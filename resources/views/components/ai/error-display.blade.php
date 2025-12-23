<template x-if="results && results.error">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
            <div class="flex items-center mb-2">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-bold text-red-800">Error</h3>
            </div>
            <p class="text-red-700" x-text="results.error"></p>
            <div class="mt-4 text-sm text-red-600">
                <p>Possible causes:</p>
                <ul class="list-disc list-inside mt-2">
                    <li>ANTHROPIC_API_KEY not configured in .env</li>
                    <li>API rate limit exceeded</li>
                    <li>Network connection issue</li>
                    <li>Invalid API key</li>
                </ul>
            </div>
        </div>
    </div>
</template>