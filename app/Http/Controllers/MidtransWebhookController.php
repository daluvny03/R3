<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function handle(Request $request)
    {
        // Verify notification
        $notification = $request->all();
        
        Log::info('Midtrans Webhook Received:', $notification);

        // Verifikasi signature key
        $signatureKey = hash('sha512', 
            $notification['order_id'] . 
            $notification['status_code'] . 
            $notification['gross_amount'] . 
            config('midtrans.server_key')
        );

        if ($signatureKey !== $notification['signature_key']) {
            Log::error('Invalid Midtrans signature');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Handle notifikasi
        $result = $this->midtrans->handleNotification((object) $notification);

        // Cari transaksi berdasarkan order_id
        $transaction = Transaction::where('midtrans_order_id', $result['order_id'])->first();

        if (!$transaction) {
            Log::error('Transaction not found: ' . $result['order_id']);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status transaksi
        $transaction->update([

            'status' => $result['status']
        ]);

        // Jika sukses, buat payment record
        if ($result['status'] === 'success') {
            Payment::firstOrCreate(
                ['transaction_id' => $transaction->id],
                [
                    'metode_pembayaran' => $result['payment_type'] ?? 'qris',
                    'jumlah_bayar' => $transaction->total_harga
                ]
            );
        }

        // Jika gagal, kembalikan stok
        if ($result['status'] === 'failed') {
            foreach ($transaction->items as $item) {
                $item->product->increment('stok', $item->jumlah);
            }
        }

        Log::info('Transaction updated:', [
            'order_id' => $result['order_id'],
            'status' => $result['status']
        ]);

        return response()->json(['message' => 'Notification handled']);
    }
}