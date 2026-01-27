<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Carbon\Carbon;

class ClaudeAIService
{
    private $apiKey;
    private $apiUrl = 'https://api.anthropic.com/v1/messages';

    public function __construct()
    {
        $this->apiKey = env('ANTHROPIC_API_KEY');
    }

    /**
     * Analisis Prediksi Penjualan
     */
    public function predictSales($period = 30)
    {
        // Ambil RINGKASAN data penjualan historis
        $salesData = $this->getSalesHistory($period * 3);

        $prompt = $this->buildSalesPredictionPrompt($salesData, $period);

        return $this->callClaudeAPI($prompt);
    }

    /**
     * Rekomendasi Stok Optimal
     */
    public function recommendStock()
    {
        // Ambil RINGKASAN data stok
        $stockData = $this->prepareStockData();

        $prompt = $this->buildStockRecommendationPrompt($stockData);

        return $this->callClaudeAPI($prompt);
    }

    /**
     * Analisis Tren Penjualan
     */
    public function analyzeTrends()
    {
        $trendsData = $this->getTrendsData();

        $prompt = $this->buildTrendsAnalysisPrompt($trendsData);

        return $this->callClaudeAPI($prompt);
    }

    /**
     * Deteksi Anomali
     */
    public function detectAnomalies()
    {
        $recentData = $this->getRecentTransactions(30);
        $historicalAverage = $this->getHistoricalAverage(90);

        $prompt = $this->buildAnomalyDetectionPrompt($recentData, $historicalAverage);

        return $this->callClaudeAPI($prompt);
    }

    /**
     * Customer Insights
     */
    public function analyzeCustomerBehavior()
    {
        $customerData = $this->getCustomerData();

        $prompt = $this->buildCustomerInsightsPrompt($customerData);

        return $this->callClaudeAPI($prompt);
    }

