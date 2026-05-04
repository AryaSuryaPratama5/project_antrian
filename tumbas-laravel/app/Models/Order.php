<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'nomor_meja',
        'nama_pelanggan',
        'jenis_pesanan',
        'detail_pesanan',
        'detail_json',
        'total_harga',
        'metode_bayar',
        'status_bayar',
        'status_pelayanan',
        'estimasi_menit',
    ];

    protected $casts = [
        'detail_json' => 'array',
    ];
}
