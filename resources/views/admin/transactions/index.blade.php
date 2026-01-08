<x-app-layout>
    <!-- Orange Header Bar -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-3xl font-bold text-white">Kelola Transaksi</h1>
            <p class="text-orange-100 mt-1">Monitor dan kelola semua transaksi penjualan</p>
        </div>
    </div>

    <!-- Statistics Top Nav -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-x divide-gray-200">
                <!-- Total Transaksi -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Total Transaksi</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($statistics['total_transaksi']) }}</h3>
                            <p class="text-xs text-blue-600 font-medium">Semua waktu</p>
                        </div>
                        <div class="bg-blue-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Pendapatan -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Total Pendapatan</p>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Rp {{ number_format($statistics['total_pendapatan'], 0, ',', '.') }}</h3>
                            <p class="text-xs text-green-600 font-medium">Akumulasi penjualan</p>
                        </div>
                        <div class="bg-green-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Hari Ini -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Transaksi Hari Ini</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($statistics['transaksi_hari_ini']) }}</h3>
                            <p class="text-xs text-purple-600 font-medium">Hari ini</p>
                        </div>
                        <div class="bg-purple-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pendapatan Hari Ini -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Pendapatan Hari Ini</p>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Rp {{ number_format($statistics['pendapatan_hari_ini'], 0, ',', '.') }}</h3>
                            <p class="text-xs text-orange-600 font-medium">Penjualan hari ini</p>
                        </div>
                        <div class="bg-orange-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Filter -->
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6 border border-gray-100">
                <form method="GET" action="{{ route('admin.transactions') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition shadow-md">
                            Filter
                        </button>
                        <a href="{{ route('admin.transactions') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Transaksi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Riwayat Transaksi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal & Waktu</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Item</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Harga</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode Pembayaran</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        {{ $transaction->items->sum('jumlah') }} item
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if($transaction->payments->count() > 1)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Split Payment
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($transaction->metode_pembayaran) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ in_array($transaction->status, ['selesai', 'success']) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <button onclick="viewTransaction({{ $transaction->id }})" 
                                                    class="text-blue-600 hover:text-blue-900 font-medium">
                                                Detail
                                            </button>
                                            <form action="{{ route('admin.transactions.destroy', $transaction) }}" method="POST" class="inline-block" 
                                                  onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Stok produk akan dikembalikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
            <div class="button mt-4">
                <a href="{{ route('dashboard') }}" 
                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Detail Transaksi -->
    <div id="modalDetail" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <h3 class="text-xl font-bold text-gray-900">Detail Transaksi</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modalDetailContent" class="space-y-4">
                <!-- Content akan diisi via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        function viewTransaction(id) {
            fetch(`/transactions/${id}`)
                .then(response => response.json())
                .then(data => {
                    let itemsHtml = '';
                    data.items.forEach(item => {
                        itemsHtml += `
                            <tr>
                                <td class="px-4 py-2">${item.product.nama_produk}</td>
                                <td class="px-4 py-2 text-center">${item.jumlah}</td>
                                <td class="px-4 py-2 text-right">Rp ${parseInt(item.harga_satuan).toLocaleString('id-ID')}</td>
                                <td class="px-4 py-2 text-right font-semibold">Rp ${parseInt(item.subtotal).toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });

                    let paymentsHtml = '';
                    data.payments.forEach(payment => {
                        paymentsHtml += `
                            <div class="flex justify-between py-1">
                                <span>${payment.metode_pembayaran}</span>
                                <span class="font-semibold">Rp ${parseInt(payment.jumlah_bayar).toLocaleString('id-ID')}</span>
                            </div>
                        `;
                    });

                    document.getElementById('modalDetailContent').innerHTML = `
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">ID Transaksi</p>
                                <p class="font-semibold">#${String(data.id).padStart(5, '0')}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal</p>
                                <p class="font-semibold">${new Date(data.tanggal_transaksi).toLocaleString('id-ID')}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Kasir</p>
                                <p class="font-semibold">${data.user.name}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${data.status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                    ${data.status}
                                </span>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="font-semibold mb-3">Item Produk</h4>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Produk</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Jumlah</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Harga</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    ${itemsHtml}
                                </tbody>
                            </table>
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="font-semibold mb-3">Pembayaran</h4>
                            ${paymentsHtml}
                            <div class="flex justify-between pt-2 mt-2 border-t font-bold text-lg">
                                <span>Total</span>
                                <span>Rp ${parseInt(data.total_harga).toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    `;

                    document.getElementById('modalDetail').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat detail transaksi');
                });
        }

        function closeDetailModal() {
            document.getElementById('modalDetail').classList.add('hidden');
        }
    </script>
</x-app-layout>