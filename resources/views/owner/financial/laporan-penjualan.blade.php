<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Penjualan</h1>
        <p class="text-gray-600 mt-2">Periode: {{ $periode }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-90">Total Penjualan</div>
            <div class="text-3xl font-bold mt-2">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-90">Total Transaksi</div>
            <div class="text-3xl font-bold mt-2">{{ $totalTransaksi }}</div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-90">Rata-rata Transaksi</div>
            <div class="text-3xl font-bold mt-2">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Grafik Penjualan Per Hari -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-xl font-semibold mb-4">Trend Penjualan Harian</h3>
        <canvas id="chartPenjualanHarian" height="80"></canvas>
    </div>

    <!-- Produk Terlaris -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-xl font-semibold mb-4">Top 10 Produk Terlaris</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Terjual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($produkTerlaris as $index => $produk)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $produk->nama_produk }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                {{ $produk->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-600">
                            {{ $produk->total_terjual }} unit
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">
                            Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Belum ada data penjualan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Penjualan per Metode & Kasir -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Metode Pembayaran -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4">Penjualan per Metode Pembayaran</h3>
            <div class="space-y-3">
                @foreach($penjualanPerMetode as $metode)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <div class="font-semibold">{{ ucfirst($metode->metode_pembayaran) }}</div>
                        <div class="text-sm text-gray-600">{{ $metode->jumlah_transaksi }} transaksi</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-blue-600">
                            Rp {{ number_format($metode->total_penjualan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Penjualan per Kasir -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4">Penjualan per Kasir</h3>
            <div class="space-y-3">
                @foreach($penjualanPerKasir as $kasir)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <div class="font-semibold">{{ $kasir->name }}</div>
                        <div class="text-sm text-gray-600">{{ $kasir->jumlah_transaksi }} transaksi</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-green-600">
                            Rp {{ number_format($kasir->total_penjualan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4 mt-8 pt-8 border-t">
        <a href="{{ route('owner.financial.index') }}" 
           class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
            Kembali
        </a>
        <a href="{{ route('owner.financial.export-pdf', ['tipe' => 'penjualan', 'bulan' => $bulan]) }}" 
           class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
            Export PDF
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart Penjualan Harian
const ctx = document.getElementById('chartPenjualanHarian').getContext('2d');
const chartData = {
    labels: @json($transaksiPerHari->pluck('tanggal')),
    datasets: [{
        label: 'Total Penjualan (Rp)',
        data: @json($transaksiPerHari->pluck('total_penjualan')),
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.4
    }]
};

new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
</x-app-layout>
