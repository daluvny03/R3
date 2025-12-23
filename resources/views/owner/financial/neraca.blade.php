<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Neraca</h1>
        <p class="text-gray-600 mt-2">Periode: {{ $periode }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="border-b pb-4 mb-6">
            <h2 class="text-2xl font-bold text-center">LAPORAN NERACA</h2>
            <p class="text-center text-gray-600 mt-2">{{ $periode }}</p>
        </div>

        <!-- Aset -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 text-blue-700">ASET</h3>
            <div class="pl-6">
                <div class="flex justify-between py-2">
                    <span>Kas</span>
                    <span class="font-semibold">Rp {{ number_format($kas, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span>Bank</span>
                    <span class="font-semibold">Rp {{ number_format($bank, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-t-2 font-bold text-lg">
                    <span>Total Aset</span>
                    <span class="text-blue-700">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 text-green-700">MODAL</h3>
            <div class="pl-6">
                <div class="flex justify-between py-2 font-bold text-lg">
                    <span>Modal</span>
                    <span class="text-green-700">Rp {{ number_format($modal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 mt-8 pt-8 border-t">
            <a href="{{ route('owner.financial.index') }}" 
               class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Kembali
            </a>
            <a href="{{ route('owner.financial.export-pdf', ['tipe' => 'neraca', 'bulan' => $bulan]) }}" 
               class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Export PDF
            </a>
        </div>
    </div>
</div>
</x-app-layout>