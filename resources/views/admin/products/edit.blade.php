<x-app-layout>
    <!-- Orange Header Bar -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center">
                <a href="{{ route('admin.products') }}" class="mr-4 text-white hover:text-orange-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-white">Edit Produk</h1>
                    <p class="text-orange-100 mt-1">Perbarui informasi produk {{ $product->nama_produk }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-700 font-medium mb-2">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header Card -->
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-8 py-6">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-xl p-3 mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Edit Informasi Produk</h3>
                            <p class="text-orange-100 text-sm">Perbarui detail produk yang sudah ada</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Info Produk -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-700">
                                    <p class="font-semibold mb-1">Informasi Produk</p>
                                    <p>ID: #{{ $product->id }} | Stok Saat Ini: {{ $product->stok }} {{ $product->satuan }} | Dibuat: {{ $product->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Produk -->
                        <div>
                            <label for="nama_produk" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_produk" 
                                   id="nama_produk" 
                                   value="{{ old('nama_produk', $product->nama_produk) }}"
                                   required 
                                   placeholder="Contoh: Laptop ASUS ROG, Sepatu Nike, dll"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kategori
                            </label>
                            <select name="category_id" 
                                    id="category_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                <option value="{{ $product->categories_id }}">{{ $product->kategori }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Harga Beli & Harga Jual -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="harga_beli" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Harga Beli <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                    <input type="number" 
                                           name="harga_beli" 
                                           id="harga_beli" 
                                           value="{{ old('harga_beli', $product->harga_beli) }}"
                                           required 
                                           min="0"
                                           placeholder="0"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                </div>
                            </div>

                            <div>
                                <label for="harga_jual" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Harga Jual <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                    <input type="number" 
                                           name="harga_jual" 
                                           id="harga_jual" 
                                           value="{{ old('harga_jual', $product->harga_jual) }}"
                                           required 
                                           min="0"
                                           placeholder="0"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                </div>
                            </div>
                        </div>

                        <!-- Stok & Satuan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="stok" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="stok" 
                                       id="stok" 
                                       value="{{ old('stok', $product->stok) }}"
                                       required 
                                       min="0"
                                       placeholder="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                <p class="text-xs text-gray-500 mt-1">Gunakan menu "Kelola Stok" untuk update stok yang lebih detail</p>
                            </div>

                            <div>
                                <label for="satuan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Satuan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="satuan" 
                                       id="satuan" 
                                       value="{{ old('satuan', $product->satuan) }}"
                                       required 
                                       placeholder="Pcs, Kg, Liter, Box, dll"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- Gambar Produk -->
                        <div>
                            <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                                Gambar Produk
                            </label>

                            @if($product->gambar)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                                    

                                    <img src="{{ asset('storage/' . $product->gambar) }}" 
                                         alt="{{ $product->nama_produk }}" 
                                         class="w-48 h-48 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endif

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-400 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500">
                                            <span>Upload gambar baru</span>
                                            <input id="gambar" name="gambar" type="file" accept="image/*" class="sr-only" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                                </div>
                            </div>
                            <!-- Image Preview -->
                            <div id="imagePreview" class="hidden mt-4">
                                <p class="text-sm text-gray-600 mb-2">Preview gambar baru:</p>
                                <img id="preview" class="w-full h-48 object-cover rounded-lg" alt="Preview">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.products') }}" 
                           class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium shadow-md transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Produk
                        </button>
                    </div>
                </form>
            </div>

            <!-- Warning Box -->
            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 mb-1">Perhatian!</p>
                        <p class="text-sm text-yellow-700">Perubahan harga akan mempengaruhi transaksi baru. Transaksi lama tetap menggunakan harga saat transaksi dilakukan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>