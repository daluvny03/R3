<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERTIGA POS - Sistem Kasir Modern untuk Bisnis Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-orange {
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fcd34d 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(251, 146, 60, 0.3);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .hero-image {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-white">

    <!-- Navigation -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 gradient-orange rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">E</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-800">ERTIGA POS</span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="#fitur" class="text-gray-600 hover:text-orange-500 font-medium">Fitur</a>
                    <a href="#keunggulan" class="text-gray-600 hover:text-orange-500 font-medium">Keunggulan</a>
                    <a href="#harga" class="text-gray-600 hover:text-orange-500 font-medium">Harga</a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="/login" class="text-gray-600 hover:text-orange-500 font-medium">Masuk</a>
                    <a href="/register"
                        class="gradient-orange text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">Coba
                        Gratis</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-16 gradient-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Kelola Bisnis Jadi Lebih <span class="text-orange-500">Mudah & Cepat</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        ERTIGA POS adalah sistem kasir modern dengan AI Analysis yang membantu bisnis Anda berkembang
                        lebih pesat. Mulai dari Rp 0 hari ini!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/register"
                            class="gradient-orange text-white px-8 py-4 rounded-xl font-bold text-lg hover:opacity-90 transition text-center">
                            Mulai Sekarang
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="hero-image relative z-10">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800"
                            alt="ERTIGA POS Dashboard" class="rounded-2xl shadow-2xl">
                    </div>
                    <div class="absolute -bottom-4 -left-4 w-72 h-72 bg-orange-200 rounded-full opacity-50 blur-3xl">
                    </div>
                    <div class="absolute -top-4 -right-4 w-72 h-72 bg-yellow-200 rounded-full opacity-50 blur-3xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Fitur Lengkap untuk <span class="text-orange-500">Semua Jenis Bisnis</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Dari kasir hingga analisis AI, semua yang Anda butuhkan dalam satu platform
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover">
                    <div class="w-14 h-14 gradient-orange rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Kasir Modern</h3>
                    <p class="text-gray-600 mb-4">Interface kasir yang mudah digunakan dengan fitur lengkap untuk
                        transaksi cepat dan akurat.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Responsive design
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Cloud-based access
                        </li>
                    </ul>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover">
                    <div class="w-14 h-14 gradient-orange rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">AI Analysis</h3>
                    <p class="text-gray-600 mb-4">Analisis bisnis powered by AI untuk keputusan yang lebih cerdas dan
                        akurat.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Prediksi penjualan
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Stock recommendation
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Customer insights
                        </li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover">
                    <div class="w-14 h-14 gradient-orange rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Inventory Management</h3>
                    <p class="text-gray-600 mb-4">Kelola stok produk dengan mudah, real-time, dan terintegrasi dengan
                        kasir.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Real-time stock update
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Low stock alerts
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Product categorization
                        </li>
                    </ul>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover">
                    <div class="w-14 h-14 gradient-orange rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Laporan Lengkap</h3>
                    <p class="text-gray-600 mb-4">Dashboard analitik lengkap dengan export PDF & Excel untuk laporan
                        bisnis.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Sales reports
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Export PDF/Excel
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Chart visualization
                        </li>
                    </ul>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover">
                    <div class="w-14 h-14 gradient-orange rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Multi-Role Access</h3>
                    <p class="text-gray-600 mb-4">Kelola tim dengan role Owner, Admin, dan Kasir dengan hak akses
                        berbeda.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            3 role management
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Permission control
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Activity tracking
                        </li>
                    </ul>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover">
                    <div class="w-14 h-14 gradient-orange rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Mobile Responsive</h3>
                    <p class="text-gray-600 mb-4">Akses dari mana saja, desktop, tablet, atau smartphone dengan
                        tampilan optimal.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            Cross-platform
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span>Optimized for tablet & mobile</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span>Touch-friendly POS UI</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span>Fast loading performance</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="keunggulan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Kenapa <span class="text-orange-500">ERTIGA POS?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Lebih dari sekadar sistem kasir, partner bisnis yang membantu Anda berkembang
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600" alt="Dashboard"
                        class="rounded-2xl shadow-xl">
                </div>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div
                            class="flex-shrink-0 w-12 h-12 gradient-orange rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">1</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Setup Cepat 5 Menit</h3>
                            <p class="text-gray-600">Tidak perlu instalasi ribet. Daftar, login, dan langsung bisa
                                digunakan. Mudah untuk pemula sekalipun.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div
                            class="flex-shrink-0 w-12 h-12 gradient-orange rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">2</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Hemat Biaya Operasional</h3>
                            <p class="text-gray-600">Tidak perlu beli hardware mahal. Cukup gunakan laptop, tablet,
                                atau smartphone yang sudah ada.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div
                            class="flex-shrink-0 w-12 h-12 gradient-orange rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">3</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Data Aman & Backup Otomatis</h3>
                            <p class="text-gray-600">Data tersimpan di cloud dengan enkripsi. Backup otomatis setiap
                                hari, tidak perlu khawatir kehilangan data.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div
                            class="flex-shrink-0 w-12 h-12 gradient-orange rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">4</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Support 24/7</h3>
                            <p class="text-gray-600">Tim support kami siap membantu kapan saja via WhatsApp, email,
                                atau live chat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Harga <span class="text-orange-500">Terjangkau</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Pilih paket yang sesuai dengan kebutuhan bisnis Anda
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-5xl font-bold text-gray-900">Rp 0</span>
                            <span class="text-gray-600 ml-2">/bulan</span>
                        </div>
                        <p class="text-gray-600">Cocok untuk bisnis baru</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">1 User Kasir</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">100 Produk</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Unlimited Transaksi</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Laporan Basic</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Email Support</span>
                        </li>
                    </ul>
                    <a href="/register"
                        class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-xl font-bold transition">
                        Mulai Gratis
                    </a>
                </div>

                <!-- Pro Plan (Popular) -->
                <div
                    class="bg-white rounded-2xl shadow-2xl p-8 border-4 border-orange-500 relative transform scale-105">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-orange-500 text-white px-4 py-1 rounded-full text-sm font-bold">PALING
                            POPULER</span>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Professional</h3>
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-5xl font-bold text-orange-500">Rp 9M</span>
                            <span class="text-gray-600 ml-2">/bulan</span>
                        </div>
                        <p class="text-gray-600">Untuk bisnis yang berkembang</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">3 User (Owner/Admin/Kasir)</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Unlimited Produk</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Unlimited Transaksi</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700"><strong>AI Analysis</strong> ⭐</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Laporan Advanced</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Export PDF/Excel</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Priority Support 24/7</span>
                        </li>
                    </ul>
                    <a href="/register"
                        class="block w-full text-center gradient-orange text-white py-3 rounded-xl font-bold hover:opacity-90 transition">
                        Pilih Paket Ini
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                        <div class="flex items-center justify-center mb-4">
                            <span class="text-5xl font-bold text-gray-900">Custom</span>
                        </div>
                        <p class="text-gray-600">Solusi untuk enterprise</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Unlimited Users</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Multi-Store Support</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Semua Fitur Pro</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Custom Integration</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">Dedicated Account Manager</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                            <span class="text-gray-700">SLA Guarantee</span>
                        </li>
                    </ul>
                    <a href="#kontak"
                        class="block w-full text-center bg-gray-800 hover:bg-gray-900 text-white py-3 rounded-xl font-bold transition">
                        Hubungi Sales
                    </a>
                </div>
            </div>

            <div class="text-center mt-12">
                <p class="text-gray-600">Semua paket termasuk free trial 30 hari. Tidak perlu kartu kredit!</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-orange">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Siap Tingkatkan Bisnis Anda?
            </h2>
            <p class="text-xl text-white/90 mb-8">
                Bergabunglah dengan ribuan bisnis yang telah berkembang bersama ERTIGA POS
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register"
                    class="bg-white text-orange-500 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition">
                    Mulai Sekarang
                </a>
            </div>
            <p class="text-white/80 mt-6 text-sm">Tidak perlu kartu kredit • Setup 5 menit • Support 24/7</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 gradient-orange rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">E</span>
                        </div>
                        <span class="text-2xl font-bold">ERTIGA POS</span>
                    </div>
                    <p class="text-gray-400">Sistem kasir modern untuk bisnis masa depan.</p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Produk</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#fitur" class="hover:text-white">Fitur</a></li>
                        <li><a href="#harga" class="hover:text-white">Harga</a></li>
                        <li><a href="#" class="hover:text-white">Integrasi</a></li>
                        <li><a href="#" class="hover:text-white">API</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                        <li><a href="#" class="hover:text-white">Karir</a></li>
                        <li><a href="#" class="hover:text-white">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-white">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white">Status System</a></li>
                        <li><a href="#" class="hover:text-white">WhatsApp Support</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">© 2024 ERTIGA POS. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>