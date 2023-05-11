<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StorageDevice extends Model
{
    protected $table = 'storagedevice';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'storageDeviceType', 'size', 'storageDeviceInterface'];
    public $timestamps = false;
}
