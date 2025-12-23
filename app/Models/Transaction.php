<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kasir_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_status',
        'midtrans_qr_string',
        'midtrans_expired_at',
        'tanggal_transaksi',
        'total_harga',
        'metode_pembayaran',
        'status'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'midtrans_expired_at' => 'datetime',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_transaksi', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal_transaksi', now()->month)
                    ->whereYear('tanggal_transaksi', now()->year);
    }

    public function scopeLastDays($query, $days = 7)
    {
        return $query->where('tanggal_transaksi', '>=', now()->subDays($days));
    }
    public function isQrisExpired()
    {
        if (!$this->midtrans_expired_at) {
            return false;
        }
        return now()->isAfter($this->midtrans_expired_at);
    }

    public function isPending()
    {
        return $this->midtrans_status === 'pending';
    }

    public function isSuccess()
    {
        return in_array($this->midtrans_status, ['capture', 'settlement']);
    }
}
