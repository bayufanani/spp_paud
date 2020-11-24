<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class transaksi_daful extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi_daful';

    protected $fillable = [
        'keterangan',
        'jumlah_bayar',
        'cicilan',
        'lunas'
    ];
}
