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