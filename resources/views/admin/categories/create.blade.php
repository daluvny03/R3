<x-app-layout>
    <!-- Orange Header Bar -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center">
                <a href="{{ route('admin.categories') }}" class="mr-4 text-white hover:text-orange-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-white">Tambah Kategori Baru</h1>
                    <p class="text-orange-100 mt-1">Buat kategori baru untuk produk</p>
                </div>
            </div>
        </div>
    </div>

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
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-lg p-3 mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Informasi Kategori</h3>
                            <p class="text-blue-100 text-sm">Isi formulir di bawah untuk membuat kategori baru</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.categories.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="space-y-6">
                        <!-- Nama Kategori -->
                        <div>
                            <label for="nama_kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama_kategori" 
                                   id="nama_kategori" 
                                   value="{{ old('nama_kategori') }}"
                                   required 
                                   placeholder="Contoh: Elektronik, Makanan, dll"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
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
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('deskripsi') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Opsional: Berikan deskripsi singkat tentang kategori ini</p>
                        </div>

                        <!-- Status Aktif -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer">
                                <span class="ml-3">
                                    <span class="text-sm font-semibold text-gray-900">Aktifkan Kategori</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Kategori akan langsung tersedia untuk digunakan</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.categories') }}" 
                           class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-md transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 mb-1">Tips Membuat Kategori</p>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Gunakan nama yang singkat dan jelas</li>
                            <li>• Pastikan kategori tidak duplikat</li>
                            <li>• Berikan deskripsi untuk memudahkan tim lain</li>
                            <li>• Kategori nonaktif tidak akan muncul saat menambah produk</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>