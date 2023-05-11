<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Motherboard extends Model
{
    protected $table = 'motherboard';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'socketTypeId', 'ramMemoryTypeId', 'ramMemorySlots', 'maxTdp', 'sataSlots', 'm2Slots', 'pciSlots'];
    public $timestamps = false;
}
