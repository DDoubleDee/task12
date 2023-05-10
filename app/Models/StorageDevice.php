<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageDevice extends Model
{
    protected $table = 'storagedevice';
    use HasFactory;
    protected $fillable = ['name', 'imageUrl', 'brandId', 'storageDeviceType', 'size', 'storageDeviceInterface'];
    public $timestamps = false;
}
