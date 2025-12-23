<template x-if="analysisType === 'stock_recommendation' && results && !results.error">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

        <!-- HEADER -->
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Analisa Stok & Rekomendasi AI
        </h3>

        <!-- STOCK HEALTH -->
        <div class="mb-8">
            <div class="p-4 rounded-lg border-l-4"
                 :class="results.stock_health.status === 'warning'
                    ? 'bg-yellow-50 border-yellow-500'
                    : 'bg-green-50 border-green-500'">
                <h4 class="font-bold text-lg text-gray-800 mb-1">
                    Status Stok: <span class="uppercase" x-text="results.stock_health.status"></span>
                </h4>
                <p class="text-sm text-gray-700" x-text="results.stock_health.summary"></p>
            </div>
        </div>

        <!-- PRIORITY RESTOCK -->
        <div x-show="results.priority_restock?.length" class="mb-10">
            <h4 class="font-bold text-lg text-red-600 mb-4">
                🚨 Rekomendasi Restok Prioritas
            </h4>

            <div class="space-y-4">
                <template x-for="item in results.priority_restock" :key="item.product_id">
                    <div class="p-4 rounded-lg border-l-4"
                         :class="item.urgency === 'high'
                            ? 'bg-red-50 border-red-500'
                            : 'bg-yellow-50 border-yellow-500'">

                        <div class="flex justify-between items-center mb-2">
                            <h5 class="font-bold text-gray-800" x-text="item.product_name"></h5>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase text-white"
                                  :class="item.urgency === 'high' ? 'bg-red-500' : 'bg-yellow-500'"
                                  x-text="item.urgency">
                            </span>
                        </div>

                        <p class="text-sm text-gray-700 mb-3" x-text="item.reason"></p>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                            <div class="bg-white p-2 rounded">
                                <span class="block text-xs text-gray-500">Stok Saat Ini</span>
                                <strong x-text="item.current_stock"></strong>
                            </div>

                            <div class="bg-white p-2 rounded">
                                <span class="block text-xs text-gray-500">Order Disarankan</span>
                                <strong class="text-green-600"
                                        x-text="item.recommended_order_quantity"></strong>
                            </div>

                            <div class="bg-white p-2 rounded">
                                <span class="block text-xs text-gray-500">Habis Dalam</span>
                                <strong class="text-red-600"
                                        x-text="item.estimated_days_until_stockout + ' hari'"></strong>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- OVERSTOCK RISKS -->
        <div x-show="results.overstock_risks?.length" class="mb-10">
            <h4 class="font-bold text-lg text-orange-600 mb-4">
                ⚠️ Risiko Overstock
            </h4>

            <div class="space-y-3">
                <template x-for="item in results.overstock_risks" :key="item.product_id">
                    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-lg">
                        <h5 class="font-bold text-gray-800 mb-1" x-text="item.product_name"></h5>
                        <p class="text-sm text-gray-700 mb-1" x-text="item.risk"></p>
                        <p class="text-sm">
                            <strong class="text-orange-600">Rekomendasi:</strong>
                            <span x-text="item.recommended_action"></span>
                        </p>
                    </div>
                </template>
            </div>
        </div>

        <!-- FAST MOVING PRODUCTS -->
        <div x-show="results.fast_moving_products?.length" class="mb-10">
            <h4 class="font-bold text-lg text-green-600 mb-4">
                ⚡ Produk Fast Moving
            </h4>

            <div class="grid md:grid-cols-2 gap-4">
                <template x-for="item in results.fast_moving_products" :key="item.product_id">
                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                        <h5 class="font-bold text-gray-800" x-text="item.product_name"></h5>
                        <p class="text-sm text-gray-700">
                            Avg Sales: <strong x-text="item.average_daily_sales + '/hari'"></strong>
                        </p>
                        <p class="text-xs text-gray-600 mt-1" x-text="item.note"></p>
                    </div>
                </template>
            </div>
        </div>

        <!-- BUDGET GUIDANCE -->
        <div x-show="results.budget_guidance" class="bg-blue-50 border-2 border-blue-200 p-6 rounded-xl">
            <h4 class="font-bold text-lg text-blue-800 mb-4">
                💰 Panduan Budget Restok
            </h4>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Estimasi Total Biaya</p>
                    <p class="text-2xl font-bold text-blue-600"
                       x-text="formatRupiah(results.budget_guidance.estimated_restock_cost)">
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-2">Wajib Dibeli Hari Ini</p>
                    <template x-for="item in results.budget_guidance.must_buy_today" :key="item">
                        <span class="inline-block bg-blue-200 text-blue-800 px-2 py-1 rounded text-xs mr-1 mb-1"
                              x-text="item"></span>
                    </template>
                </div>
            </div>
        </div>

    </div>
</template>
