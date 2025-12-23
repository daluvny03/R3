<template x-if="analysisType === 'comprehensive' && results && !results.error">
    <div
    x-data="{ activeTab: 'sales' }"
    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

        <!-- Header -->
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-8 h-8 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Analisis Komprehensif
        </h3>

        <!-- Executive Summary -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl p-6 mb-8">
            <p class="text-sm text-indigo-100 mb-1">
                Generated: <span x-text="results.generated_at"></span>
            </p>
            <p class="text-lg font-semibold">
                Ringkasan performa bisnis, stok, tren, anomali, dan pelanggan.
            </p>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-xl shadow p-4 mb-6">
            <div class="flex flex-wrap gap-2">
                <button @click="activeTab='sales'" :class="activeTab==='sales' ? 'bg-blue-500 text-white' : 'bg-gray-100'"
                        class="px-4 py-2 rounded font-semibold">📈 Sales</button>
                <button @click="activeTab='stock'" :class="activeTab==='stock' ? 'bg-green-500 text-white' : 'bg-gray-100'"
                        class="px-4 py-2 rounded font-semibold">📦 Stock</button>
                <button @click="activeTab='trends'" :class="activeTab==='trends' ? 'bg-purple-500 text-white' : 'bg-gray-100'"
                        class="px-4 py-2 rounded font-semibold">📊 Trends</button>
                <button @click="activeTab='anomalies'" :class="activeTab==='anomalies' ? 'bg-red-500 text-white' : 'bg-gray-100'"
                        class="px-4 py-2 rounded font-semibold">🚨 Anomalies</button>
                <button @click="activeTab='customers'" :class="activeTab==='customers' ? 'bg-orange-500 text-white' : 'bg-gray-100'"
                        class="px-4 py-2 rounded font-semibold">👥 Customers</button>
            </div>
        </div>

        <!-- SALES -->
        <div x-show="activeTab==='sales'" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded">
                    <p class="text-sm">Estimasi Hari Ini</p>
                    <p class="text-xl font-bold text-blue-600"
                       x-text="formatRupiah(results.sales_prediction?.today_health?.estimated_sales_today)">
                    </p>
                    <p class="text-xs text-gray-600"
                       x-text="results.sales_prediction?.today_health?.estimated_transactions_today + ' transaksi'">
                    </p>
                </div>

                <div class="bg-green-50 p-4 rounded">
                    <p class="text-sm">Forecast 7 Hari</p>
                    <p class="text-xl font-bold text-green-600"
                       x-text="formatRupiah(results.sales_prediction?.next_7_days_forecast?.total_sales)">
                    </p>
                    <p class="text-xs text-gray-600"
                       x-text="results.sales_prediction?.next_7_days_forecast?.confidence">
                    </p>
                </div>
            </div>

            <div>
                <p class="font-semibold mb-2">Key Insights</p>
                <ul class="list-disc list-inside text-sm text-gray-700">
                    <template x-for="(insight, i) in results.sales_prediction?.key_insights || []"
                              :key="i">
                        <li x-text="insight"></li>
                    </template>
                </ul>
            </div>
        </div>

        <!-- STOCK -->
        <div x-show="activeTab==='stock'" class="space-y-4">
            <div class="bg-yellow-50 p-4 rounded">
                <p class="font-semibold">Stock Health</p>
                <p x-text="results.stock_recommendation?.stock_health?.summary"></p>
            </div>

            <div>
                <p class="font-semibold mb-2">Priority Restock</p>
                <ul class="space-y-2">
                    <template x-for="(item, i) in results.stock_recommendation?.priority_restock || []"
                              :key="item.product_id">
                        <li class="bg-red-50 p-3 rounded">
                            <p class="font-semibold" x-text="item.product_name"></p>
                            <p class="text-sm text-gray-700"
                               x-text="'Order ' + item.recommended_order_quantity + ' unit — stok habis ' + item.estimated_days_until_stockout + ' hari'">
                            </p>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <!-- TRENDS -->
        <div x-show="activeTab==='trends'" class="space-y-4">
            <div class="bg-purple-50 p-4 rounded">
                <p class="font-semibold capitalize"
                   x-text="results.trends?.business_trend?.direction">
                </p>
                <p class="text-sm text-gray-700"
                   x-text="results.trends?.business_trend?.summary">
                </p>
            </div>

            <div>
                <p class="font-semibold mb-2">Category Signals</p>
                <ul class="space-y-2">
                    <template x-for="(cat, i) in results.trends?.category_signals || []"
                              :key="i">
                        <li class="bg-gray-50 p-3 rounded">
                            <p class="font-semibold" x-text="cat.category"></p>
                            <p class="text-sm" x-text="cat.implication"></p>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <!-- ANOMALIES -->
        <div x-show="activeTab==='anomalies'" class="space-y-4">
            <div class="bg-red-50 p-4 rounded">
                <p class="font-semibold">Summary</p>
                <p x-text="results.anomalies?.summary_for_owner"></p>
            </div>

            <ul class="space-y-2">
                <template x-for="(alert, i) in results.anomalies?.owner_alerts || []"
                          :key="i">
                    <li class="bg-orange-50 p-3 rounded">
                        <p class="font-semibold capitalize" x-text="alert.priority"></p>
                        <p x-text="alert.message"></p>
                    </li>
                </template>
            </ul>
        </div>

        <!-- CUSTOMERS -->
        <div x-show="activeTab==='customers'" class="space-y-4">
            <div class="bg-orange-50 p-4 rounded">
                <p class="font-semibold">Customer Summary</p>
                <p x-text="results.customer_insights?.summary"></p>
            </div>

            <ul class="space-y-2">
                <template x-for="(seg, i) in results.customer_insights?.key_segments || []"
                          :key="i">
                    <li class="bg-white border p-3 rounded">
                        <p class="font-semibold" x-text="seg.segment_name"></p>
                        <p class="text-sm"
                           x-text="seg.percentage + '% — ' + formatRupiah(seg.average_transaction)">
                        </p>
                    </li>
                </template>
            </ul>
        </div>

    </div>
</template>
