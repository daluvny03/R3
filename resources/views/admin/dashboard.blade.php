<x-app-layout>
    <!-- Orange Header Bar -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4">

            <!-- KIRI : Judul Dashboard -->
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard Admin</h1>
                <p class="text-orange-100 mt-1">Kelola Produk dan Pantau Penjualan</p>
            </div>

            <!-- KANAN : Profile -->
            <div class="flex justify-start md:justify-end" x-data="{ showProfileMenu: false }">
                <div class="relative">
                    <div class="flex items-center space-x-3 cursor-pointer"
                        @click="showProfileMenu = !showProfileMenu">

                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                        </div>

                        <div class="text-right">
                            <h1 class="text-sm font-bold text-white">ERTIGA POS</h1>
                            <p class="text-xs text-orange-100" x-text="currentDate"></p>
                        </div>

                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <!-- Dropdown -->
                    <div x-show="showProfileMenu" @click.away="showProfileMenu = false" x-cloak
                        class="absolute right-0 mt-3 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-50">

                        <div class="p-4 border-b border-gray-200">
                            <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            <span
                                class="inline-block mt-2 px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-xs font-semibold">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>

                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                Profile
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                    Logout
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


    <!-- Top Navigation: 4 Statistics Cards -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-x divide-gray-200">
                <!-- Card 1: Total Produk -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Total Produk</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($totalProduk) }}</h3>
                            <div class="flex items-center text-blue-600">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <span class="text-xs font-medium">Produk terdaftar</span>
                            </div>
                        </div>
                        <div class="bg-blue-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Stok Menipis -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Stok Menipis</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($totalStokMenipis) }}</h3>
                            <div class="flex items-center {{ $totalStokMenipis > 0 ? 'text-red-600' : 'text-green-600' }}">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($totalStokMenipis > 0)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                <span class="text-xs font-medium">{{ $totalStokMenipis > 0 ? 'Perlu perhatian' : 'Semua aman' }}</span>
                            </div>
                        </div>
                        <div class="{{ $totalStokMenipis > 0 ? 'bg-red-100' : 'bg-green-100' }} rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 {{ $totalStokMenipis > 0 ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Kategori -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Total Kategori</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($totalKategori) }}</h3>
                            <div class="flex items-center text-purple-600">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span class="text-xs font-medium">Kategori aktif</span>
                            </div>
                        </div>
                        <div class="bg-purple-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Transaksi -->
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs font-medium mb-1 uppercase tracking-wide">Total Transaksi</p>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($totalTransaksi) }}</h3>
                            <div class="flex items-center text-green-600">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="text-xs font-medium">Total penjualan</span>
                            </div>
                        </div>
                        <div class="bg-green-100 rounded-xl p-3 ml-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Aksi Cepat (Left) & Stok Menipis (Right) -->
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Side: AKSI CEPAT (2 columns) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">AKSI CEPAT</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Kelola Produk -->
                            <a href="{{ route('admin.products') }}" 
                               class="group bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-2xl p-8 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <h3 class="text-2xl font-bold group-hover:translate-x-1 transition-transform">Kelola Produk</h3>
                            </a>

                            <!-- Kelola Kategori -->
                            <a href="{{ route('admin.categories') }}" 
                               class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-2xl p-8 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <h3 class="text-2xl font-bold group-hover:translate-x-1 transition-transform">Kelola Kategori</h3>
                            </a>

                            <!-- Kelola Transaksi -->
                            <a href="{{ route('admin.transactions') }}" 
                               class="group bg-gradient-to-br from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-2xl p-8 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <h3 class="text-2xl font-bold group-hover:translate-x-1 transition-transform">Kelola Transaksi</h3>
                            </a>

                            <!-- Kelola Stok -->
                            <a href="{{ route('admin.stocks') }}" 
                               class="group bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-2xl p-8 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <h3 class="text-2xl font-bold group-hover:translate-x-1 transition-transform">Kelola Stok</h3>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Produk dengan Stok Menipis (1 column) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm sticky top-6">
                        <!-- Header -->
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-red-100 rounded-xl p-2.5 mr-3">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900">Produk dengan Stok Menipis</h3>
                                </div>
                                <a href="{{ route('admin.stocks') }}" 
                                   class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition">
                                    Semua
                                </a>
                            </div>
                        </div>

                        <!-- Product List -->
                        <div class="divide-y divide-gray-100">
                            @forelse($lowStockProducts as $product)
                                <div class="p-5 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <!-- Product Image/Icon -->
                                        @if($product->gambar)
                                            <img src="{{ Storage::url($product->gambar) }}" 
                                                 alt="{{ $product->nama_produk }}" 
                                                 class="w-14 h-14 rounded-xl object-cover mr-4 shadow-sm">
                                        @else
                                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center mr-4 shadow-sm">
                                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate mb-1">
                                                {{ $product->nama_produk }}
                                            </p>
                                            <p class="text-xs text-gray-500 font-medium">
                                                ID : #{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}
                                            </p>
                                        </div>

                                        <!-- Stock Info -->
                                        <div class="text-right ml-4">
                                            <p class="text-xs text-gray-500 font-medium mb-1">Instock</p>
                                            <p class="text-xl font-bold {{ $product->stok <= 10 ? 'text-red-600' : 'text-orange-600' }}">
                                                {{ str_pad($product->stok, 2, '0', STR_PAD_LEFT) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center">
                                    <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Semua Stok Aman</p>
                                    <p class="text-xs text-gray-500">Tidak ada produk dengan stok menipis</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>