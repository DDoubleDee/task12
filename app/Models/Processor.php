<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Processor extends Model
{
    protected $table = 'processor';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'socketTypeId', 'cores', 'baseFrequency', 'maxFrequency', 'cacheMemory', 'tdp'];
    public $timestamps = false;
}