    /**
     * Build Prompt untuk Prediksi Penjualan
     */
    private function buildSalesPredictionPrompt($salesData, $period)
    {
        $dataJson = json_encode($salesData, JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Anda adalah AI Business Advisor untuk OWNER usaha retail.
Tugas Anda adalah membantu OWNER mengambil KEPUTUSAN HARIAN.

KONTEKS:
- Data berasal dari sistem POS
- Owner membaca hasil ini maksimal 1 menit
- Fokus pada tindakan, bukan laporan panjang

DATA PENJUALAN HISTORIS (RINGKASAN):
{$dataJson}

ATURAN OUTPUT (WAJIB DAN KETAT):
1. Output HARUS JSON VALID
2. DILARANG menggunakan markdown (``` atau sejenisnya)
3. DILARANG menambahkan teks di luar JSON
4. Semua angka HARUS number, bukan string
5. Bahasa singkat, jelas, untuk owner non-teknis

FORMAT OUTPUT (WAJIB IKUT):

{
  "today_health": {
    "status": "good | warning | critical",
    "estimated_sales_today": number,
    "estimated_transactions_today": number
  },
  "next_7_days_forecast": {
    "total_sales": number,
    "total_transactions": number,
    "confidence": "high | medium | low"
  },
  "priority_actions": [
    {
      "priority": "high | medium | low",
      "action": string,
      "reason": string
    }
  ],
  "risks_to_watch": [
    string
  ],
  "key_insights": [
    string
  ],
  "growth_trend": "increasing | decreasing | stable"
}

TUJUAN:
Jawaban Anda HARUS membuat owner langsung tahu:
- Kondisi bisnis hari ini
- Apa yang harus dilakukan hari ini / minggu ini
- Risiko paling penting yang perlu diperhatikan

PROMPT;
    }

    /**
     * Build Prompt untuk Rekomendasi Stok
     */
    private function buildStockRecommendationPrompt($stockData)
    {
        $dataJson = json_encode($stockData, JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Anda adalah AI Inventory Advisor untuk OWNER usaha retail.
Tugas Anda adalah membantu OWNER mengambil keputusan STOK HARI INI.

KONTEKS:
- Data berasal dari sistem POS
- Owner tidak melihat detail gudang, hanya keputusan
- Fokus pada cash flow, risiko kehabisan stok, dan efisiensi modal

DATA STOK & PENJUALAN PRODUK (RINGKASAN):
{$dataJson}

ATURAN OUTPUT (WAJIB DAN KETAT):
1. Output HARUS JSON VALID
2. DILARANG markdown (``` atau sejenisnya)
3. DILARANG teks di luar JSON
4. Semua angka HARUS number, bukan string
5. Maksimal 5 item per kategori agar mudah diputuskan

FORMAT OUTPUT (WAJIB IKUT):

{
  "stock_health": {
    "status": "good | warning | critical",
    "summary": string
  },
  "priority_restock": [
    {
      "product_id": number,
      "product_name": string,
      "current_stock": number,
      "recommended_order_quantity": number,
      "estimated_days_until_stockout": number,
      "urgency": "high | medium | low",
      "reason": string
    }
  ],
  "overstock_risks": [
    {
      "product_id": number,
      "product_name": string,
      "current_stock": number,
      "estimated_excess_units": number,
      "risk": string,
      "recommended_action": string
    }
  ],
  "fast_moving_products": [
    {
      "product_id": number,
      "product_name": string,
      "average_daily_sales": number,
      "note": string
    }
  ],
  "slow_moving_products": [
    {
      "product_id": number,
      "product_name": string,
      "days_since_last_sale": number,
      "recommended_action": string
    }
  ],
  "budget_guidance": {
    "estimated_restock_cost": number,
    "must_buy_today": [string],
    "can_delay": [string]
  }
}

TUJUAN:
Jawaban Anda HARUS membuat owner langsung tahu:
- Apakah stok bisnisnya sehat hari ini
- Produk mana yang WAJIB dibeli sekarang
- Produk mana yang mengunci cash flow
- Cara mengalokasikan budget stok secara aman

PROMPT;
    }

    /**
     * Build Prompt untuk Analisis Tren
     */
    private function buildTrendsAnalysisPrompt($trendsData)
    {
        $dataJson = json_encode($trendsData, JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Anda adalah AI Business Trend Advisor untuk OWNER usaha retail.
Tugas Anda adalah membantu OWNER memahami ARAH bisnis,
bukan membaca laporan panjang.

KONTEKS:
- Data berawal dari sistem POS
- Trend digunakan untuk keputusan mingguan & bulanan
- Output harus ringkas dan mudah dipahami

DATA HISTORIS PENJUALAN & KATEGORI (RINGKASAN):
{$dataJson}

ATURAN OUTPUT (WAJIB DAN KETAT):
1. Output HARUS JSON VALID
2. DILARANG markdown (``` atau sejenisnya)
3. DILARANG teks di luar JSON
4. Semua angka HARUS number (bukan string persentase)
5. Maksimal 3 item per kategori agar fokus

FORMAT OUTPUT (WAJIB IKUT):

{
  "business_trend": {
    "direction": "growing | declining | stable",
    "monthly_growth_rate": number,
    "summary": string
  },
  "category_signals": [
    {
      "category": string,
      "signal": "positive | neutral | negative",
      "growth_rate": number,
      "implication": string
    }
  ],
  "product_signals": {
    "rising": [
      {
        "product_name": string,
        "growth_rate": number,
        "note": string
      }
    ],
    "declining": [
      {
        "product_name": string,
        "decline_rate": number,
        "risk": string
      }
    ]
  },
  "peak_patterns": {
    "top_days": [string],
    "top_hours": [string],
    "seasonal_note": string
  },
  "opportunities": [
    {
      "type": "expand | optimize | promote",
      "description": string
    }
  ],
  "threats": [
    string
  ]
}

TUJUAN:
Jawaban Anda HARUS membuat owner langsung paham:
- Arah bisnis saat ini
- Kategori/produk mana yang perlu difokuskan
- Pola waktu terbaik untuk jualan
- Peluang & ancaman paling relevan

PROMPT;
    }

    /**
     * Build Prompt untuk Deteksi Anomali
     */
    private function buildAnomalyDetectionPrompt($recentData, $historicalAverage)
    {
        $recentJson = json_encode($recentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $historicalJson = json_encode($historicalAverage, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Kamu adalah AI analis fraud & anomaly detection untuk sistem POS retail.

Tugas kamu:
- Mendeteksi anomali yang BERDAMPAK KE KEPUTUSAN OWNER
- Abaikan fluktuasi kecil yang normal
- Fokus ke anomali yang butuh tindakan

DATA TRANSAKSI 30 HARI TERAKHIR (RINGKASAN):
{$recentJson}

RATA-RATA HISTORIS (90 HARI):
{$historicalJson}

Kembalikan ANALISIS dalam FORMAT JSON VALID (tanpa markdown, tanpa penjelasan tambahan):

{
    "anomalies_detected": true,
    "critical_anomalies": [
        {
            "type": "unusual_spike | unusual_drop",
            "date": "YYYY-MM-DD",
            "metric": "sales | transactions | avg_transaction_value",
            "actual_value": 0,
            "expected_range": "min-max",
            "severity": "high | medium | low",
            "possible_causes": [
                "Cause 1",
                "Cause 2"
            ],
            "recommended_actions": [
                "Action 1",
                "Action 2"
            ]
        }
    ],
    "positive_anomalies": [
        {
            "date": "YYYY-MM-DD",
            "description": "Exceptional performance",
            "what_to_repeat": "Actionable lesson"
        }
    ],
    "negative_anomalies": [
        {
            "date": "YYYY-MM-DD",
            "description": "Significant drop",
            "urgency": "high | medium | low",
            "action_required": true
        }
    ],
    "owner_alerts": [
        {
            "priority": "high | medium",
            "message": "Alert message for owner"
        }
    ],
    "summary_for_owner": "Ringkasan singkat 2-3 kalimat untuk owner"
}
PROMPT;
    }

    /**
     * Build Prompt untuk Customer Insights
     */
    private function buildCustomerInsightsPrompt($customerData)
    {
        $dataJson = json_encode($customerData, JSON_PRETTY_PRINT);

        return <<<PROMPT
Anda adalah AI analis bisnis retail yang fokus pada pengambilan keputusan owner.

Tugas Anda:
Menganalisis perilaku pelanggan berdasarkan data transaksi dan memberikan insight yang praktis dan actionable.

DATA PELANGGAN & TRANSAKSI (RINGKASAN):
{$dataJson}

ATURAN OUTPUT (WAJIB):
- Output HARUS berupa JSON valid
- TIDAK boleh ada teks di luar JSON
- Gunakan struktur persis seperti di bawah
- Maksimal 5 item per array
- Gunakan angka tanpa simbol mata uang

FORMAT JSON:
{
  "summary": "Ringkasan kondisi pelanggan secara keseluruhan (1 kalimat)",
  "key_segments": [
    {
      "segment_name": "High Value / Regular / Occasional",
      "percentage": 0,
      "average_transaction": 0,
      "characteristics": "Deskripsi singkat",
      "recommended_strategy": "Strategi yang disarankan"
    }
  ],
  "purchase_behavior": {
    "average_basket_size": 0,
    "repeat_purchase_rate": "high/medium/low",
    "common_product_combinations": [
      ["Produk A", "Produk B"]
    ]
  },
  "customer_value": {
    "average_clv": 0,
    "top_customer_clv": 0,
    "focus_recommendation": "Apa yang harus difokuskan owner"
  },
  "churn_risk": [
    {
      "customer_group": "Deskripsi segmen",
      "estimated_count": 0,
      "last_activity": "X hari lalu",
      "risk_level": "high/medium/low",
      "win_back_action": "Aksi konkret"
    }
  ],
  "preferences": {
    "preferred_payment_methods": ["Cash", "QRIS"],
    "peak_shopping_time": "Hari & jam",
    "preferred_categories": ["Kategori A", "Kategori B"]
  },
  "priority_actions": [
    {
      "priority": "high/medium/low",
      "action": "Aksi yang harus dilakukan owner",
      "expected_impact": "Dampak bisnis"
    }
  ]
}
PROMPT;
    }

    /**
     * Call Claude API
     */
    private function callClaudeAPI($prompt)
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'error' => 'ANTHROPIC_API_KEY not configured in .env file. Please add your API key.'
                ];
            }

            $response = Http::timeout(90)
                ->retry(3, 100)
                ->withHeaders([
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ])->post($this->apiUrl, [
                    'model' => 'claude-sonnet-4-20250514',
                    'max_tokens' => 4096,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $content = $result['content'][0]['text'] ?? '';

                // Clean up markdown code blocks if present
                $content = preg_replace('/```json\s*/i', '', $content);
                $content = preg_replace('/```\s*$/i', '', $content);
                $content = trim($content);

                // Try to parse as JSON
                if ($this->isJson($content)) {
                    return json_decode($content, true);
                }

                // If not JSON, return as formatted text
                return [
                    'analysis' => $content,
                    'raw_text' => true
                ];
            }

            return [
                'error' => 'API call failed: ' . $response->status() . ' - ' . $response->body()
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get Sales History - RINGKASAN (bukan raw data per hari)
     */
    private function getSalesHistory($days)
    {
        // Ambil statistik ringkasan, bukan data per hari
        $summary = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(total_harga) as total_revenue,
                AVG(total_harga) as avg_transaction_value,
                MAX(total_harga) as max_transaction,
                MIN(total_harga) as min_transaction,
                STDDEV(total_harga) as revenue_volatility
            ')
            ->first();

        // Trend analysis - bandingkan periode awal vs akhir
        $firstHalfDays = (int)($days / 2);
        $firstHalf = Transaction::where('status', 'selesai')
            ->whereBetween('tanggal_transaksi', [
                Carbon::now()->subDays($days),
                Carbon::now()->subDays($firstHalfDays)
            ])
            ->selectRaw('COUNT(*) as transactions, SUM(total_harga) as revenue')
            ->first();

        $secondHalf = Transaction::where('status', 'selesai')
            ->whereBetween('tanggal_transaksi', [
                Carbon::now()->subDays($firstHalfDays),
                Carbon::now()
            ])
            ->selectRaw('COUNT(*) as transactions, SUM(total_harga) as revenue')
            ->first();

        $revenueGrowth = $firstHalf->revenue > 0
            ? (($secondHalf->revenue - $firstHalf->revenue) / $firstHalf->revenue) * 100
            : 0;

        // Best & worst days
        $bestDay = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DATE(tanggal_transaksi) as date, SUM(total_harga) as daily_sales')
            ->groupBy('date')
            ->orderByDesc('daily_sales')
            ->first();

        $worstDay = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DATE(tanggal_transaksi) as date, SUM(total_harga) as daily_sales')
            ->groupBy('date')
            ->orderBy('daily_sales')
            ->first();

        // Day of week performance
        $dayPerformance = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DAYNAME(tanggal_transaksi) as day_name, COUNT(*) as transactions, SUM(total_harga) as revenue')
            ->groupBy('day_name')
            ->orderByDesc('revenue')
            ->limit(3)
            ->get()
            ->pluck('day_name')
            ->toArray();

        return [
            'period_days' => $days,
            'summary' => [
                'total_transactions' => $summary->total_transactions ?? 0,
                'total_revenue' => (int)($summary->total_revenue ?? 0),
                'avg_transaction_value' => (int)($summary->avg_transaction_value ?? 0),
                'max_transaction' => (int)($summary->max_transaction ?? 0),
                'min_transaction' => (int)($summary->min_transaction ?? 0),
                'revenue_volatility' => (int)($summary->revenue_volatility ?? 0),
                'avg_daily_revenue' => $summary->total_revenue ? (int)($summary->total_revenue / $days) : 0,
                'avg_daily_transactions' => $summary->total_transactions ? round($summary->total_transactions / $days, 1) : 0
            ],
            'trend' => [
                'growth_rate_percentage' => round($revenueGrowth, 2),
                'direction' => $revenueGrowth > 5 ? 'increasing' : ($revenueGrowth < -5 ? 'decreasing' : 'stable'),
                'first_half_revenue' => (int)($firstHalf->revenue ?? 0),
                'second_half_revenue' => (int)($secondHalf->revenue ?? 0)
            ],
            'extremes' => [
                'best_day' => [
                    'date' => $bestDay->date ?? null,
                    'sales' => (int)($bestDay->daily_sales ?? 0)
                ],
                'worst_day' => [
                    'date' => $worstDay->date ?? null,
                    'sales' => (int)($worstDay->daily_sales ?? 0)
                ]
            ],
            'patterns' => [
                'top_performing_days' => $dayPerformance
            ]
        ];
    }

    /**
     * Prepare Stock Data - RINGKASAN (bukan semua produk)
     */
    private function prepareStockData()
    {
        // Critical items - produk yang akan habis dalam 7 hari
        $criticalItems = DB::query()
            ->fromSub(function ($q) {
                $q->from('products')
                    ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
                    ->leftJoin('transactions', function ($join) {
                        $join->on('transaction_items.transaction_id', '=', 'transactions.id')
                            ->where('transactions.status', 'selesai')
                            ->where('transactions.tanggal_transaksi', '>=', Carbon::now()->subDays(90));
                    })
                    ->selectRaw('
                products.id,
                products.nama_produk as name,
                products.kategori as category,
                products.stok as current_stock,
                products.harga_beli as buy_price,
                products.harga_jual as sell_price,
                COALESCE(SUM(transaction_items.jumlah), 0) as sales_90d,
                COALESCE(SUM(transaction_items.jumlah) / 90, 0) as daily_velocity
            ')
                    ->groupBy(
                        'products.id',
                        'products.nama_produk',
                        'products.kategori',
                        'products.stok',
                        'products.harga_beli',
                        'products.harga_jual'
                    );
            }, 't')
            ->whereRaw('t.current_stock < (t.daily_velocity * 7)')
            ->whereRaw('t.daily_velocity > 0.1')
            ->orderByRaw('t.current_stock / t.daily_velocity')
            ->limit(10)
            ->get();


        // Overstock items - stok lebih dari 60 hari
        $overstockItems = DB::query()
            ->fromSub(function ($q) {
                $q->from('products')
                    ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
                    ->leftJoin('transactions', function ($join) {
                        $join->on('transaction_items.transaction_id', '=', 'transactions.id')
                            ->where('transactions.status', 'selesai')
                            ->where('transactions.tanggal_transaksi', '>=', Carbon::now()->subDays(90));
                    })
                    ->selectRaw('
                products.id,
                products.nama_produk as name,
                products.kategori as category,
                products.stok as current_stock,
                COALESCE(SUM(transaction_items.jumlah) / 90, 0) as daily_velocity,
                products.stok * products.harga_beli as locked_capital
            ')
                    ->groupBy(
                        'products.id',
                        'products.nama_produk',
                        'products.kategori',
                        'products.stok',
                        'products.harga_beli'
                    );
            }, 't')
            ->whereRaw('t.current_stock > (t.daily_velocity * 60)')
            ->whereRaw('t.current_stock > 10')
            ->orderByDesc('t.locked_capital')
            ->limit(10)
            ->get();


        // Fast moving - top 10 produk paling laku
        $fastMoving = DB::table('products')
            ->join('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->join('transactions', function ($join) {
                $join->on('transaction_items.transaction_id', '=', 'transactions.id')
                    ->where('transactions.status', '=', 'selesai')
                    ->where('transactions.tanggal_transaksi', '>=', Carbon::now()->subDays(90));
            })
            ->selectRaw('
                products.id,
                products.nama_produk as name,
                products.kategori as category,
                SUM(transaction_items.jumlah) / 90 as daily_velocity
            ')
            ->groupBy('products.id', 'products.nama_produk', 'products.kategori')
            ->orderByDesc('daily_velocity')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'average_daily_sales' => round($item->daily_velocity, 2)
                ];
            })
            ->toArray();

        // Slow moving / dead stock - tidak laku 60 hari
        $slowMoving = DB::table('products')
            ->leftJoin('transaction_items', function ($join) {
                $join->on('products.id', '=', 'transaction_items.product_id')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('transactions')
                            ->whereColumn('transactions.id', 'transaction_items.transaction_id')
                            ->where('transactions.status', 'selesai')
                            ->where('transactions.tanggal_transaksi', '>=', Carbon::now()->subDays(60));
                    });
            })
            ->whereNull('transaction_items.id')
            ->where('products.stok', '>', 0)
            ->select('products.id', 'products.nama_produk as name', 'products.kategori as category', 'products.stok as current_stock')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'current_stock' => $item->current_stock,
                    'days_since_last_sale' => 60
                ];
            })
            ->toArray();

        // Overall summary
        $totalStockValue = Product::sum(DB::raw('stok * harga_beli'));
        $outOfStockCount = Product::where('stok', '=', 0)->count();

        return [
            'summary' => [
                'total_stock_value' => (int)$totalStockValue,
                'critical_items_count' => count($criticalItems),
                'overstock_items_count' => count($overstockItems),
                'out_of_stock_count' => $outOfStockCount
            ],
            'critical_items' => $criticalItems,
            'overstock_items' => $overstockItems,
            'fast_moving' => $fastMoving,
            'slow_moving' => $slowMoving
        ];
    }

    /**
     * Get Trends Data - RINGKASAN
     */
    private function getTrendsData()
    {
        // Category trends dengan growth comparison
        $categoryTrends = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'selesai')
            ->where('transactions.tanggal_transaksi', '>=', Carbon::now()->subDays(90))
            ->selectRaw('
                products.kategori,
                SUM(transaction_items.subtotal) as total_sales,
                COUNT(DISTINCT transaction_items.transaction_id) as transaction_count,
                SUM(transaction_items.jumlah) as units_sold,
                AVG(transaction_items.subtotal) as avg_sale_value
            ')
            ->groupBy('products.kategori')
            ->orderByDesc('total_sales')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->kategori,
                    'total_sales' => (int)$item->total_sales,
                    'transaction_count' => $item->transaction_count,
                    'units_sold' => $item->units_sold,
                    'avg_sale_value' => (int)$item->avg_sale_value
                ];
            })
            ->toArray();

        // Top 5 products (30 hari terakhir)
        $topProducts = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'selesai')
            ->where('transactions.tanggal_transaksi', '>=', Carbon::now()->subDays(30))
            ->selectRaw('
                products.nama_produk,
                products.kategori,
                SUM(transaction_items.jumlah) as qty_sold,
                SUM(transaction_items.subtotal) as revenue
            ')
            ->groupBy('products.id', 'products.nama_produk', 'products.kategori')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->nama_produk,
                    'category' => $item->kategori,
                    'qty_sold' => $item->qty_sold,
                    'revenue' => (int)$item->revenue
                ];
            })
            ->toArray();

        // Declining products - compare 30 hari vs 30 hari sebelumnya
        $decliningProducts = DB::table('products')
            ->selectRaw('
                products.nama_produk,
                products.kategori,
                (SELECT COALESCE(SUM(ti.jumlah), 0)
                 FROM transaction_items ti
                 JOIN transactions t ON ti.transaction_id = t.id
                 WHERE ti.product_id = products.id
                 AND t.status = "selesai"
                 AND t.tanggal_transaksi BETWEEN ? AND ?) as prev_30d_sales,
                (SELECT COALESCE(SUM(ti.jumlah), 0)
                 FROM transaction_items ti
                 JOIN transactions t ON ti.transaction_id = t.id
                 WHERE ti.product_id = products.id
                 AND t.status = "selesai"
                 AND t.tanggal_transaksi >= ?) as last_30d_sales
            ', [
                Carbon::now()->subDays(60),
                Carbon::now()->subDays(30),
                Carbon::now()->subDays(30)
            ])
            ->havingRaw('prev_30d_sales > 5 AND last_30d_sales < prev_30d_sales * 0.6')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $decline = $item->prev_30d_sales > 0
                    ? (($item->prev_30d_sales - $item->last_30d_sales) / $item->prev_30d_sales) * 100
                    : 0;
                return [
                    'product_name' => $item->nama_produk,
                    'category' => $item->kategori,
                    'decline_percentage' => round($decline, 1),
                    'prev_sales' => $item->prev_30d_sales,
                    'current_sales' => $item->last_30d_sales
                ];
            })
            ->toArray();

        // Peak days & hours
        $peakDays = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(90))
            ->selectRaw('DAYNAME(tanggal_transaksi) as day_name, COUNT(*) as transactions')
            ->groupBy('day_name')
            ->orderByDesc('transactions')
            ->limit(3)
            ->pluck('day_name')
            ->toArray();

        $peakHours = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(30))
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as transactions')
            ->groupBy('hour')
            ->orderByDesc('transactions')
            ->limit(3)
            ->pluck('hour')
            ->map(function ($h) {
                return $h . ':00-' . ($h + 1) . ':00';
            })
            ->toArray();

        return [
            'summary' => [
                'total_categories' => count($categoryTrends),
                'analysis_period' => '90 days'
            ],
            'category_trends' => $categoryTrends,
            'top_products' => $topProducts,
            'declining_products' => $decliningProducts,
            'peak_patterns' => [
                'top_days' => $peakDays,
                'top_hours' => $peakHours
            ]
        ];
    }

    /**
     * Get Recent Transactions - RINGKASAN (bukan per hari)
     */
    private function getRecentTransactions($days)
    {
        // Summary statistik, bukan detail per hari
        $summary = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(total_harga) as total_revenue,
                AVG(total_harga) as avg_transaction,
                MAX(total_harga) as max_transaction,
                MIN(total_harga) as min_transaction
            ')
            ->first();

        // Hanya ambil best & worst 3 days
        $bestDays = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DATE(tanggal_transaksi) as date, COUNT(*) as count, SUM(total_harga) as total')
            ->groupBy('date')
            ->orderByDesc('total')
            ->limit(3)
            ->get()
            ->toArray();

        $worstDays = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DATE(tanggal_transaksi) as date, COUNT(*) as count, SUM(total_harga) as total')
            ->groupBy('date')
            ->orderBy('total')
            ->limit(3)
            ->get()
            ->toArray();

        return [
            'period_days' => $days,
            'summary' => [
                'total_transactions' => $summary->total_transactions ?? 0,
                'total_revenue' => (int)($summary->total_revenue ?? 0),
                'avg_transaction' => (int)($summary->avg_transaction ?? 0),
                'max_transaction' => (int)($summary->max_transaction ?? 0),
                'min_transaction' => (int)($summary->min_transaction ?? 0),
                'avg_daily_revenue' => $summary->total_revenue ? (int)($summary->total_revenue / $days) : 0
            ],
            'best_days' => $bestDays,
            'worst_days' => $worstDays
        ];
    }

    /**
     * Get Historical Average
     */
    private function getHistoricalAverage($days)
    {
        $avg = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('
                AVG(total_harga) as avg_transaction,
                COUNT(*)/? as avg_daily_transactions,
                SUM(total_harga)/? as avg_daily_revenue,
                STDDEV(total_harga) as volatility
            ', [$days, $days])
            ->first();

        return [
            'period_days' => $days,
            'avg_transaction_value' => (int)($avg->avg_transaction ?? 0),
            'avg_daily_transactions' => round($avg->avg_daily_transactions ?? 0, 2),
            'avg_daily_revenue' => (int)($avg->avg_daily_revenue ?? 0),
            'volatility' => (int)($avg->volatility ?? 0)
        ];
    }

    /**
     * Get Customer Data - RINGKASAN
     */
    private function getCustomerData()
    {
        // Payment method summary
        $paymentMethods = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(90))
            ->selectRaw('
                metode_pembayaran,
                COUNT(*) as count,
                SUM(total_harga) as total,
                AVG(total_harga) as avg_value
            ')
            ->groupBy('metode_pembayaran')
            ->orderByDesc('count')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => $item->metode_pembayaran,
                    'transaction_count' => $item->count,
                    'total_value' => (int)$item->total,
                    'avg_transaction' => (int)$item->avg_value
                ];
            })
            ->toArray();

        // Peak hours (top 3 saja)
        $peakHours = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(30))
            ->selectRaw('
                HOUR(created_at) as hour,
                COUNT(*) as transactions,
                SUM(total_harga) as revenue
            ')
            ->groupBy('hour')
            ->orderByDesc('transactions')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'time_range' => $item->hour . ':00 - ' . ($item->hour + 1) . ':00',
                    'transactions' => $item->transactions,
                    'revenue' => (int)$item->revenue
                ];
            })
            ->toArray();

        // Customer frequency (estimate)
        $totalCustomers = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(90))
            ->distinct('kasir_id')
            ->count();

        $totalTransactions = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(90))
            ->count();

        return [
            'summary' => [
                'total_unique_customers_90d' => $totalCustomers,
                'total_transactions_90d' => $totalTransactions,
                'avg_visits_per_customer' => $totalCustomers > 0 ? round($totalTransactions / $totalCustomers, 1) : 0
            ],
            'payment_methods' => $paymentMethods,
            'peak_hours' => $peakHours
        ];
    }

    /**
     * Check if string is valid JSON
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
