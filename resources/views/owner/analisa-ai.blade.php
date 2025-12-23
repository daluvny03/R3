<x-app-layout>
    <div class="bg-gradient-to-r from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center">
                <div>
                <h1 class="text-3xl font-bold text-white">Analisa AI</h1>
                <p class="text-white mt-2">AI-Powered Business Intelligence & Insights</p>
            </div>
            </div>
        </div>
    </div>

    <div class="py-12" x-data="aiAnalysis()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Analysis Type Selector -->
            <x-ai.analysis-selector />

            <!-- Results Display -->
            <div x-show="results" x-transition class="mt-6">
                <!-- Error Display -->
                <x-ai.error-display />

                <!-- Sales Prediction -->
                <x-ai.sales-prediction />

                <!-- Stock Recommendation -->
                <x-ai.stock-recommendation />

                <!-- Trends Analysis -->
                <x-ai.trends-analysis />

                <!-- Anomaly Detection -->
                <x-ai.anomaly-detection />

                <!-- Customer Insights -->
                <x-ai.customer-insights />

                <!-- Comprehensive Analysis -->
                <x-ai.comprehensive-analysis />
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function aiAnalysis() {
            return {
                analysisType: null,
                loading: false,
                results: null,
                activeTab: 'sales',
                
                analyzeType(type) {
                    this.analysisType = type;
                    this.results = null;
                },
                
                async runAnalysis() {
                    if (!this.analysisType) return;
                    
                    this.loading = true;
                    this.results = null;
                    
                    try {
                        const response = await fetch(`/owner/ai-analysis/${this.analysisType}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        // Parse JSON string if needed
                        if (typeof data === 'string') {
                            try {
                                this.results = JSON.parse(data);
                            } catch (e) {
                                this.results = { analysis: data };
                            }
                        } else {
                            this.results = data;
                        }
                        
                    } catch (error) {
                        console.error('Analysis failed:', error);
                        this.results = {
                            error: 'Analysis failed: ' + error.message
                        };
                    } finally {
                        this.loading = false;
                    }
                },
                
                formatRupiah(number) {
                    if (!number) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>