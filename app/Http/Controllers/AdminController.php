<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Categories;
use App\Models\StockAdjusments;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==================== DASHBOARD ====================
    public function index()
    {
        $totalProduk = Product::count();
        $totalKategori = Categories::count();
        $totalStokMenipis = Product::where('stok', '<', 10)->count();
        $totalTransaksi = Transaction::count();
        $lowStockProducts = Product::LowStock(10)->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalKategori',
            'totalStokMenipis',
            'totalTransaksi',
            'lowStockProducts'
        ));
    }

    // ==================== KELOLA PRODUK ====================
    public function products()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Categories::active()->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function createProduct()
    {
        $categories = Categories::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'categories_id' => 'nullable|exists:categories,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        $kategori = Categories::where('id', $request->categories_id)
        ->value('nama_kategori');
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }
        $validated['kategori'] = $kategori;
        Product::create($validated);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduct(Product $product)
    {
        $categories = Categories::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'categories_id' => 'nullable|exists:categories,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }

    // ==================== KELOLA KATEGORI ====================
    public function categories()
    {
        $categories = Categories::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Set default true jika tidak ada checkbox
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Categories::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function editCategory(Categories $category)
    {
        $category->loadCount('products');
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Categories $category)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $category->id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Set default false jika tidak ada checkbox
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $category->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyCategory(Categories $category)
    {
        // Set category_id produk menjadi null sebelum hapus kategori
        $category->products()->update(['categories_id' => null]);
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus!');
    }

    // ==================== KELOLA STOK ====================
    public function stocks()
    {
        $products = Product::with('category')->get();
        $stockAdjustments = StockAdjusments::with(['product', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.stock.index', compact('products', 'stockAdjustments'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:masuk,keluar,koreksi',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        DB::transaction(function() use ($product, $validated, $request) {
            $stokSebelum = $product->stok;
            
            // Hitung stok baru berdasarkan tipe
            $stokBaru = match($validated['tipe']) {
                'masuk' => $stokSebelum + $validated['jumlah'],
                'keluar' => max(0, $stokSebelum - $validated['jumlah']),
                'koreksi' => $validated['jumlah'] // Untuk koreksi, jumlah = stok baru
            };

            // Update stok produk
            $product->update(['stok' => $stokBaru]);

            // Catat adjustment
            StockAdjusments::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['tipe'] == 'koreksi' 
                    ? ($stokBaru - $stokSebelum) 
                    : $validated['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokBaru,
                'keterangan' => $validated['keterangan']
            ]);
        });

        return redirect()->route('admin.stocks')->with('success', 'Stok berhasil diperbarui!');
    }

    // ==================== KELOLA TRANSAKSI ====================
    public function transactions(Request $request)
    {
        
        $query = Transaction::with(['user', 'items.product', 'payments']);

    if ($request->filled('start_date')) {
        $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
    }

    $transactions = $query
        ->latest('tanggal_transaksi')
        ->paginate(15);
    $transactions->appends($request->query());


        $statistics = [
            'total_transaksi' => Transaction::count(),
            'total_pendapatan' => Transaction::sum('total_harga'),
            'transaksi_hari_ini' => Transaction::whereDate('tanggal_transaksi', today())->count(),
            'pendapatan_hari_ini' => Transaction::whereDate('tanggal_transaksi', today())->sum('total_harga')
        ];

        return view('admin.transactions.index', compact('transactions', 'statistics'));
    }

    public function showTransaction(Transaction $transaction)
{
    return response()->json(
        $transaction->load([
            'items.product',
            'payments',
            'user'
        ])
    );
}

    public function deleteTransaction(Transaction $transaction)
    {
        DB::transaction(function() use ($transaction) {
            // Kembalikan stok produk
            foreach ($transaction->items as $item) {
                $item->product->increment('stok', $item->jumlah);
            }

            // Hapus transaksi (cascade akan hapus items dan payments)
            $transaction->delete();
        });

        return redirect()->route('admin.transactions')->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan!');
    }

    // ==================== REPORTS ====================
    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $transactions = Transaction::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->with(['items.product', 'payments'])
            ->get();

        $totalPenjualan = $transactions->sum('total_harga');
        $totalTransaksi = $transactions->count();
        
        $topProducts = TransactionItem::with('product')
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })
            ->select('product_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        return view('admin.reports', compact('transactions', 'totalPenjualan', 'totalTransaksi', 'topProducts', 'startDate', 'endDate'));
    }
}