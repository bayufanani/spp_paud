<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class daful extends Model
{
    use SoftDeletes;

    protected $table = 'daful';

    protected $fillable = [
        'keterangan',
        'jumlah'
    ];
}
