<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Transaction;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\FinancialReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class OwnerFinancialController extends Controller
{
    // Dashboard Laporan Keuangan
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $periode = Carbon::parse($bulan . '-01');
        
        // Get expenses for the month
        $expenses = Expense::whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        // Summary data
        $totalPendapatan = $this->getTotalPendapatan($periode);
        $totalBeban = $this->getTotalBeban($periode);
        $labaBersih = $totalPendapatan - $totalBeban;
        
        return view('owner.financial.index', compact(
            'expenses',
            'bulan',
            'totalPendapatan',
            'totalBeban',
            'labaBersih'
        ));
    }

    // Form Tambah Expense
    public function createExpense()
    {
        $kategoriOptions = Expense::getKategoriOptions();
        $metodeBayarOptions = Expense::getMetodeBayarOptions();
        
        return view('owner.financial.create-expense', compact(
            'kategoriOptions',
            'metodeBayarOptions'
        ));
    }

    // Simpan Expense
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|in:gaji,listrik,sewa,pajak,perlengkapan,lain',
            'jumlah' => 'required|numeric|min:0',
            'metode_bayar' => 'required|in:kas,bank',
            'keterangan' => 'nullable|string|max:500'
        ]);

        Expense::create($validated);

        return redirect()->route('owner.financial.index')
            ->with('success', 'Expense berhasil ditambahkan!');
    }

    // Generate Laporan Laba-Rugi
    public function generateLabaRugi(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $periode = Carbon::parse($bulan . '-01');
        
        $data = $this->getLabaRugiData($periode);
        
        return view('owner.financial.laba-rugi', $data);
    }

    // Generate Laporan Neraca
    public function generateNeraca(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $periode = Carbon::parse($bulan . '-01');
        
        $data = $this->getNeracaData($periode);
        
        return view('owner.financial.neraca', $data);
    }

    // Export PDF
    public function exportPDF(Request $request)
    {
        $tipe = $request->input('tipe'); // 'laba-rugi', 'neraca', atau 'penjualan'
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $periode = Carbon::parse($bulan . '-01');
        
        if ($tipe === 'laba-rugi') {
            $data = $this->getLabaRugiData($periode);
            $view = 'owner.financial.pdf.laba-rugi';
            $filename = "Laba-Rugi-{$bulan}.pdf";
            $tipeLaporan = 'Laba Rugi';
        } elseif ($tipe === 'neraca') {
            $data = $this->getNeracaData($periode);
            $view = 'owner.financial.pdf.neraca';
            $filename = "Neraca-{$bulan}.pdf";
            $tipeLaporan = 'Neraca';
        } else {
            $data = $this->getLaporanPenjualanData($periode);
            $view = 'owner.financial.pdf.penjualan';
            $filename = "Laporan-Penjualan-{$bulan}.pdf";
            $tipeLaporan = 'Penjualan';
        }
        
        $pdf = Pdf::loadView($view, $data);
        
        // Simpan file ke storage
        $path = "reports/{$filename}";
        $pdf->save(storage_path("app/public/{$path}"));
        
        // Simpan ke database reports
        Report::create([
            'periode' => $bulan,
            'tipe_laporan' => $tipeLaporan,
            'file_path' => $path
        ]);
        
        return $pdf->download($filename);
    }

    // Generate Laporan Penjualan
    public function generateLaporanPenjualan(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $periode = Carbon::parse($bulan . '-01');
        
        $data = $this->getLaporanPenjualanData($periode);
        
        return view('owner.financial.laporan-penjualan', $data);
    }

    // Helper: Get Total Pendapatan
    private function getTotalPendapatan($periode)
    {
        return Transaction::whereYear('tanggal_transaksi', $periode->year)
            ->whereMonth('tanggal_transaksi', $periode->month)
            ->where('status', 'selesai')
            ->sum('total_harga');
    }

    // Helper: Get Total Beban
    private function getTotalBeban($periode)
    {
        return Expense::whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->sum('jumlah');
    }

    // Helper: Get Laba Rugi Data
    private function getLabaRugiData($periode)
    {
        // Pendapatan
        $pendapatanQuery = Transaction::whereYear('tanggal_transaksi', $periode->year)
            ->whereMonth('tanggal_transaksi', $periode->month)
            ->where('status', 'selesai');
        
        $totalPendapatan = $pendapatanQuery->sum('total_harga');
        $jumlahTransaksi = $pendapatanQuery->count();
        
        // Beban per kategori
        $bebanPerKategori = Expense::whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->select('kategori', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->get()
            ->pluck('total', 'kategori')
            ->toArray();
        
        $totalBeban = array_sum($bebanPerKategori);
        $labaBersih = $totalPendapatan - $totalBeban;
        
        return [
            'periode' => $periode->format('F Y'),
            'bulan' => $periode->format('Y-m'),
            'totalPendapatan' => $totalPendapatan,
            'jumlahTransaksi' => $jumlahTransaksi,
            'bebanPerKategori' => $bebanPerKategori,
            'totalBeban' => $totalBeban,
            'labaBersih' => $labaBersih
        ];
    }

    // Helper: Get Neraca Data
    private function getNeracaData($periode)
    {
        // Kas & Bank dari transaksi
        $kasFromTransaksi = Transaction::whereYear('tanggal_transaksi', '<=', $periode->year)
            ->where(function($q) use ($periode) {
                $q->whereYear('tanggal_transaksi', '<', $periode->year)
                  ->orWhere(function($q2) use ($periode) {
                      $q2->whereYear('tanggal_transaksi', $periode->year)
                         ->whereMonth('tanggal_transaksi', '<=', $periode->month);
                  });
            })
            ->where('status', 'selesai')
            ->where(function($q) {
                $q->where('metode_pembayaran', 'cash')
                  ->orWhere('metode_pembayaran', 'tunai');
            })
            ->sum('total_harga');
        
        $bankFromTransaksi = Transaction::whereYear('tanggal_transaksi', '<=', $periode->year)
            ->where(function($q) use ($periode) {
                $q->whereYear('tanggal_transaksi', '<', $periode->year)
                  ->orWhere(function($q2) use ($periode) {
                      $q2->whereYear('tanggal_transaksi', $periode->year)
                         ->whereMonth('tanggal_transaksi', '<=', $periode->month);
                  });
            })
            ->where('status', 'selesai')
            ->whereIn('metode_pembayaran', ['bank', 'transfer', 'debit', 'qris'])
            ->sum('total_harga');
        
        // Expenses dari Kas & Bank
        $kasFromExpenses = Expense::whereYear('tanggal', '<=', $periode->year)
            ->where(function($q) use ($periode) {
                $q->whereYear('tanggal', '<', $periode->year)
                  ->orWhere(function($q2) use ($periode) {
                      $q2->whereYear('tanggal', $periode->year)
                         ->whereMonth('tanggal', '<=', $periode->month);
                  });
            })
            ->where('metode_bayar', 'kas')
            ->sum('jumlah');
        
        $bankFromExpenses = Expense::whereYear('tanggal', '<=', $periode->year)
            ->where(function($q) use ($periode) {
                $q->whereYear('tanggal', '<', $periode->year)
                  ->orWhere(function($q2) use ($periode) {
                      $q2->whereYear('tanggal', $periode->year)
                         ->whereMonth('tanggal', '<=', $periode->month);
                  });
            })
            ->where('metode_bayar', 'bank')
            ->sum('jumlah');
        
        $kas = $kasFromTransaksi - $kasFromExpenses;
        $bank = $bankFromTransaksi - $bankFromExpenses;
        $totalAset = $kas + $bank;
        
        // Modal (simplified: aset = modal untuk UMKM)
        $modal = $totalAset;
        
        return [
            'periode' => $periode->format('F Y'),
            'bulan' => $periode->format('Y-m'),
            'kas' => $kas,
            'bank' => $bank,
            'totalAset' => $totalAset,
            'modal' => $modal
        ];
    }

    // Helper: Get Laporan Penjualan Data
    private function getLaporanPenjualanData($periode)
    {
        // Transaksi per hari
        $transaksiPerHari = Transaction::whereYear('tanggal_transaksi', $periode->year)
            ->whereMonth('tanggal_transaksi', $periode->month)
            ->where('status', 'selesai')
            ->select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_penjualan')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Produk terlaris
        $produkTerlaris = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereYear('transactions.tanggal_transaksi', $periode->year)
            ->whereMonth('transactions.tanggal_transaksi', $periode->month)
            ->where('transactions.status', 'selesai')
            ->select(
                'products.nama_produk',
                'products.kategori',
                DB::raw('SUM(transaction_items.jumlah) as total_terjual'),
                DB::raw('SUM(transaction_items.subtotal) as total_pendapatan')
            )
            ->groupBy('products.id', 'products.nama_produk', 'products.kategori')
            ->orderBy('total_terjual', 'desc')
            ->limit(10)
            ->get();
        
        // Penjualan per metode pembayaran
        $penjualanPerMetode = Transaction::whereYear('tanggal_transaksi', $periode->year)
            ->whereMonth('tanggal_transaksi', $periode->month)
            ->where('status', 'selesai')
            ->select(
                'metode_pembayaran',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_penjualan')
            )
            ->groupBy('metode_pembayaran')
            ->get();
        
        // Penjualan per kasir
        $penjualanPerKasir = Transaction::join('users', 'transactions.kasir_id', '=', 'users.id')
            ->whereYear('transactions.tanggal_transaksi', $periode->year)
            ->whereMonth('transactions.tanggal_transaksi', $periode->month)
            ->where('transactions.status', 'selesai')
            ->select(
                'users.name',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(transactions.total_harga) as total_penjualan')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_penjualan', 'desc')
            ->get();
        
        // Summary
        $totalPenjualan = $transaksiPerHari->sum('total_penjualan');
        $totalTransaksi = $transaksiPerHari->sum('jumlah_transaksi');
        $rataRataTransaksi = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;
        
        return [
            'periode' => $periode->format('F Y'),
            'bulan' => $periode->format('Y-m'),
            'transaksiPerHari' => $transaksiPerHari,
            'produkTerlaris' => $produkTerlaris,
            'penjualanPerMetode' => $penjualanPerMetode,
            'penjualanPerKasir' => $penjualanPerKasir,
            'totalPenjualan' => $totalPenjualan,
            'totalTransaksi' => $totalTransaksi,
            'rataRataTransaksi' => $rataRataTransaksi
        ];
    }
}