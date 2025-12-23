<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjusments extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'tipe',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan'
    ];

    /**
     * Relasi: Penyesuaian stok milik produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi: Penyesuaian stok dilakukan oleh user (admin)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    /**
     * Accessor: Badge color untuk tipe
     */
    public function getTipeBadgeAttribute()
    {
        return match($this->tipe) {
            'masuk' => 'bg-green-100 text-green-800',
            'keluar' => 'bg-red-100 text-red-800',
            'koreksi' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}