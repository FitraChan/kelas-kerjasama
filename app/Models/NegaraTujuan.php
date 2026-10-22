<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NegaraTujuan extends Model
{
    use SoftDeletes;

    protected $table = 'tb_negara_tujuan';

    protected $fillable = [
        'nama_negara',
    ];
}