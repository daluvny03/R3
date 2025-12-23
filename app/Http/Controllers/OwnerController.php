<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Services\ClaudeAIService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Analysis_AI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner']);
    }

    public function dashboard()
    {
        // KPI Cards
        $totalSales = Transaction::where('status', 'selesai')
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('total_harga');

        $produkTerjual = TransactionItem::sum('jumlah');

        $totalPurchases = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'selesai')
            ->whereYear('transactions.tanggal_transaksi', Carbon::now()->year)
            ->sum(DB::raw('transaction_items.jumlah * products.harga_beli'));

        $totalLaba = $totalSales - $totalPurchases;

        // Monthly Sales & Purchases
        $monthlySales = [];
        $monthlyPurchases = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[] = Transaction::where('status', 'selesai')
                ->whereYear('tanggal_transaksi', Carbon::now()->year)
                ->whereMonth('tanggal_transaksi', $i)
                ->sum('total_harga');

            $monthlyPurchases[] = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->where('transactions.status', 'selesai')
                ->whereYear('transactions.tanggal_transaksi', Carbon::now()->year)
                ->whereMonth('transactions.tanggal_transaksi', $i)
                ->sum(DB::raw('transaction_items.jumlah * products.harga_beli'));
        }

        // Revenue & Expense
        $revenue = $totalSales;
        $expense = $totalPurchases;

        $monthlyRevenue = $monthlySales;
        $monthlyExpense = $monthlyPurchases;


        // Recent Transactions
        $recentTransactions = Transaction::with('kasir')
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(2)
            ->get();

        return view('owner.dashboard', compact(
            'totalSales',
            'produkTerjual',
            'totalPurchases',
            'totalLaba',
            'monthlySales',
            'monthlyPurchases',
            'revenue',
            'expense',
            'monthlyRevenue',
            'monthlyExpense',
            'recentTransactions'
        ));
    }

    public function laporanKeuangan(Request $request)
    {
        $jenisLaporan = $request->get('jenis_laporan', 'penjualan');
        $periode = $request->get('periode', 'bulan-ini');
        
        // Determine date range
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalAkhir = $request->get('tanggal_akhir');

        switch ($periode) {
            case 'bulan-ini':
                $tanggalMulai = Carbon::now()->startOfMonth()->toDateString();
                $tanggalAkhir = Carbon::now()->endOfMonth()->toDateString();
                $periodText = 'Bulan ' . Carbon::now()->format('F Y');
                break;
            case 'bulan-lalu':
                $tanggalMulai = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                $tanggalAkhir = Carbon::now()->subMonth()->endOfMonth()->toDateString();
                $periodText = 'Bulan ' . Carbon::now()->subMonth()->format('F Y');
                break;
            case 'tahun-ini':
                $tanggalMulai = Carbon::now()->startOfYear()->toDateString();
                $tanggalAkhir = Carbon::now()->endOfYear()->toDateString();
                $periodText = 'Tahun ' . Carbon::now()->year;
                break;
            case 'custom':
                $periodText = Carbon::parse($tanggalMulai)->format('d M Y') . ' - ' . Carbon::parse($tanggalAkhir)->format('d M Y');
                break;
            default:
                $tanggalMulai = Carbon::now()->startOfMonth()->toDateString();
                $tanggalAkhir = Carbon::now()->endOfMonth()->toDateString();
                $periodText = 'Bulan ' . Carbon::now()->format('F Y');
        }

        // Chart Data
        $transactions = Transaction::where('status', 'selesai')
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('tanggal_transaksi')
            ->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($transactions->groupBy(function($date) {
            return Carbon::parse($date->tanggal_transaksi)->format('d M');
        }) as $date => $trans) {
            $chartLabels[] = $date;
            $chartData[] = $trans->sum('total_harga');
        }

        // Prepare data based on report type
        $data = $this->prepareReportData($jenisLaporan, $tanggalMulai, $tanggalAkhir, $periodText);

        return view('owner.laporan-keuangan', array_merge($data, [
            'jenisLaporan' => $jenisLaporan,
            'periode' => $periode,
            'tanggalMulai' => $tanggalMulai,
            'tanggalAkhir' => $tanggalAkhir,
            'periodText' => $periodText,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ]));
    }

    private function prepareReportData($jenisLaporan, $tanggalMulai, $tanggalAkhir, $periodText)
    {
        switch ($jenisLaporan) {
            case 'laba-rugi':
                return $this->getLabaRugiData($tanggalMulai, $tanggalAkhir, $periodText);
            case 'penjualan':
                return $this->getPenjualanData($tanggalMulai, $tanggalAkhir, $periodText);
            case 'neraca':
                return $this->getNeracaData($tanggalAkhir, $periodText);
            default:
                return [];
        }
    }

    private function getLabaRugiData($tanggalMulai, $tanggalAkhir, $periodText)
    {
        // Penjualan
        $penjualanKotor = Transaction::where('status', 'selesai')
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->sum('total_harga');

        $returPenjualan = Transaction::where('status', 'retur')
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->sum('total_harga');

        $penjualanBersih = $penjualanKotor - $returPenjualan;

        // HPP
        $persediaanAwal = Product::sum(DB::raw('harga_beli * stok'));
        
        $pembelian = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'selesai')
            ->whereBetween('transactions.tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->sum(DB::raw('transaction_items.jumlah * products.harga_beli'));

        $returPembelian = 0;
        $pembelianBersih = $pembelian - $returPembelian;
        $barangTersedia = $persediaanAwal + $pembelianBersih;
        $persediaanAkhir = Product::sum(DB::raw('harga_beli * stok'));
        $totalHPP = $barangTersedia - $persediaanAkhir;

        // Laba Kotor
        $labaKotor = $penjualanBersih - $totalHPP;

        // Beban Operasional
        $bebanGaji = 15000000; // Mock data
        $bebanSewa = 5000000;
        $bebanListrik = 2000000;
        $bebanPerlengkapan = 1500000;
        $bebanPenyusutan = 3000000;
        $bebanLainLain = 2500000;
        $totalBebanOperasional = $bebanGaji + $bebanSewa + $bebanListrik + $bebanPerlengkapan + $bebanPenyusutan + $bebanLainLain;

        // Laba Operasional
        $labaOperasional = $labaKotor - $totalBebanOperasional;

        // Pendapatan & Beban Lain
        $pendapatanBunga = 500000;
        $bebanBunga = 1000000;
        $totalPendapatanBebanLain = $pendapatanBunga - $bebanBunga;

        // Laba Sebelum Pajak
        $labaSebelumPajak = $labaOperasional + $totalPendapatanBebanLain;

        // Pajak
        $pajakPenghasilan = $labaSebelumPajak * 0.10; // 10% tax

        // Laba Bersih
        $labaBersih = $labaSebelumPajak - $pajakPenghasilan;

        return compact(
            'periodText',
            'penjualanKotor',
            'returPenjualan',
            'penjualanBersih',
            'persediaanAwal',
            'pembelian',
            'returPembelian',
            'pembelianBersih',
            'barangTersedia',
            'persediaanAkhir',
            'totalHPP',
            'labaKotor',
            'bebanGaji',
            'bebanSewa',
            'bebanListrik',
            'bebanPerlengkapan',
            'bebanPenyusutan',
            'bebanLainLain',
            'totalBebanOperasional',
            'labaOperasional',
            'pendapatanBunga',
            'bebanBunga',
            'totalPendapatanBebanLain',
            'labaSebelumPajak',
            'pajakPenghasilan',
            'labaBersih'
        );
    }

    private function getPenjualanData($tanggalMulai, $tanggalAkhir, $periodText)
    {
        $totalTransaksi = Transaction::where('status', 'selesai')
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->count();

        $totalPenjualan = Transaction::where('status', 'selesai')
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->sum('total_harga');

        $rataRataTransaksi = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;

        // Penjualan per Metode Pembayaran
        $penjualanPerMetode = Payment::join('transactions', 'payments.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'selesai')
            ->whereBetween('transactions.tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->select('payments.metode_pembayaran as metode', DB::raw('COUNT(DISTINCT transaction_id) as jumlah_transaksi'), DB::raw('SUM(jumlah_bayar) as total'))
            ->groupBy('payments.metode_pembayaran')
            ->get()
            ->map(function($item) use ($totalPenjualan) {
                $item->persentase = $totalPenjualan > 0 ? ($item->total / $totalPenjualan) * 100 : 0;
                return $item;
            });

        // Top 10 Products
        $topProducts = TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'selesai')
            ->whereBetween('transactions.tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->select('products.nama_produk', 'products.kategori', DB::raw('SUM(transaction_items.jumlah) as qty_terjual'), DB::raw('SUM(transaction_items.subtotal) as total_penjualan'))
            ->groupBy('products.id', 'products.nama_produk', 'products.kategori')
            ->orderBy('total_penjualan', 'desc')
            ->limit(10)
            ->get();

        // Penjualan per Kasir
        $penjualanPerKasir = Transaction::join('users', 'transactions.kasir_id', '=', 'users.id')
            ->where('transactions.status', 'selesai')
            ->whereBetween('transactions.tanggal_transaksi', [$tanggalMulai, $tanggalAkhir])
            ->select('users.name as nama_kasir', DB::raw('COUNT(*) as jumlah_transaksi'), DB::raw('SUM(transactions.total_harga) as total'))
            ->groupBy('users.id', 'users.name')
            ->get()
            ->map(function($item) use ($totalPenjualan) {
                $item->persentase = $totalPenjualan > 0 ? ($item->total / $totalPenjualan) * 100 : 0;
                return $item;
            });

        return compact(
            'periodText',
            'totalTransaksi',
            'totalPenjualan',
            'rataRataTransaksi',
            'penjualanPerMetode',
            'topProducts',
            'penjualanPerKasir'
        );
    }

    private function getNeracaData($tanggalAkhir, $periodText)
    {
        $tanggalNeraca = Carbon::parse($tanggalAkhir)->format('d F Y');

        // Aktiva Lancar
        $kas = 50000000;
        $bank = 75000000;
        $piutangDagang = 15000000;
        $persediaanBarang = Product::sum(DB::raw('harga_beli * stok'));
        $perlengkapan = 5000000;
        $totalAktivaLancar = $kas + $bank + $piutangDagang + $persediaanBarang + $perlengkapan;

        // Aktiva Tetap
        $tanah = 200000000;
        $bangunan = 150000000;
        $akumPenyusutanBangunan = 30000000;
        $peralatan = 50000000;
        $akumPenyusutanPeralatan = 15000000;
        $kendaraan = 80000000;
        $akumPenyusutanKendaraan = 20000000;
        $totalAktivaTetap = $tanah + ($bangunan - $akumPenyusutanBangunan) + ($peralatan - $akumPenyusutanPeralatan) + ($kendaraan - $akumPenyusutanKendaraan);

        // Total Aktiva
        $totalAktiva = $totalAktivaLancar + $totalAktivaTetap;

        // Kewajiban Lancar
        $hutangDagang = 20000000;
        $hutangGaji = 8000000;
        $hutangPajak = 5000000;
        $totalKewajibanLancar = $hutangDagang + $hutangGaji + $hutangPajak;

        // Kewajiban Jangka Panjang
        $hutangBank = 100000000;
        $hutangJangkaPanjang = 50000000;
        $totalKewajibanJangkaPanjang = $hutangBank + $hutangJangkaPanjang;

        // Total Kewajiban
        $totalKewajiban = $totalKewajibanLancar + $totalKewajibanJangkaPanjang;

        // Ekuitas
        $modalAwal = 200000000;
        $labaDitahan = 50000000;
        $labaTahunBerjalan = Transaction::where('status', 'selesai')
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('total_harga') * 0.20; // 20% profit margin
        $totalEkuitas = $modalAwal + $labaDitahan + $labaTahunBerjalan;

        // Total Pasiva
        $totalPasiva = $totalKewajiban + $totalEkuitas;

        return compact(
            'tanggalNeraca',
            'kas',
            'bank',
            'piutangDagang',
            'persediaanBarang',
            'perlengkapan',
            'totalAktivaLancar',
            'tanah',
            'bangunan',
            'akumPenyusutanBangunan',
            'peralatan',
            'akumPenyusutanPeralatan',
            'kendaraan',
            'akumPenyusutanKendaraan',
            'totalAktivaTetap',
            'totalAktiva',
            'hutangDagang',
            'hutangGaji',
            'hutangPajak',
            'totalKewajibanLancar',
            'hutangBank',
            'hutangJangkaPanjang',
            'totalKewajibanJangkaPanjang',
            'totalKewajiban',
            'modalAwal',
            'labaDitahan',
            'labaTahunBerjalan',
            'totalEkuitas',
            'totalPasiva'
        );
    }

    public function generatePDF(Request $request)
    {
        $jenisLaporan = $request->get('jenis_laporan', 'laba-rugi');
        $periode = $request->get('periode', 'bulan-ini');
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalAkhir = $request->get('tanggal_akhir');

        // Determine date range
        switch ($periode) {
            case 'bulan-ini':
                $tanggalMulai = Carbon::now()->startOfMonth()->toDateString();
                $tanggalAkhir = Carbon::now()->endOfMonth()->toDateString();
                $periodText = 'Bulan ' . Carbon::now()->format('F Y');
                break;
            case 'bulan-lalu':
                $tanggalMulai = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                $tanggalAkhir = Carbon::now()->subMonth()->endOfMonth()->toDateString();
                $periodText = 'Bulan ' . Carbon::now()->subMonth()->format('F Y');
                break;
            case 'tahun-ini':
                $tanggalMulai = Carbon::now()->startOfYear()->toDateString();
                $tanggalAkhir = Carbon::now()->endOfYear()->toDateString();
                $periodText = 'Tahun ' . Carbon::now()->year;
                break;
            case 'custom':
                $periodText = Carbon::parse($tanggalMulai)->format('d M Y') . ' - ' . Carbon::parse($tanggalAkhir)->format('d M Y');
                break;
        }

        $data = $this->prepareReportData($jenisLaporan, $tanggalMulai, $tanggalAkhir, $periodText);
        
        $pdf = Pdf::loadView('owner.reports.' . $jenisLaporan, $data);
        
        $filename = 'Laporan-' . ucwords(str_replace('-', ' ', $jenisLaporan)) . '-' . Carbon::now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function analisaAI()
    {
        // Placeholder untuk halaman Analisa AI
        return view('owner.analisa-ai');
    }

    public function transactions()
    {
        $transactions = Transaction::with('kasir')
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(20);
        $statistics = [
            'total_transaksi' => Transaction::count(),
            'total_pendapatan' => Transaction::sum('total_harga'),
            'transaksi_hari_ini' => Transaction::whereDate('tanggal_transaksi', today())->count(),
            'pendapatan_hari_ini' => Transaction::whereDate('tanggal_transaksi', today())->sum('total_harga')
        ];

        return view('admin.transactions.index', compact('transactions', 'statistics'));
    }
    public function aiAnalysis(Request $request, $type)
{
    $aiService = new ClaudeAIService();
    
    try {
        DB::beginTransaction();
        
        switch ($type) {
            case 'sales_prediction':
                $result = $aiService->predictSales(30);
                break;
                
            case 'stock_recommendation':
                $result = $aiService->recommendStock();
                break;
                
            case 'trends':
                $result = $aiService->analyzeTrends();
                break;
                
            case 'anomaly':
                $result = $aiService->detectAnomalies();
                break;
                
            case 'customer':
                $result = $aiService->analyzeCustomerBehavior();
                break;
                
            case 'comprehensive':
                $result = [
                    'sales_prediction' => $aiService->predictSales(30),
                    'stock_recommendation' => $aiService->recommendStock(),
                    'trends' => $aiService->analyzeTrends(),
                    'anomalies' => $aiService->detectAnomalies(),
                    'customer_insights' => $aiService->analyzeCustomerBehavior(),
                    'generated_at' => Carbon::now()->toDateTimeString()
                ];
                break;
                
            default:
                return response()->json(['error' => 'Invalid analysis type'], 400);
        }
        
        // 🔥 SAVE TO DATABASE
        $analysis = Analysis_AI::create([
            'type' => $type,
            'hasil' => $result
        ]);
        
        DB::commit();
        
        // Return with analysis ID
        return response()->json([
            'success' => true,
            'analysis_id' => $analysis->id,
            'data' => $result,
            'saved_at' => $analysis->created_at->toDateTimeString()
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'type' => get_class($e)
        ], 500);
    }
}
}