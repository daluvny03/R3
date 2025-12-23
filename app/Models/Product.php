<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'categories_id',
        'kategori', // Tetap ada untuk backward compatibility
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
        'gambar'
    ];

    /**
     * Relasi: Produk milik kategori
     */
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    /**
     * Relasi: Produk memiliki banyak item transaksi
     */
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Relasi: Produk memiliki banyak penyesuaian stok
     */
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjusments::class);
    }

    /**
     * Scope: Stok rendah (kurang dari 10)
     */
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stok', '<', $threshold);
    }

    /**
     * Accessor: Margin keuntungan
     */
    public function getProfitMarginAttribute()
    {
        if ($this->harga_beli == 0) return 0;
        return (($this->harga_jual - $this->harga_beli) / $this->harga_beli) * 100;
    }

    /**
     * Accessor: Status stok
     */
    public function getStockStatusAttribute()
    {
        if ($this->stok == 0) return 'Habis';
        if ($this->stok < 10) return 'Rendah';
        if ($this->stok < 50) return 'Sedang';
        return 'Aman';
    }

    /**
     * Accessor: Badge color untuk status stok
     */
    public function getStockBadgeAttribute()
    {
        return match($this->stock_status) {
            'Habis' => 'bg-red-100 text-red-800',
            'Rendah' => 'bg-yellow-100 text-yellow-800',
            'Sedang' => 'bg-blue-100 text-blue-800',
            'Aman' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}