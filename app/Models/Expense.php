<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kategori',
        'jumlah',
        'metode_bayar',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2'
    ];

    public static function getKategoriOptions()
    {
        return [
            'gaji' => 'Gaji Karyawan',
            'listrik' => 'Listrik',
            'sewa' => 'Sewa Tempat',
            'pajak' => 'Pajak',
            'perlengkapan' => 'Perlengkapan',
            'lain' => 'Lain-lain'
        ];
    }

    public static function getMetodeBayarOptions()
    {
        return [
            'kas' => 'Kas',
            'bank' => 'Bank'
        ];
    }
}