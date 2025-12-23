<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Pilih Jenis Analisis</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Sales Prediction -->
        <x-ai.analysis-card type="sales_prediction" color="blue" icon="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
            title="Prediksi Penjualan" description="Forecasting penjualan 7-30 hari ke depan" />

        <!-- Stock Recommendation -->
        <x-ai.analysis-card type="stock_recommendation" color="green"
            icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" title="Rekomendasi Stok"
            description="Optimal inventory management" />

        <!-- Trends Analysis -->
        <x-ai.analysis-card type="trends" color="purple"
            icon="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" title="Analisis Tren"
            description="Identifikasi pola & peluang" />

        <!-- Anomaly Detection -->
        <x-ai.analysis-card type="anomaly" color="red"
            icon="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
            title="Deteksi Anomali" description="Identifikasi transaksi tidak biasa" />

        <!-- Customer Insights -->
        <x-ai.analysis-card type="customer" color="orange"
            icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
            title="Customer Insights" description="Analisis perilaku pelanggan" />

        <!-- Comprehensive Analysis -->
        <x-ai.analysis-card type="comprehensive" color="indigo"
            icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
            title="Analisis Komprehensif" description="Semua analisis dalam satu laporan" />
    </div>

    <!-- Run Button -->
    <div class="m-6 flex justify-end">
        <a href="{{ route('owner.dashboard') }}"
            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Kembali
        </a>
        <button @click="runAnalysis()" :disabled="loading || !analysisType"
            class="px-8 mx-3 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all flex items-center space-x-2">
            <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span x-text="loading ? 'Analyzing...' : 'Run AI Analysis'"></span>
        </button>
    </div>
</div>
