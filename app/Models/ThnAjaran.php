<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThnAjaran extends Model
{
    protected $table = 'tb_thn_ajaran';
  //  protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'thn_ajaran',
        'isaktiv'
    ];
}
