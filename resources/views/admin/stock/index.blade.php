<x-app-layout>
    <!-- Orange Header Bar -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-3xl font-bold text-white">Kelola Stok Produk</h1>
            <p class="text-orange-100 mt-1">Update dan pantau stok produk secara real-time</p>
        </div>
    </div>

    <!-- Statistics Top Nav -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-x divide-gray-200">
                <!-- Total Produk -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Total Produk</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $products->count() }}</h3>
                            <p class="text-xs text-blue-600 font-medium">Produk terdaftar</p>
                        </div>
                        <div class="bg-blue-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stok Aman -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Stok Aman</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $products->where('stok', '>=', 50)->count() }}</h3>
                            <p class="text-xs text-green-600 font-medium">Stok mencukupi</p>
                        </div>
                        <div class="bg-green-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stok Rendah -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Stok Rendah</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $products->where('stok', '<', 10)->count() }}</h3>
                            <p class="text-xs text-yellow-600 font-medium">Perlu diisi ulang</p>
                        </div>
                        <div class="bg-yellow-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Penyesuaian -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Total Penyesuaian</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $stockAdjustments->total() }}</h3>
                            <p class="text-xs text-purple-600 font-medium">Riwayat update</p>
                        </div>
                        <div class="bg-purple-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
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

            <!-- Daftar Produk -->
            <div class="bg-white rounded-2xl shadow-sm mb-8 border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Daftar Produk & Stok</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($product->gambar)
                                                <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_produk }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                            @else
                                                <div class="w-10 h-10 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $product->nama_produk }}</div>
                                                <div class="text-xs text-gray-500">{{ $product->satuan }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $product->category ? $product->category->nama_kategori : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-bold text-gray-900">{{ number_format($product->stok) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stock_badge }}">
                                            {{ $product->stock_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openStockModal({{ $product->id }}, '{{ $product->nama_produk }}', {{ $product->stok }})" 
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                                            Update Stok
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Riwayat Penyesuaian Stok -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Riwayat Penyesuaian Stok</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok Sebelum</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok Sesudah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Oleh</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($stockAdjustments as $adjustment)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $adjustment->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $adjustment->product->nama_produk }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $adjustment->tipe_badge }}">
                                            {{ ucfirst($adjustment->tipe) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        {{ $adjustment->jumlah > 0 ? '+' : '' }}{{ number_format($adjustment->jumlah) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ number_format($adjustment->stok_sebelum) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                        {{ number_format($adjustment->stok_sesudah) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $adjustment->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $adjustment->keterangan ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="text-gray-500 font-medium">Belum ada riwayat penyesuaian stok</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($stockAdjustments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $stockAdjustments->links() }}
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

    <!-- Modal Update Stok -->
    <div id="modalStok" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <h3 class="text-xl font-bold text-gray-900">Update Stok</h3>
                <button onclick="closeStockModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="formStok" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Produk</label>
                        <input type="text" id="stok_nama_produk" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Saat Ini</label>
                        <input type="text" id="stok_current" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Penyesuaian *</label>
                        <select name="tipe" id="stok_tipe" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Tipe</option>
                            <option value="masuk">Stok Masuk (+)</option>
                            <option value="keluar">Stok Keluar (-)</option>
                            <option value="koreksi">Koreksi Stok (Set Langsung)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah *</label>
                        <input type="number" name="jumlah" id="stok_jumlah" required min="1" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1" id="stok_hint"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeStockModal()" 
                            class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-md transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    

    <script>
        let currentStock = 0;

        function openStockModal(productId, productName, stock) {
            currentStock = stock;
            document.getElementById('formStok').action = `/admin/stocks/${productId}`;
            document.getElementById('stok_nama_produk').value = productName;
            document.getElementById('stok_current').value = stock;
            document.getElementById('stok_tipe').value = '';
            document.getElementById('stok_jumlah').value = '';
            document.getElementById('stok_hint').textContent = '';
            document.getElementById('modalStok').classList.remove('hidden');
        }

        function closeStockModal() {
            document.getElementById('modalStok').classList.add('hidden');
        }

        document.getElementById('stok_tipe').addEventListener('change', function() {
            const hint = document.getElementById('stok_hint');
            switch(this.value) {
                case 'masuk':
                    hint.textContent = 'Stok akan ditambah dengan jumlah yang diinput';
                    break;
                case 'keluar':
                    hint.textContent = 'Stok akan dikurangi dengan jumlah yang diinput';
                    break;
                case 'koreksi':
                    hint.textContent = 'Stok akan diset langsung ke jumlah yang diinput';
                    break;
                default:
                    hint.textContent = '';
            }
        });
    </script>
</x-app-layout>