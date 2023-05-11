<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Processor extends Model
{
    protected $table = 'processor';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'socketTypeId', 'cores', 'baseFrequency', 'maxFrequency', 'cacheMemory', 'tdp'];
    public $timestamps = false;
}
