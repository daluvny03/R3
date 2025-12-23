<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-10">
            <img src="/images/logo-ertiga.png" alt="ERTIGA POS" class="w-20 mb-3">
            <h1 class="text-2xl font-semibold text-orange-500 tracking-wide">ERTIGA POS</h1>
        </div>

        <!-- Card -->
        <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-8 w-full max-w-sm">

            <p class="text-sm text-gray-600 mb-4">
                Silakan masukkan password baru Anda.
            </p>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-full transition">
                        Reset Password
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-orange-600">
                    Kembali ke Login
                </a>
            </div>

        </div>

    </div>
</x-guest-layout>
