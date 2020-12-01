<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class transaksi_daful extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi_daful';
    protected $appends = ['jumlah_idr'];

    protected $fillable = [
        'keterangan',
        'jumlah_bayar',
        'cicilan',
        'lunas'
    ];

    public function getJumlahIdrAttribute()
    {
        return "IDR. " . format_idr($this->jumlah);
    }
    public function siswa()
    {
        return $this->hasOne('App\Models\Siswa', 'id', 'siswa_id');
    }
}
