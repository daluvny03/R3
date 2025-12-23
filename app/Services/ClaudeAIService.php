<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
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
        // Ambil data penjualan historis
        $salesData = $this->getSalesHistory($period * 3); // 3x periode untuk training data

        $prompt = $this->buildSalesPredictionPrompt($salesData, $period);

        return $this->callClaudeAPI($prompt);
    }

    /**
     * Rekomendasi Stok Optimal
     */
    public function recommendStock()
    {
        // Ambil data produk dan penjualan
        $products = Product::with(['transactionItems' => function ($query) {
            $query->whereHas('transaction', function ($q) {
                $q->where('status', 'selesai')
                    ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(90));
            });
        }])->get();

        $stockData = $this->prepareStockData($products);

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

DATA PENJUALAN HISTORIS (JSON):
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

DATA STOK & PENJUALAN PRODUK (JSON):
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
- Data berasal dari sistem POS
- Trend digunakan untuk keputusan mingguan & bulanan
- Output harus ringkas dan mudah dipahami

DATA HISTORIS PENJUALAN & KATEGORI (JSON):
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

DATA TRANSAKSI 30 HARI TERAKHIR:
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
    "summary_for_owner": "Ringkasan singkat 2–3 kalimat untuk owner"
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

DATA PELANGGAN & TRANSAKSI:
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
            // Check if API key is configured
            if (empty($this->apiKey)) {
                return [
                    'error' => 'ANTHROPIC_API_KEY not configured in .env file. Please add your API key.'
                ];
            }

            $response = Http::timeout(60)->withHeaders([
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
     * Get Sales History
     */
    private function getSalesHistory($days)
    {
        $transactions = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DATE(tanggal_transaksi) as date, COUNT(*) as transaction_count, SUM(total_harga) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $transactions->map(function ($item) {
            return [
                'date' => $item->date,
                'transactions' => $item->transaction_count,
                'sales' => (int)$item->total_sales
            ];
        })->toArray();
    }

    /**
     * Prepare Stock Data
     */
    private function prepareStockData($products)
    {
        return $products->map(function ($product) {
            $sales90Days = $product->transactionItems->sum('jumlah');
            $salesVelocity = $sales90Days / 90; // units per day

            $daysUntilStockout = $salesVelocity > 0
                ? round($product->stok / $salesVelocity)
                : 999;

            return [
                'id' => $product->id,
                'name' => $product->nama_produk,
                'category' => $product->kategori,
                'current_stock' => $product->stok,
                'sales_last_90_days' => $sales90Days,
                'sales_velocity_per_day' => round($salesVelocity, 2),
                'days_until_stockout' => $daysUntilStockout,
                'buy_price' => $product->harga_beli,
                'sell_price' => $product->harga_jual,
                'profit_margin' => round((($product->harga_jual - $product->harga_beli) / $product->harga_jual) * 100, 2)
            ];
        })->toArray();
    }

    /**
     * Get Trends Data
     */
    private function getTrendsData()
    {
        // Sales by category
        $categoryTrends = TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'selesai')
            ->where('transactions.tanggal_transaksi', '>=', Carbon::now()->subDays(90))
            ->selectRaw('products.kategori, SUM(transaction_items.subtotal) as total_sales, COUNT(*) as items_sold')
            ->groupBy('products.kategori')
            ->get();

        // Top products
        $topProducts = TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'selesai')
            ->where('transactions.tanggal_transaksi', '>=', Carbon::now()->subDays(30))
            ->selectRaw('products.nama_produk, SUM(transaction_items.jumlah) as qty_sold, SUM(transaction_items.subtotal) as revenue')
            ->groupBy('products.id', 'products.nama_produk')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return [
            'category_trends' => $categoryTrends->toArray(),
            'top_products' => $topProducts->toArray(),
            'period' => '90 days'
        ];
    }

    /**
     * Get Recent Transactions
     */
    private function getRecentTransactions($days)
    {
        return Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('DATE(tanggal_transaksi) as date, COUNT(*) as count, SUM(total_harga) as total')
            ->groupBy('date')
            ->get()
            ->toArray();
    }

    /**
     * Get Historical Average
     */
    private function getHistoricalAverage($days)
    {
        $avg = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays($days))
            ->selectRaw('AVG(total_harga) as avg_transaction, COUNT(*)/? as avg_daily_transactions', [$days])
            ->first();

        return [
            'avg_transaction_value' => (int)$avg->avg_transaction,
            'avg_daily_transactions' => round($avg->avg_daily_transactions, 2)
        ];
    }

    /**
     * Get Customer Data
     */
    private function getCustomerData()
    {
        // Payment method distribution
        $paymentMethods = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(90))
            ->selectRaw('metode_pembayaran, COUNT(*) as count, SUM(total_harga) as total')
            ->groupBy('metode_pembayaran')
            ->get();

        // Transaction time patterns
        $hourlyPattern = Transaction::where('status', 'selesai')
            ->where('tanggal_transaksi', '>=', Carbon::now()->subDays(30))
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as transactions')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return [
            'payment_methods' => $paymentMethods->toArray(),
            'hourly_patterns' => $hourlyPattern->toArray(),
            'total_customers_90d' => Transaction::where('tanggal_transaksi', '>=', Carbon::now()->subDays(90))->distinct('kasir_id')->count()
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
