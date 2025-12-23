<x-app-layout>
    <div class="bg-gradient-to-r from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4">

                <!-- KIRI : Judul Dashboard -->
                <div>
                    <h1 class="text-3xl font-bold text-white">Dashboard Owner</h1>
                    <p class="text-orange-100 mt-1">Selamat Datang, Owner</p>
                </div>

                <!-- KANAN : Profile -->
                <div class="flex justify-start md:justify-end" x-data="{
                    showProfileMenu: false,
                    currentDate: new Date().toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    })
                }">
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

    <div class="max-w-7xl mx-auto px-8 py-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sales -->
            <div
                class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-orange-100 text-sm mb-1">Total Sales</p>
                <p class="text-3xl font-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            </div>

            <!-- Total Sales Return -->
            <div
                class="bg-gradient-to-br from-red-400 to-red-500 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3" />
                        </svg>
                    </div>
                </div>
                <p class="text-red-100 text-sm mb-1">Total Produk Terjual</p>
                <p class="text-3xl font-bold">Rp {{ number_format($produkTerjual, 0, ',', '.') }}</p>
            </div>

            <!-- Total Purchase -->
            <div
                class="bg-gradient-to-br from-green-400 to-green-500 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <p class="text-green-100 text-sm mb-1">Total Purchase</p>
                <p class="text-3xl font-bold">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</p>
            </div>

            <!-- Total Purchase Return -->
            <div
                class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <p class="text-blue-100 text-sm mb-1">Total Laba</p>
                <p class="text-3xl font-bold">Rp {{ number_format($totalLaba, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Charts -->
            <div class="lg:col-span-2">

                <!-- Time filter and Sales Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <canvas id="salesChart" height="200"></canvas>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {

                            const canvas = document.getElementById('salesChart');
                            if (!canvas) return;

                            const monthlyPurchases = @json($monthlyPurchases).map(Number);
                            const monthlySales = @json($monthlySales).map(Number);

                            new Chart(canvas.getContext('2d'), {
                                type: 'bar',
                                data: {
                                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                                        'Dec'
                                    ],
                                    datasets: [{
                                            label: 'Total Purchase',
                                            data: monthlyPurchases,
                                            backgroundColor: 'rgba(251,146,60,0.3)',
                                            borderRadius: 8
                                        },
                                        {
                                            label: 'Total Sales',
                                            data: monthlySales,
                                            backgroundColor: 'rgba(249,115,22,0.8)',
                                            borderRadius: 8
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        },
                                        x: {
                                            grid: {
                                                display: false
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                </div>


                <!-- Profit Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <canvas id="profitChart" height="120"></canvas>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {

                            const canvas = document.getElementById('profitChart');
                            if (!canvas) return;

                            const monthlyRevenue = @json($monthlyRevenue).map(Number);
                            const monthlyExpense = @json($monthlyExpense).map(Number);

                            new Chart(canvas.getContext('2d'), {
                                type: 'bar',
                                data: {
                                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                                        'Dec'
                                    ],
                                    datasets: [{
                                            label: 'Revenue',
                                            data: monthlyRevenue,
                                            backgroundColor: 'rgba(34,197,94,0.85)',
                                            borderRadius: 6,
                                            stack: 'profit'
                                        },
                                        {
                                            label: 'Expense',
                                            data: monthlyExpense,
                                            backgroundColor: 'rgba(239,68,68,0.85)',
                                            borderRadius: 6,
                                            stack: 'profit'
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 800,
                                        easing: 'easeOutQuart'
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const value = context.parsed.y || 0;
                                                    return context.dataset.label + ': Rp ' +
                                                        value.toLocaleString('id-ID');
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            stacked: true,
                                            grid: {
                                                display: false
                                            }
                                        },
                                        y: {
                                            stacked: true,
                                            beginAtZero: true,
                                            ticks: {
                                                callback: value => 'Rp ' + value.toLocaleString('id-ID')
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                </div>

            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Quick Nav -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center space-x-2 mb-6">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h2 class="text-xl font-bold text-gray-800">Quick Nav</h2>
                    </div>
                    <div class="space-y-4">
                        <a href="{{ route('owner.financial.index') }}" class="block group">
                            <div
                                class="bg-gradient-to-r from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 p-4 rounded-xl transition-all duration-300 transform group-hover:scale-105">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-orange-500 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800 group-hover:text-orange-600">Laporan
                                            Keuangan</p>
                                        <p class="text-xs text-gray-500">View financial reports</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transform group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('owner.analisa-ai') }}" class="block group">
                            <div
                                class="bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 p-4 rounded-xl transition-all duration-300 transform group-hover:scale-105">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-500 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800 group-hover:text-blue-600">Analisa AI</p>
                                        <p class="text-xs text-gray-500">AI-powered insights</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Recent Transactions</h2>
                        <a href="{{ route('owner.transactions') }}"
                            class="text-sm text-orange-500 hover:text-orange-600 font-semibold">View All</a>
                    </div>
                    <div class="space-y-4">
                        @foreach ($recentTransactions as $transaction)
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($transaction->kasir->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $transaction->kasir->name }}</p>
                                        <p class="text-xs text-gray-500">#{{ $transaction->id }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">Rp
                                        {{ number_format($transaction->total_harga, 0, ',', '.') }}</p>
                                    <span
                                        class="inline-block px-2 py-1 text-xs rounded-full 
                                        @if ($transaction->status == 'selesai') bg-green-100 text-green-600
                                        @elseif($transaction->status == 'success') bg-green-100 text-green-600
                                        @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-600
                                        @else bg-red-100 text-red-600 @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</x-app-layout>
