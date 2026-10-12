<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "t_user";
    protected $primaryKey='str_user_name';
    protected $fillable=['str_password'];
}