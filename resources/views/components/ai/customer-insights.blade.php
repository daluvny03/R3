<template x-if="analysisType === 'customer' && results && !results.error">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

        <!-- HEADER -->
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-8 h-8 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                         c0-.656-.126-1.283-.356-1.857M7 20H2v-2
                         a3 3 0 015.356-1.857M7 20v-2
                         c0-.656.126-1.283.356-1.857m0 0
                         a5.002 5.002 0 019.288 0M15 7
                         a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Customer Insights
        </h3>

        <!-- SUMMARY -->
        <div class="mb-8">
            <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-lg">
                <h4 class="font-bold text-lg text-gray-800 mb-2">Ringkasan</h4>
                <p class="text-sm text-gray-700" x-text="results.summary"></p>
            </div>
        </div>

        <!-- CUSTOMER SEGMENTS -->
        <div x-show="results.key_segments?.length" class="mb-10">
            <h4 class="font-bold text-lg mb-4">👥 Segmentasi Pelanggan</h4>

            <div class="grid md:grid-cols-2 gap-4">
                <template x-for="segment in results.key_segments" :key="segment.segment_name">
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
                        <div class="flex justify-between mb-3">
                            <h5 class="font-bold text-gray-800"
                                x-text="segment.segment_name"></h5>
                            <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold"
                                  x-text="segment.percentage + '%'"></span>
                        </div>

                        <p class="text-sm text-gray-700 mb-3"
                           x-text="segment.characteristics"></p>

                        <div class="bg-white p-3 rounded mb-3">
                            <p class="text-xs text-gray-500">Rata-rata Transaksi</p>
                            <p class="text-lg font-bold text-orange-600"
                               x-text="formatRupiah(segment.average_transaction)"></p>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                            <p class="text-xs font-semibold mb-1">Strategi</p>
                            <p class="text-sm text-gray-700"
                               x-text="segment.recommended_strategy"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- PURCHASE BEHAVIOR -->
        <div x-show="results.purchase_behavior" class="mb-10">
            <h4 class="font-bold text-lg mb-4">🛒 Perilaku Pembelian</h4>

            <div class="bg-gray-50 border rounded-xl p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Average Basket Size</p>
                    <p class="text-3xl font-bold text-blue-600"
                       x-text="formatRupiah(results.purchase_behavior.average_basket_size)">
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        Repeat Purchase Rate:
                        <strong x-text="results.purchase_behavior.repeat_purchase_rate"></strong>
                    </p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">
                        Kombinasi Produk Umum
                    </p>
                    <template x-for="(combo, index) in results.purchase_behavior.common_product_combinations"
          :key="index">

                        <div class="bg-white p-3 rounded mb-2 text-sm">
                            <span x-text="Array.isArray(combo) ? combo.join(' + ') : combo"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- CUSTOMER VALUE -->
        <div x-show="results.customer_value" class="mb-10">
            <h4 class="font-bold text-lg mb-4">💰 Nilai Pelanggan</h4>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div class="bg-green-50 p-5 rounded-xl border">
                    <p class="text-sm text-gray-600">Average CLV</p>
                    <p class="text-3xl font-bold text-green-600"
                       x-text="formatRupiah(results.customer_value.average_clv)">
                    </p>
                </div>

                <div class="bg-purple-50 p-5 rounded-xl border">
                    <p class="text-sm text-gray-600">Top Customer CLV</p>
                    <p class="text-3xl font-bold text-purple-600"
                       x-text="formatRupiah(results.customer_value.top_customer_clv)">
                    </p>
                </div>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <p class="text-sm font-semibold mb-1">Rekomendasi Fokus</p>
                <p class="text-sm text-gray-700"
                   x-text="results.customer_value.focus_recommendation"></p>
            </div>
        </div>

        <!-- CHURN RISK -->
        <div x-show="results.churn_risk?.length" class="mb-10">
            <h4 class="font-bold text-lg mb-4">⚠️ Risiko Churn</h4>

            <div class="space-y-3">
                <template x-for="risk in results.churn_risk" :key="risk.customer_group">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <h5 class="font-bold text-gray-800"
                            x-text="risk.customer_group"></h5>
                        <p class="text-sm text-gray-600">
                            Jumlah: <strong x-text="risk.estimated_count"></strong>
                        </p>
                        <p class="text-sm text-gray-600">
                            Aktivitas Terakhir:
                            <span x-text="risk.last_activity"></span>
                        </p>
                        <p class="text-sm mt-2">
                            <strong>Win-back:</strong>
                            <span x-text="risk.win_back_action"></span>
                        </p>
                    </div>
                </template>
            </div>
        </div>

        <!-- PREFERENCES -->
        <div x-show="results.preferences" class="mb-10">
            <h4 class="font-bold text-lg mb-4">🎯 Preferensi Pelanggan</h4>

            <div class="bg-gray-50 rounded-xl p-6 grid md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm font-semibold mb-2">Metode Pembayaran</p>
                    <template x-for="m in results.preferences.preferred_payment_methods" :key="m">
                        <span class="inline-block bg-white px-3 py-1 rounded text-sm mr-1 mb-1"
                              x-text="m"></span>
                    </template>
                </div>

                <div>
                    <p class="text-sm font-semibold mb-2">Waktu Belanja</p>
                    <p class="text-sm bg-white p-2 rounded"
                       x-text="results.preferences.peak_shopping_time"></p>
                </div>

                <div>
                    <p class="text-sm font-semibold mb-2">Kategori Favorit</p>
                    <template x-for="c in results.preferences.preferred_categories" :key="c">
                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-1"
                              x-text="c"></span>
                    </template>
                </div>
            </div>
        </div>

        <!-- PRIORITY ACTIONS -->
        <div x-show="results.priority_actions?.length">
            <h4 class="font-bold text-lg mb-4">🎯 Priority Actions</h4>

            <div class="space-y-3">
                <template x-for="act in results.priority_actions" :key="act.action">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <p class="font-semibold capitalize"
                           x-text="act.priority"></p>
                        <p class="text-sm mt-1"
                           x-text="act.action"></p>
                        <p class="text-xs text-gray-600 mt-1"
                           x-text="act.expected_impact"></p>
                    </div>
                </template>
            </div>
        </div>

    </div>
</template>
