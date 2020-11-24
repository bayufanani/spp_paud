<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class spp_bulan extends Model
{
    use SoftDeletes;

    protected $table = 'spp_bulan';

    protected $fillable = [
        'transaksi_id',
        'bulan'
    ];
}
