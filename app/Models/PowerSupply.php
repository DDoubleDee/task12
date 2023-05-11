<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PowerSupply extends Model
{
    protected $table = 'powersupply';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'potency', 'badge80Plus'];
    public $timestamps = false;
}
