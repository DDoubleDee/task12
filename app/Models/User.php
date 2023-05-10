<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    use HasFactory;
    protected $fillable = ['username', 'password', 'accessToken'];
    public $timestamps = false;
}
