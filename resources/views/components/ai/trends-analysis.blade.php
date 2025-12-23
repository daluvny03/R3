<template x-if="analysisType === 'trends' && results && !results.error">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

        <!-- HEADER -->
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            Analisis Tren Bisnis
        </h3>

        <!-- BUSINESS TREND -->
        <div class="mb-8">
            <div class="bg-purple-50 border-l-4 border-purple-500 p-5 rounded-lg">
                <h4 class="font-bold text-lg text-gray-800 mb-1">
                    Tren Bisnis:
                    <span class="capitalize" x-text="results.business_trend.direction"></span>
                </h4>
                <p class="text-sm text-gray-700 mb-2"
                   x-text="results.business_trend.summary"></p>
                <p class="text-sm">
                    Growth Bulanan:
                    <strong class="text-purple-600"
                            x-text="results.business_trend.monthly_growth_rate + '%'"></strong>
                </p>
            </div>
        </div>

        <!-- CATEGORY SIGNALS -->
        <div x-show="results.category_signals?.length" class="mb-10">
            <h4 class="font-bold text-lg mb-4">📊 Sinyal per Kategori</h4>

            <div class="grid md:grid-cols-2 gap-4">
                <template x-for="cat in results.category_signals" :key="cat.category">
                    <div class="bg-white border rounded-xl p-4">
                        <div class="flex justify-between mb-2">
                            <h5 class="font-bold text-gray-800" x-text="cat.category"></h5>
                            <span class="px-3 py-1 rounded-full text-xs font-bold capitalize"
                                  :class="cat.signal === 'positive'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-700'"
                                  x-text="cat.signal">
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Growth:
                            <strong x-text="cat.growth_rate + '%'"></strong>
                        </p>
                        <p class="text-sm text-gray-700 mt-1"
                           x-text="cat.implication"></p>
                    </div>
                </template>
            </div>
        </div>

        <!-- PRODUCT SIGNALS -->
        <div class="mb-10">
            <h4 class="font-bold text-lg mb-4">📦 Sinyal Produk</h4>

            <!-- RISING -->
            <div x-show="results.product_signals?.rising?.length" class="mb-6">
                <h5 class="font-bold text-green-600 mb-3">⬆ Produk Naik</h5>
                <div class="space-y-3">
                    <template x-for="item in results.product_signals.rising" :key="item.product_name">
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                            <h6 class="font-bold text-gray-800" x-text="item.product_name"></h6>
                            <p class="text-sm">
                                Growth:
                                <strong class="text-green-600"
                                        x-text="item.growth_rate + '%'"></strong>
                            </p>
                            <p class="text-xs text-gray-600 mt-1"
                               x-text="item.note"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- DECLINING -->
            <div x-show="results.product_signals?.declining?.length">
                <h5 class="font-bold text-red-600 mb-3">⬇ Produk Menurun</h5>
                <div class="space-y-3">
                    <template x-for="item in results.product_signals.declining" :key="item.product_name">
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                            <h6 class="font-bold text-gray-800" x-text="item.product_name"></h6>
                            <p class="text-sm">
                                Penurunan:
                                <strong class="text-red-600"
                                        x-text="item.decline_rate + '%'"></strong>
                            </p>
                            <p class="text-xs text-gray-600 mt-1"
                               x-text="item.risk"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- PEAK PATTERNS -->
        <div class="mb-10">
            <h4 class="font-bold text-lg mb-4">⏰ Pola Puncak Penjualan</h4>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h5 class="font-bold text-gray-800 mb-2">Hari Terbaik</h5>
                    <template x-for="day in results.peak_patterns.top_days" :key="day">
                        <span class="inline-block bg-blue-200 text-blue-800 px-2 py-1 rounded text-xs mr-1 mb-1"
                              x-text="day"></span>
                    </template>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <h5 class="font-bold text-gray-800 mb-2">Jam Terbaik</h5>
                    <template x-for="hour in results.peak_patterns.top_hours" :key="hour">
                        <span class="inline-block bg-blue-200 text-blue-800 px-2 py-1 rounded text-xs mr-1 mb-1"
                              x-text="hour"></span>
                    </template>
                </div>
            </div>

            <p class="text-sm text-gray-600 mt-3"
               x-text="results.peak_patterns.seasonal_note"></p>
        </div>

        <!-- OPPORTUNITIES -->
        <div x-show="results.opportunities?.length" class="mb-10">
            <h4 class="font-bold text-lg mb-4">🚀 Peluang Bisnis</h4>
            <ul class="space-y-2">
                <template x-for="opp in results.opportunities" :key="opp.description">
                    <li class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                        <strong class="capitalize" x-text="opp.type"></strong> —
                        <span x-text="opp.description"></span>
                    </li>
                </template>
            </ul>
        </div>

        <!-- THREATS -->
        <div x-show="results.threats?.length">
            <h4 class="font-bold text-lg mb-4">⚠️ Ancaman</h4>
            <ul class="space-y-2">
                <template x-for="threat in results.threats" :key="threat">
                    <li class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500"
                        x-text="threat"></li>
                </template>
            </ul>
        </div>

    </div>
</template>
