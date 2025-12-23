<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi: Kategori memiliki banyak produk
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope: Hanya kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor: Jumlah produk dalam kategori
     */
    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }
}