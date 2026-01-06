import './bootstrap';

import Alpine from 'alpinejs';
import './kasir';

window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {
    Alpine.data('aiAnalysis', () => ({
        analysisType: null,
        loading: false,
        results: null,
        formatRupiah(number) {
                    if (!number) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
                },
        analyzeType(type) {
            this.analysisType = type;
        },

        async runAnalysis() {
    if (!this.analysisType) return;
    this.loading = true;

    try {
        const response = await fetch(`/owner/ai-analysis/${this.analysisType}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const raw = await response.text();
        console.log('[AI] Raw response:', raw);

        let json;
        try {
            json = JSON.parse(raw);
        } catch {
            throw new Error('Invalid JSON from server');
        }

        if (!response.ok) {
            throw new Error(json.error || 'Analysis failed');
        }

        this.results = json.data;

    } catch (e) {
        console.error('[AI] Analysis failed:', e.message);
        alert('Analysis failed');
    } finally {
        this.loading = false;
    }
}

    }));
});

Alpine.start();
