<template x-if="analysisType === 'sales_prediction' && results">
  <div>

    <!-- HEADER -->
    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
      <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
      </svg>
      Prediksi Penjualan
    </h3>

    <!-- KPI -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

      <!-- 7 HARI -->
      <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
        <p class="text-sm text-gray-600 mb-2">Prediksi 7 Hari</p>
        <p class="text-3xl font-bold text-blue-600"
           x-text="formatRupiah(results.next_7_days_forecast?.total_sales || 0)">
        </p>
        <span class="text-xs bg-blue-200 px-2 py-1 rounded-full"
              x-text="'Confidence: ' + (results.next_7_days_forecast?.confidence || 'medium')">
        </span>
      </div>

      <!-- HARI INI -->
      <div class="bg-green-50 p-6 rounded-xl border border-green-200">
        <p class="text-sm text-gray-600 mb-2">Estimasi Hari Ini</p>
        <p class="text-3xl font-bold text-green-600"
           x-text="formatRupiah(results.today_health?.estimated_sales_today || 0)">
        </p>
        <p class="text-sm text-gray-600 mt-1"
           x-text="'Transaksi: ' + (results.today_health?.estimated_transactions_today || 0)">
        </p>
      </div>

      <!-- GROWTH -->
      <div class="bg-purple-50 p-6 rounded-xl border border-purple-200">
        <p class="text-sm text-gray-600 mb-2">Growth Trend</p>
        <p class="text-3xl font-bold capitalize text-purple-600"
           x-text="results.growth_trend || 'stable'">
        </p>
      </div>

    </div>

    <!-- KEY INSIGHTS -->
    <div class="mb-8" x-show="results.key_insights?.length">
      <h4 class="font-bold text-lg mb-3">Key Insights</h4>
      <ul class="space-y-2">
        <template x-for="(insight, i) in results.key_insights" :key="i">
          <li class="bg-yellow-50 p-3 rounded-lg text-gray-700">
            • <span x-text="insight"></span>
          </li>
        </template>
      </ul>
    </div>

    <!-- PRIORITY ACTIONS -->
    <div class="mb-8" x-show="results.priority_actions?.length">
      <h4 class="font-bold text-lg mb-3">Rekomendasi Aksi</h4>

      <template x-for="(item, i) in results.priority_actions" :key="i">
        <div class="p-4 mb-3 rounded-lg border-l-4"
             :class="{
               'border-red-500 bg-red-50': item.priority === 'high',
               'border-yellow-500 bg-yellow-50': item.priority === 'medium',
               'border-green-500 bg-green-50': item.priority === 'low'
             }">
          <p class="font-semibold" x-text="item.action"></p>
          <p class="text-sm text-gray-600" x-text="item.reason"></p>
        </div>
      </template>
    </div>

    <!-- RISKS -->
    <div x-show="results.risks_to_watch?.length">
      <h4 class="font-bold text-lg mb-3">Risiko yang Perlu Diwaspadai</h4>
      <ul class="space-y-2">
        <template x-for="(risk, i) in results.risks_to_watch" :key="i">
          <li class="bg-red-50 p-3 rounded-lg text-gray-700">
            ⚠ <span x-text="risk"></span>
          </li>
        </template>
      </ul>
    </div>

  </div>
</template>
