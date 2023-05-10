<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PowerSupply extends Model
{
    protected $table = 'powersupply';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'potency', 'badge80Plus'];
    public $timestamps = false;
}
