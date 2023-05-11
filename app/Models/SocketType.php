<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocketType extends Model
{
    protected $table = 'sockettype';
    use HasFactory;
    protected $fillable = ['name'];
    public $timestamps = false;
}
