<div x-show="results.risk_factors && results.risk_factors.length > 0">
    <h4 class="font-bold text-lg mb-3 flex items-center">
        <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        Risk Factors
    </h4>
    <ul class="space-y-2">
        <template x-for="(risk, index) in results.risk_factors" :key="index">
            <li class="flex items-start bg-red-50 p-3 rounded-lg">
                <span class="text-red-500 mr-3 font-bold">⚠️</span>
                <span x-text="risk" class="text-gray-700"></span>
            </li>
        </template>
    </ul>
</div>