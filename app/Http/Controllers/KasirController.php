<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class KasirController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function index()
    {
        $categories = Product::select('kategori')->distinct()->pluck('kategori');
        $products = Product::where('stok', '>', 0)->get();

        return view('kasir.index', compact('categories', 'products'));
    }

    public function getProducts(Request $request)
    {
        $query = Product::where('stok', '>', 0);

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->search) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'payments' => 'required|array|min:1',
            'payments.*.metode' => 'required|string',
            'payments.*.jumlah' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Hitung total
            $totalHarga = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stok < $item['jumlah']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->nama_produk} tidak cukup"
                    ], 400);
                }

                $subtotal = $product->harga_jual * $item['jumlah'];
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'nama_produk' => $product->nama_produk,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $product->harga_jual,
                    'subtotal' => $subtotal
                ];
            }

            // Validasi pembayaran
            $totalPembayaran = collect($request->payments)->sum('jumlah');
            if ($totalPembayaran < $totalHarga) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pembayaran kurang'
                ], 400);
            }

            // Ambil metode pembayaran pertama
            $metodePembayaran = $request->payments[0]['metode'];

            // SIMPAN TRANSACTION
            $transaction = Transaction::create([
                'kasir_id' => Auth::id(),
                'tanggal_transaksi' => now(),
                'total_harga' => $totalHarga,
                'metode_pembayaran' => $metodePembayaran,
                'status' => ($metodePembayaran === 'QRIS') ? 'pending' : 'selesai'
            ]);

            // SIMPAN TRANSACTION ITEMS + UPDATE STOK
            foreach ($itemsData as $itemData) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $itemData['product_id'],
                    'jumlah'         => $itemData['jumlah'],
                    'harga_satuan'   => $itemData['harga_satuan'],
                    'subtotal'       => $itemData['subtotal']
                ]);

                // Update stok
                Product::where('id', $itemData['product_id'])
                    ->decrement('stok', $itemData['jumlah']);
            }

            // Handle pembayaran berdasarkan metode
            if ($metodePembayaran === 'QRIS') {
                // Generate QRIS via Midtrans
                $qrisResult = $this->midtrans->createQrisTransaction($transaction, $itemsData);

                if (!$qrisResult['success']) {
                    throw new \Exception($qrisResult['message']);
                }

                // Update transaction dengan data Midtrans
                $transaction->update([
                    'midtrans_order_id' => $qrisResult['order_id'],
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'payment_method' => 'QRIS',
                    'transaction_id' => $transaction->id,
                    'snap_token' => $qrisResult['snap_token'],
                    'order_id' => $qrisResult['order_id'],
                    'message' => 'Silakan scan QRIS untuk pembayaran'
                ]);

            } else {
                // Pembayaran Tunai langsung success
                Payment::create([
                    'transaction_id'    => $transaction->id,
                    'metode_pembayaran' => $metodePembayaran,
                    'jumlah_bayar'      => $request->payments[0]['jumlah']
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'payment_method' => 'Tunai',
                    'message' => 'Transaksi berhasil!',
                    'transaction_id' => $transaction->id
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'ERROR SISTEM: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkQrisStatus($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if (!$transaction->midtrans_order_id) {
            return response()->json([
                'success' => false,
                'message' => 'Bukan transaksi QRIS'
            ]);
        }

        $result = $this->midtrans->checkTransactionStatus($transaction->midtrans_order_id);

        if ($result['success']) {
            $status = $result['status'];
            
            // Update status transaksi
            if (in_array($status->transaction_status, ['capture', 'settlement'])) {
                $transaction->update([
                    'status' => 'selesai',
                ]);
                // Simpan payment record
                Payment::firstOrCreate(
                    ['transaction_id' => $transaction->id],
                    [
                        'metode_pembayaran' => 'QRIS',
                        'jumlah_bayar' => $transaction->total_harga
                    ]
                );

                return response()->json([
                    'success' => true,
                    'status' => 'paid',
                    'message' => 'Pembayaran berhasil!'
                ]);
            } elseif (in_array($status->transaction_status, ['deny', 'cancel', 'expire'])) {
                $transaction->update([
                    'status' => 'gagal',
                ]);

                // Kembalikan stok
                foreach ($transaction->items as $item) {
                    $item->product->increment('stok', $item->jumlah);
                }

                return response()->json([
                    'success' => true,
                    'status' => 'failed',
                    'message' => 'Pembayaran gagal atau expired'
                ]);
            }

            return response()->json([
                'success' => true,
                'status' => 'pending',
                'message' => 'Menunggu pembayaran...'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengecek status'
        ]);
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with(['items.product', 'payments', 'kasir'])->findOrFail($id);

        $pdf = Pdf::loadView('kasir.receipt', compact('transaction'));
        return $pdf->stream('struk-' . $transaction->id . '.pdf');
    }
}