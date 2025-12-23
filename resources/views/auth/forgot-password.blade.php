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
                Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- RESET LINK (Jika tersedia) -->
            @if (session('reset_link'))
                <div class="mb-4 text-sm bg-blue-50 text-blue-700 border border-blue-300 rounded-lg p-3">
                    <span class="font-semibold">Reset Link:</span><br>
                    <a href="{{ session('reset_link') }}" class="text-blue-600 underline break-all">
                        {{ session('reset_link') }}
                    </a>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-full transition">
                        Kirim Link Reset Password
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
