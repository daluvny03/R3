<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.categories') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Kategori') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi kategori {{ $category->nama_kategori }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header Card -->
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-8 py-6">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-lg p-3 mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Edit Informasi Kategori</h3>
                            <p class="text-orange-100 text-sm">Perbarui detail kategori yang sudah ada</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Info Kategori -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-700">
                                    <p class="font-semibold mb-1">Informasi Kategori</p>
                                    <p>ID: #{{ $category->id }} | Jumlah Produk: {{ $category->products_count }} | Dibuat: {{ $category->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Kategori -->
                        <div>
                            <label for="nama_kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_kategori" 
                                   id="nama_kategori" 
                                   value="{{ old('nama_kategori', $category->nama_kategori) }}"
                                   required 
                                   placeholder="Contoh: Elektronik, Makanan, dll"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                            <p class="text-xs text-gray-500 mt-1">Nama kategori harus unik dan mudah dikenali</p>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" 
                                      id="deskripsi" 
                                      rows="4" 
                                      placeholder="Jelaskan kategori ini untuk memudahkan identifikasi..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">{{ old('deskripsi', $category->deskripsi) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Opsional: Berikan deskripsi singkat tentang kategori ini</p>
                        </div>

                        <!-- Status Aktif -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500 cursor-pointer">
                                <span class="ml-3">
                                    <span class="text-sm font-semibold text-gray-900">Aktifkan Kategori</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">
                                        {{ $category->is_active ? 'Kategori saat ini aktif' : 'Kategori saat ini nonaktif' }}
                                    </span>
                                </span>
                            </label>
                        </div>

                        @if($category->products_count > 0)
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-800 mb-1">Perhatian!</p>
                                        <p class="text-sm text-yellow-700">Kategori ini memiliki {{ $category->products_count }} produk. Menonaktifkan kategori tidak akan menghapus produk yang ada.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.categories') }}" 
                           class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium shadow-md transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>