<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-10">
            <img src="/images/logo-ertiga.png" alt="ERTIGA POS" class="w-20 mb-3">
            <h1 class="text-2xl font-semibold text-orange-500 tracking-wide">ERTIGA POS</h1>
        </div>

        <!-- Card Form -->
        @if (session('error'))
            <div class="mb-4 text-red-600 bg-red-100 p-3 rounded-lg border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 text-green-600 bg-green-100 p-3 rounded-lg border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-lg border border-gray-200 rounded-2xl p-8 w-full max-w-sm">

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Username -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email"
                        type="text"
                        name="email"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500"
                        required autofocus />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password"
                        type="password"
                        name="password"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-orange-500 focus:border-orange-500"
                        required />
                </div>

                <!-- Login Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-full transition">
                        Login
                    </button>
                </div>
            </form>
            <!-- Reset Password Link -->
            <div class="text-center mt-4">
                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-orange-600">
                    Lupa password?
                </a>
            </div>
            <!-- Register Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-orange-500 font-semibold hover:text-orange-600">
                        Daftar di sini
                    </a>
                </p>
            </div>

        </div>

    </div>
</x-guest-layout>
