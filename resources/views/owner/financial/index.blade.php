<x-app-layout>
    <div class="bg-gradient-to-r from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Laporan Keuangan</h1>
                    <p class="text-white mt-2">Kelola dan monitor keuangan bisnis Anda</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 py-8">

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Bulan -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('owner.financial.index') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode</label>
                    <input type="month" name="bulan" value="{{ $bulan }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('owner.financial.expense.create') }}"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    + Tambah Pengeluaran
                </a>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="text-sm opacity-90">Total Pendapatan</div>
                <div class="text-3xl font-bold mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                <div class="text-sm opacity-90">Total Beban</div>
                <div class="text-3xl font-bold mt-2">Rp {{ number_format($totalBeban, 0, ',', '.') }}</div>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="text-sm opacity-90">Laba Bersih</div>
                <div class="text-3xl font-bold mt-2">Rp {{ number_format($labaBersih, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Laporan Penjualan</h3>
                <p class="text-gray-600 mb-4">Analisis detail transaksi dan produk terlaris</p>
                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('owner.financial.laporan-penjualan', ['bulan' => $bulan]) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Lihat Laporan
                    </a>
                    <a href="{{ route('owner.financial.export-pdf', ['tipe' => 'penjualan', 'bulan' => $bulan]) }}"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        PDF
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Laporan Laba-Rugi</h3>
                <p class="text-gray-600 mb-4">Lihat detail pendapatan dan beban operasional</p>
                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('owner.financial.laba-rugi', ['bulan' => $bulan]) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Lihat Laporan
                    </a>
                    <a href="{{ route('owner.financial.export-pdf', ['tipe' => 'laba-rugi', 'bulan' => $bulan]) }}"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        PDF
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Laporan Neraca</h3>
                <p class="text-gray-600 mb-4">Lihat posisi kas dan bank bisnis Anda</p>
                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('owner.financial.neraca', ['bulan' => $bulan]) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Lihat Laporan
                    </a>
                    <a href="{{ route('owner.financial.export-pdf', ['tipe' => 'neraca', 'bulan' => $bulan]) }}"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Daftar Expenses -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4">Daftar Expense Bulan Ini</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $expense->tanggal->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                        {{ ucfirst($expense->kategori) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                    Rp {{ number_format($expense->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($expense->metode_bayar) }}</td>
                                <td class="px-6 py-4">{{ $expense->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada data expense untuk bulan ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $expenses->links() }}
            </div>
        </div>
        <div class="button mt-4">
            <a href="{{ route('dashboard') }}"
                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
