<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Laba-Rugi</h1>
        <p class="text-gray-600 mt-2">Periode: {{ $periode }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="border-b pb-4 mb-6">
            <h2 class="text-2xl font-bold text-center">LAPORAN LABA-RUGI</h2>
            <p class="text-center text-gray-600 mt-2">{{ $periode }}</p>
        </div>

        <!-- Pendapatan -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 text-green-700">PENDAPATAN</h3>
            <div class="pl-6">
                <div class="flex justify-between py-2">
                    <span>Total Penjualan ({{ $jumlahTransaksi }} transaksi)</span>
                    <span class="font-semibold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-t-2 font-bold text-lg">
                    <span>Total Pendapatan</span>
                    <span class="text-green-700">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Beban -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 text-red-700">BEBAN OPERASIONAL</h3>
            <div class="pl-6">
                @foreach($bebanPerKategori as $kategori => $jumlah)
                <div class="flex justify-between py-2">
                    <span>{{ ucfirst($kategori) }}</span>
                    <span class="font-semibold">Rp {{ number_format($jumlah, 0, ',', '.') }}</span>
                </div>
                @endforeach
                <div class="flex justify-between py-2 border-t-2 font-bold text-lg">
                    <span>Total Beban</span>
                    <span class="text-red-700">Rp {{ number_format($totalBeban, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Laba Bersih -->
        <div class="border-t-4 border-blue-600 pt-6">
            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold">LABA BERSIH</span>
                <span class="text-3xl font-bold {{ $labaBersih >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 mt-8 pt-8 border-t">
            <a href="{{ route('owner.financial.index') }}" 
               class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Kembali
            </a>
            <a href="{{ route('owner.financial.export-pdf', ['tipe' => 'laba-rugi', 'bulan' => $bulan]) }}" 
               class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Export PDF
            </a>
        </div>
    </div>
</div>
</x-app-layout>