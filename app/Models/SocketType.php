<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocketType extends Model
{
    protected $table = 'sockettype';
    use HasFactory;
    protected $fillable = ['name'];
    public $timestamps = false;
}
