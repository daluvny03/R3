<template x-if="analysisType === 'anomaly' && results && !results.error">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Deteksi Anomali
        </h3>

        <!-- Status -->
        <div class="mb-8">
            <div :class="results.anomalies_detected ? 'bg-red-50 border-red-500' : 'bg-green-50 border-green-500'"
                 class="border-l-4 p-6 rounded-r-lg">
                <h4 class="font-bold text-lg"
                    :class="results.anomalies_detected ? 'text-red-800' : 'text-green-800'"
                    x-text="results.anomalies_detected ? '⚠️ Anomali Terdeteksi' : '✓ Tidak Ada Anomali'">
                </h4>
                <p class="text-sm"
                   :class="results.anomalies_detected ? 'text-red-700' : 'text-green-700'"
                   x-text="results.anomalies_detected ? results.summary_for_owner : 'Semua data berada dalam batas normal'">
                </p>
            </div>
        </div>

        <!-- Critical Anomalies -->
        <div x-show="results.critical_anomalies?.length" class="mb-8">
            <h4 class="font-bold text-lg mb-4">🚨 Anomali Kritis</h4>

            <div class="space-y-4">
                <template x-for="(anomaly, index) in results.critical_anomalies"
                          :key="anomaly.date + '-' + index">
                    <div class="bg-red-50 border-2 border-red-500 rounded-xl p-6">
                        <div class="flex justify-between mb-4">
                            <div>
                                <h5 class="font-bold text-gray-800" x-text="anomaly.date"></h5>
                                <span class="text-xs font-bold uppercase text-white px-3 py-1 rounded-full"
                                      :class="{
                                          'bg-red-600': anomaly.severity === 'high',
                                          'bg-orange-500': anomaly.severity === 'medium',
                                          'bg-yellow-500': anomaly.severity === 'low'
                                      }"
                                      x-text="anomaly.severity">
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Tipe</p>
                                <p class="font-semibold capitalize" x-text="anomaly.type"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 bg-white p-4 rounded-lg mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Actual Value</p>
                                <p class="text-xl font-bold text-red-600"
                                   x-text="formatRupiah(anomaly.actual_value)">
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Expected Range</p>
                                <p class="text-sm font-semibold text-gray-700"
                                   x-text="anomaly.expected_range">
                                </p>
                            </div>
                        </div>

                        <div class="mb-3" x-show="anomaly.possible_causes?.length">
                            <p class="font-semibold mb-1">Possible Causes</p>
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                <template x-for="(cause, cIndex) in anomaly.possible_causes"
                                          :key="cIndex">
                                    <li x-text="cause"></li>
                                </template>
                            </ul>
                        </div>

                        <div x-show="anomaly.recommended_actions?.length">
                            <p class="font-semibold mb-1">Recommended Actions</p>
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                <template x-for="(action, aIndex) in anomaly.recommended_actions"
                                          :key="aIndex">
                                    <li x-text="action"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Positive Anomalies -->
        <div x-show="results.positive_anomalies?.length" class="mb-8">
            <h4 class="font-bold text-lg mb-4">⭐ Anomali Positif</h4>

            <div class="space-y-3">
                <template x-for="(anomaly, index) in results.positive_anomalies"
                          :key="index">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                        <p class="font-semibold" x-text="anomaly.date"></p>
                        <p x-text="anomaly.description"></p>
                        <p class="text-sm italic text-gray-600"
                           x-text="anomaly.what_to_repeat">
                        </p>
                    </div>
                </template>
            </div>
        </div>

        <!-- Negative Anomalies -->
        <div x-show="results.negative_anomalies?.length" class="mb-8">
            <h4 class="font-bold text-lg mb-4">📉 Anomali Negatif</h4>

            <div class="space-y-3">
                <template x-for="(anomaly, index) in results.negative_anomalies"
                          :key="index">
                    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded">
                        <p x-text="anomaly.description"></p>
                        <p x-show="anomaly.action_required"
                           class="text-sm font-semibold text-red-700 mt-2">
                            ⚠️ Perlu Tindakan
                        </p>
                    </div>
                </template>
            </div>
        </div>

        <!-- Owner Alerts -->
        <div x-show="results.owner_alerts?.length">
            <h4 class="font-bold text-lg mb-4">📢 Owner Alerts</h4>

            <ul class="space-y-2">
                <template x-for="(alert, index) in results.owner_alerts"
                          :key="index">
                    <li class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="font-semibold capitalize" x-text="alert.priority"></p>
                        <p x-text="alert.message"></p>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>
