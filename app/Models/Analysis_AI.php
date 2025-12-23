<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Analysis_AI extends Model
{
    use HasFactory;

    protected $table = 'ai_analysis';

    protected $fillable = [
        'type',
        'hasil'
    ];

    protected $casts = [
        'hasil' => 'array', // Auto encode/decode JSON
        'created_at' => 'datetime',
    ];

    /**
     * Get human-readable type name
     */
    public function getTypeNameAttribute()
    {
        $types = [
            'sales_prediction' => 'Prediksi Penjualan',
            'stock_recommendation' => 'Rekomendasi Stok',
            'trends' => 'Analisis Tren',
            'anomaly' => 'Deteksi Anomali',
            'customer' => 'Insight Pelanggan',
            'comprehensive' => 'Analisis Menyeluruh'
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Get formatted created date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    /**
     * Scope: Filter by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Recent first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}