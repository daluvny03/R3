<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-10">
            <img src="/images/logo-ertiga.png" alt="ERTIGA POS" class="w-20 mb-3">
            <h1 class="text-2xl font-semibold text-orange-500 tracking-wide">ERTIGA POS</h1>
        </div>

        <!-- Card Form -->
        <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-8 w-full max-w-sm">

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required autofocus
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role"
                        name="role"
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-orange-500 focus:border-orange-500">
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>


                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password"
                        type="password"
                        name="password"
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Register Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-full transition">
                        Register
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-orange-500 font-semibold hover:text-orange-600">
                        Login di sini
                    </a>
                </p>
            </div>

        </div>

    </div>
</x-guest-layout>
