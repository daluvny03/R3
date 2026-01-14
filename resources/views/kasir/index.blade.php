<x-app-layout :hide-navigation="true">
    <div x-data="kasirApp" x-init="init()" class="flex h-screen bg-gray-50 overflow-hidden">
        <!-- LEFT SIDEBAR - Categories & Saved Orders -->
        <div class="w-64 bg-white border-r border-gray-200 overflow-y-auto">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200" x-data="{ showProfileMenu: false }">
                <div class="relative">
                    <div class="flex items-center space-x-3 cursor-pointer" @click="showProfileMenu = !showProfileMenu">
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-xl font-bold text-gray-800">ERTIGA POS</h1>
                            <p class="text-xs text-gray-500" x-text="currentDate"></p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>

                    <!-- Profile Dropdown -->
                    <div x-show="showProfileMenu" @click.away="showProfileMenu = false" x-cloak
                        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
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
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs: Categories & Saved Orders -->
            <div class="border-b border-gray-200">
                <div class="flex">
                    <button @click="activeTab = 'categories'"
                        :class="activeTab === 'categories' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500'"
                        class="flex-1 py-3 font-semibold text-sm">
                        Menu
                    </button>
                    <button @click="activeTab = 'saved'"
                        :class="activeTab === 'saved' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500'"
                        class="flex-1 py-3 font-semibold text-sm relative">
                        Tersimpan
                        <span x-show="savedOrders.length > 0"
                            class="absolute top-2 right-6 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
                            x-text="savedOrders.length"></span>
                    </button>
                </div>
            </div>

            <!-- Categories Tab -->
            <div x-show="activeTab === 'categories'" class="p-4">
                <h3 class="text-sm font-bold text-gray-400 uppercase mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    KATEGORI
                </h3>

                @foreach ($categories as $category)
                    <button
                        @click="selectedCategory = selectedCategory === '{{ $category }}' ? null : '{{ $category }}'"
                        :class="selectedCategory === '{{ $category }}' ? 'bg-orange-500 text-white' :
                            'bg-white text-gray-700 hover:bg-gray-50'"
                        class="w-full text-left px-4 py-3 rounded-lg mb-2 font-medium transition-all duration-200 border border-gray-200">
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            <!-- Saved Orders Tab -->
            <div x-show="activeTab === 'saved'" class="p-4">
                <template x-if="savedOrders.length === 0">
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-gray-400 text-sm">Tidak ada pesanan tersimpan</p>
                    </div>
                </template>

                <div class="space-y-3">
                    <template x-for="(order, index) in savedOrders" :key="index">
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 hover:bg-orange-100 cursor-pointer transition-colors"
                            @click="loadSavedOrder(index)">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">Pesanan #<span
                                            x-text="order.orderNumber"></span></p>
                                    <p class="text-xs text-gray-500" x-text="order.savedTime"></p>
                                </div>
                                <button @click.stop="deleteSavedOrder(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-sm text-gray-700"><span x-text="order.items.length"></span> items • Rp
                                <span x-text="formatNumber(order.total)"></span></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- CENTER - Products Grid -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Search Bar -->
            <div class="bg-white border-b border-gray-200 p-4">
                <div class="relative max-w-2xl">
                    <input type="text" x-model="searchQuery" @input="searchProducts()" placeholder="Search"
                        class="w-full pl-10 pr-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)"
                            class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer border-2 border-transparent hover:border-orange-500 overflow-hidden group">
                            <div class="relative bg-gray-100 aspect-square overflow-hidden">
                                <template x-if="product.gambar">
                                    <img :src="'/storage/' + product.gambar" :alt="product.nama_produk"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </template>
                                <template x-if="!product.gambar">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </template>

                                <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded-full text-xs font-semibold"
                                    :class="product.stok <= 10 ? 'text-red-600' : 'text-green-600'">
                                    <span x-text="product.stok"></span> stock
                                </div>
                            </div>

                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 mb-1 line-clamp-2" x-text="product.nama_produk">
                                </h3>
                                <p class="text-orange-600 font-bold text-lg"
                                    x-text="'Rp. ' + formatNumber(product.harga_jual)"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="filteredProducts.length === 0" class="text-center py-20">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <p class="text-gray-500 font-medium">Produk tidak ditemukan</p>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR - Cart -->
        <div class="w-96 bg-white border-l border-gray-200 flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-800">Menu Pesanan</h2>
                            <p class="text-xs text-gray-500">Pesanan No. <span x-text="orderNumber"></span></p>
                        </div>
                    </div>
                    <button @click="saveCurrentOrder()" :disabled="cart.length === 0"
                        class="text-blue-500 hover:text-blue-700 disabled:text-gray-300" title="Simpan Pesanan">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-center">
                        <svg class="w-32 h-32 text-gray-200 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p class="text-gray-400 font-medium text-lg mb-2">Keranjang Kosong</p>
                        <p class="text-gray-300 text-sm">Silahkan Pilih Menu...</p>
                    </div>
                </template>

                <div class="space-y-3">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1 pr-3">
                                    <h4 class="font-bold text-gray-800 mb-1" x-text="item.nama_produk"></h4>
                                    <p class="text-orange-600 font-bold"
                                        x-text="'Rp. ' + formatNumber(item.harga_jual)"></p>
                                </div>
                                <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button @click="decrementQty(index)"
                                        class="w-8 h-8 bg-white border-2 border-gray-300 rounded-lg flex items-center justify-center hover:border-orange-500 hover:text-orange-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span class="w-12 text-center font-bold text-lg" x-text="item.qty"></span>
                                    <button @click="incrementQty(index)"
                                        class="w-8 h-8 bg-orange-500 text-white rounded-lg flex items-center justify-center hover:bg-orange-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600">Subtotal: <span class="font-bold text-gray-800"
                                        x-text="'Rp. ' + formatNumber(item.harga_jual * item.qty)"></span></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="border-t border-gray-200 p-4 bg-gradient-to-br from-orange-500 to-orange-600">
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4 mb-3">
                    <div class="flex justify-between items-center text-white mb-1">
                        <span class="font-medium" x-text="cart.length + ' items'"></span>
                        <span class="text-sm">Total</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-3xl font-bold text-white" x-text="'Rp ' + formatNumber(totalAmount)"></h3>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <button @click="saveCurrentOrder()" :disabled="cart.length === 0"
                        class="bg-white bg-opacity-20 hover:bg-opacity-30 disabled:bg-opacity-10 text-white py-3 rounded-xl font-semibold transition-all disabled:cursor-not-allowed flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                    </button>
                    <button @click="showPaymentModal = true" :disabled="cart.length === 0"
                        class="col-span-2 bg-white text-orange-600 py-3 rounded-xl font-bold text-lg hover:bg-opacity-90 transition-all disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed shadow-lg">
                        Pesan
                    </button>
                </div>
            </div>
        </div>

        <!-- Payment Method Modal -->
        <div x-show="showPaymentModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            @click.self="showPaymentModal = false">
            <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">💳 Metode Pembayaran</h3>
                    <button @click="showPaymentModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="bg-orange-50 rounded-xl p-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Total:</span>
                        <span class="text-xl font-bold text-orange-600"
                            x-text="'Rp ' + formatNumber(totalAmount)"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Metode:</label>

                    <div class="grid grid-cols-2 gap-2">
                        <button @click="selectedPaymentMethod = 'Tunai'"
                            :class="selectedPaymentMethod === 'Tunai' ? 'bg-orange-500 text-white border-orange-500' :
                                'bg-white text-gray-700 border-gray-300'"
                            class="border-2 rounded-lg p-3 transition-all flex flex-col items-center space-y-1">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span class="font-semibold text-sm">Tunai</span>
                        </button>

                        <button @click="selectedPaymentMethod = 'QRIS'"
                            :class="selectedPaymentMethod === 'QRIS' ? 'bg-orange-500 text-white border-orange-500' :
                                'bg-white text-gray-700 border-gray-300'"
                            class="border-2 rounded-lg p-3 transition-all flex flex-col items-center space-y-1">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                </path>
                            </svg>
                            <span class="font-semibold text-sm">QRIS</span>
                        </button>
                    </div>
                </div>

                <div x-show="selectedPaymentMethod === 'Tunai'" class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Uang:</label>
                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold text-sm">Rp</span>
                        <input type="number" x-model="cashReceived" @input="calculateChange()" placeholder="0"
                            class="w-full pl-10 pr-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-semibold">
                    </div>

                    <div class="grid grid-cols-4 gap-2 mt-2">
                        <button @click="quickCash(50000)"
                            class="bg-gray-100 hover:bg-gray-200 py-2 rounded-lg text-xs font-semibold">50K</button>
                        <button @click="quickCash(100000)"
                            class="bg-gray-100 hover:bg-gray-200 py-2 rounded-lg text-xs font-semibold">100K</button>
                        <button @click="quickCash(200000)"
                            class="bg-gray-100 hover:bg-gray-200 py-2 rounded-lg text-xs font-semibold">200K</button>
                        <button @click="quickCash(totalAmount)"
                            class="bg-orange-100 hover:bg-orange-200 text-orange-600 py-2 rounded-lg text-xs font-semibold">Pas</button>
                    </div>

                    <div x-show="cashReceived >= totalAmount && cashReceived > 0"
                        class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="text-green-700 font-semibold text-sm">Kembalian:</span>
                            <span class="text-lg font-bold text-green-600"
                                x-text="'Rp ' + formatNumber(change)"></span>
                        </div>
                    </div>

                    <div x-show="cashReceived > 0 && cashReceived < totalAmount"
                        class="mt-3 bg-red-50 border border-red-200 rounded-lg p-2 text-center">
                        <span class="text-red-600 font-semibold text-sm">⚠️ Uang tidak cukup!</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button @click="showPaymentModal = false"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold text-sm">
                        Batal
                    </button>
                    <button @click="confirmPayment()" :disabled="!canProceed"
                        class="flex-1 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 text-white py-3 rounded-lg font-semibold text-sm">
                        Lanjut
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="showConfirmationModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Konfirmasi Pesanan</h3>
                    <p class="text-gray-600">Pastikan data pesanan sudah benar</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 mb-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Items:</span>
                        <span class="font-bold" x-text="cart.length + ' items'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Belanja:</span>
                        <span class="font-bold" x-text="'Rp ' + formatNumber(totalAmount)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Pembayaran:</span>
                        <span class="font-bold" x-text="selectedPaymentMethod"></span>
                    </div>
                    <template x-if="selectedPaymentMethod === 'Tunai' && change > 0">
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-600">Kembalian:</span>
                            <span class="font-bold text-green-600" x-text="'Rp ' + formatNumber(change)"></span>
                        </div>
                    </template>
                </div>

                <div class="space-y-3">
                    <button @click="processTransaction()"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-xl font-bold text-lg transition-all">
                        ✓ Selesaikan Pesanan
                    </button>
                    <button @click="showConfirmationModal = false"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-xl font-semibold transition-all">
                        Kembali
                    </button>
                </div>
            </div>
        </div>

        {{-- Show QRIS modal --}}
        <div x-show="showQrisModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="closeQrisModal()">

            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-xl font-bold text-center mb-4">
                    Scan QRIS
                </h3>

                <div id="qris-container" class="mb-4"></div>
                <div x-show="qrisMessage" class="mt-4 text-center font-semibold"
                    :class="qrisStatus === 'paid'
                        ?
                        'text-green-600' :
                        qrisStatus === 'failed' ?
                        'text-red-600' :
                        'text-yellow-600'">
                    <span x-text="qrisMessage"></span>
                </div>

                <button @click="closeQrisModal()" class="w-full mt-4 bg-gray-300 py-2 rounded">
                    Tutup
                </button>
            </div>
        </div>

        <!-- Success Modal -->
        <div x-show="showSuccessModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl">
                <div class="text-center mb-6">
                    <div
                        class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-2">Transaksi Berhasil!</h3>
                    <p class="text-gray-600">Pesanan telah diproses</p>
                </div>

                <div class="bg-orange-50 rounded-xl p-4 mb-6">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">No. Transaksi</p>
                        <p class="text-2xl font-bold text-orange-600"
                            x-text="'#' + String(lastTransactionId).padStart(6, '0')"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-orange-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-600">Total</p>
                            <p class="font-bold text-gray-800" x-text="'Rp ' + formatNumber(totalAmount)"></p>
                        </div>
                        <div class="text-center" x-show="change > 0">
                            <p class="text-xs text-gray-600">Kembalian</p>
                            <p class="font-bold text-green-600" x-text="'Rp ' + formatNumber(change)"></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <button @click="printReceipt()"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-xl font-bold text-lg transition-all flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        <span>Cetak Struk</span>
                    </button>
                    <button @click="finishTransaction()"
                        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-semibold transition-all">
                        Selesai (Tanpa Cetak)
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div x-show="showToast" x-transition x-cloak
            :class="toastType === 'success' ? 'bg-green-500' : toastType === 'error' ? 'bg-red-500' : 'bg-blue-500'"
            class="fixed bottom-6 right-6 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 z-50 max-w-md">
            <svg x-show="toastType === 'success'" class="w-6 h-6" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="toastType === 'error'" class="w-6 h-6" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
            <svg x-show="toastType === 'info'" class="w-6 h-6" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span x-text="toastMessage" class="font-semibold"></span>
        </div>
    </div>

    {{-- Midtrans --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
    window.KASIR_DATA = {
        products: @json($products),
        csrfToken: '{{ csrf_token() }}',
        receiptUrl: '{{ url('kasir/receipt') }}'
    }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #fb923c;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(-25%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }

            50% {
                transform: translateY(0);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }

        .animate-bounce {
            animation: bounce 1s infinite;
        }
    </style>
</x-app-layout>
