<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GraphicCard extends Model
{
    protected $table = 'graphiccard';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'memorySize', 'memoryType', 'minimumPowerSupply', 'supportMultiGpu'];
    public $timestamps = false;
}
