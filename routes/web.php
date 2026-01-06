<?php

use \App\Exports\TransactionsExport;
use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\ReportController;
use \App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\OwnerFinancialController;
use App\Http\Controllers\ProfileController;
use App\Mail\TransactionReceipt;
use Illuminate\Support\Facades\Route;









/*
|--------------------------------------------------------------------------
| Web Routes - ERTIGA POS
|--------------------------------------------------------------------------
|
| Route structure:
| - Public routes (login, register)
| - Authenticated routes with role-based access
|   - Kasir: POS Interface & Transactions
|   - Admin: Product Management & Reports
|   - Owner: Dashboard, Analytics & Export
|
*/

// ========================================
// PUBLIC ROUTES
// ========================================

// Redirect root to login
Route::get('/', function () {
    return view('welcome');
});

// ========================================
// AUTHENTICATED ROUTES
// ========================================

Route::middleware(['auth', 'verified'])->group(function () {

    // ========================================
    // DASHBOARD REDIRECT (Role-based)
    // ========================================
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        // Redirect based on user role
        return match ($role) {
            'owner' => redirect()->route('owner.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('kasir.index'),
            default => abort(403, 'Role tidak dikenali')
        };
    })->name('dashboard');

    // ========================================
    // PROFILE ROUTES (All Roles)
    // ========================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // KASIR ROUTES (Role: kasir)
    // ========================================
    Route::middleware(['auth', 'role:kasir'])->post(
        '/kasir/transaction/store',
        [KasirController::class, 'store']
    )->name('kasir.transaction.store');
    // Kasir Routes
    Route::middleware(['auth', 'role:kasir'])->group(function () {
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/transaction/store', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/check-qris/{transaction}', [KasirController::class, 'checkQrisStatus'])->name('kasir.check-qris');
        Route::get('/kasir/receipt/{transaction}', [KasirController::class, 'printReceipt'])->name('kasir.receipt');
    });

    // Midtrans Webhook (tanpa auth)
    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
        // Main POS Interface
        Route::get('/', [KasirController::class, 'index'])->name('index');

        // Get Products (AJAX)
        Route::get('/products', [KasirController::class, 'getProducts'])->name('products');

        // Print Receipt (PDF)
        Route::get('/receipt/{id}', [KasirController::class, 'printReceipt'])->name('receipt');

        // Transaction History (optional)
        Route::get('/history', [KasirController::class, 'history'])->name('history');
    });

    // ==================== ADMIN ROUTES ====================
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // Kelola Produk
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

        // Kelola Kategori
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        // Kelola Stok
        Route::get('/stocks', [AdminController::class, 'stocks'])->name('stocks');
        Route::put('/stocks/{product}', [AdminController::class, 'updateStock'])->name('stocks.update');

        // Kelola Transaksi
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/{transaction}', [AdminController::class, 'showTransaction'])->name('transactions.show');
        Route::delete('/transactions/{transaction}', [AdminController::class, 'deleteTransaction'])->name('transactions.destroy');

        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });
    // ========================================
    // OWNER ROUTES (Role: owner)
    // ========================================
    // Owner Routes
    Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
        
        // Laporan Keuangan Routes
        Route::get('/laporan-keuangan', [OwnerController::class, 'laporanKeuangan'])->name('laporan-keuangan');
        Route::get('/laporan-keuangan/pdf', [OwnerController::class, 'generatePDF'])->name('laporan-keuangan.pdf');
        
        // Analisa AI Route
        Route::get('/analisa-ai', [OwnerController::class, 'analisaAI'])->name('analisa-ai');
        Route::post('/ai-analysis/{type}', [OwnerController::class, 'aiAnalysis'])->name('ai-analysis');
        // Transactions Route
        Route::get('/transactions', [OwnerController::class, 'transactions'])->name('transactions');
        
    });
    Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/financial', [OwnerFinancialController::class, 'index'])->name('financial.index');
    Route::get('/financial/expense/create', [OwnerFinancialController::class, 'createExpense'])->name('financial.expense.create');
    Route::post('/financial/expense', [OwnerFinancialController::class, 'storeExpense'])->name('financial.expense.store');
    Route::get('/financial/laba-rugi', [OwnerFinancialController::class, 'generateLabaRugi'])->name('financial.laba-rugi');
    Route::get('/financial/neraca', [OwnerFinancialController::class, 'generateNeraca'])->name('financial.neraca');
    Route::get('/financial/laporan-penjualan', [OwnerFinancialController::class, 'generateLaporanPenjualan'])->name('financial.laporan-penjualan');
    Route::get('/financial/export-pdf', [OwnerFinancialController::class, 'exportPDF'])->name('financial.export-pdf');
});

    // ========================================
    // SHARED ROUTES (All Authenticated Users)
    // ========================================

    // Search Products (AJAX)
    Route::get('/api/products/search', [KasirController::class, 'searchProducts'])->name('api.products.search');

    // Get Product by ID (AJAX)
    Route::get('/api/products/{id}', [KasirController::class, 'getProduct'])->name('api.products.show');

    // Notifications
    Route::get('/notifications', function () {
        return auth()->user()->notifications;
    })->name('notifications');

    // Mark notification as read
    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications->find($id)->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');
});

// ========================================
// AUTH ROUTES (Breeze)
// ========================================
require __DIR__ . '/auth.php';

// ========================================
// FALLBACK ROUTE (404)
// ========================================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

/*
|--------------------------------------------------------------------------
| API Routes (Optional - untuk mobile app future)
|--------------------------------------------------------------------------
*/
