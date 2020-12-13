<?php

namespace App\Models;

use App\models\daful;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'kelas_id',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'nama_wali',
        'telp_wali',
        'pekerjaan_wali',
        'is_yatim'
    ];
    protected $appends = ['is_daful_lunas'];

    public function kelas()
    {
        return $this->hasOne('App\Models\Kelas', 'id', 'kelas_id');
    }

    public function transaksi()
    {
        return $this->hasMany('App\Models\Transaksi', 'siswa_id', 'id');
    }

    public function role()
    {
        return $this->hasMany('App\Models\Role', 'siswa_id', 'id');
    }

    public function tabungan()
    {
        return $this->hasMany('App\Models\Tabungan', 'siswa_id', 'id');
    }

    public function transaksi_daful()
    {
        return $this->hasMany('App\Models\transaksi_daful', 'siswa_id', 'id');
    }

    public function getIsDafulLunasAttribute()
    {
        $periode = $this->kelas->periode;
        $daful = daful::where([
            'periode_id' => $periode->id,
            'deleted_at' => null
        ])->first();
        $transaksi = transaksi_daful::where([
            'siswa_id' => $this->id,
            'lunas' => 1,
            'daful_id' => $daful->id
        ]);
        if ($transaksi->count() > 0) {
            return true;
        }
        return false;
    }
}
