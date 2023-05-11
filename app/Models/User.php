<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    protected $table = 'user';
    use HasFactory;
    protected $fillable = ['username', 'password', 'accessToken'];
    public $timestamps = false;
}
