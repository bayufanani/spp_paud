<?php

namespace App\models;

use App\transaksi_daful;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class daful extends Model
{
    use SoftDeletes;

    protected $table = 'daful';
    protected $appends = ['jumlah_idr'];

    protected $fillable = [
        'keterangan',
        'jumlah'
    ];

    public function getJumlahIdrAttribute()
    {
        return format_idr($this->jumlah);
    }
}
