<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RAMMemory extends Model
{
    protected $table = 'rammemory';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'ramMemoryTypeId', 'size', 'frequency'];
    public $timestamps = false;
}
