<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white py-8 px-6 shadow-lg">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold mb-2">Kelola Transaksi</h1>
                <p class="text-green-100">Pantau semua transaksi yang telah dilakukan</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto p-6">
            
            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🔍 Filter Laporan</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <p class="text-blue-100 text-sm mb-1">Total Transaksi</p>
                    <h3 class="text-4xl font-bold">{{ $transactions->total() }}</h3>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <p class="text-green-100 text-sm mb-1">Total Penjualan</p>
                    <h3 class="text-3xl font-bold">Rp {{ number_format($transactions->sum('total_harga'), 0, ',', '.') }}</h3>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <p class="text-purple-100 text-sm mb-1">Rata-rata Transaksi</p>
                    <h3 class="text-3xl font-bold">Rp {{ number_format($transactions->avg('total_harga'), 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">📋 Daftar Transaksi</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">ID</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Tanggal & Waktu</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Kasir</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Items</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Total</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Metode</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Status</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-gray-800">#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-800">{{ $transaction->tanggal_transaksi->format('d M Y') }}</p>
                                        <p class="text-gray-500">{{ $transaction->tanggal_transaksi->format('H:i') }} WIB</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-2">
                                            {{ substr($transaction->kasir->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $transaction->kasir->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        {{ $transaction->items->count() }} item
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-lg font-bold text-green-600">
                                        Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-sm text-gray-700">{{ $transaction->metode_pembayaran }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                        {{ $transaction->status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <button @click="$dispatch('show-detail', {{ $transaction->id }})" 
                                            class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        Detail →
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>