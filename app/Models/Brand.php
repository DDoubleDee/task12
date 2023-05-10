<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';
    use HasFactory;
    protected $fillable = ['name'];
    public $timestamps = false;
}
