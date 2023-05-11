<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MachineHasStorageDevice extends Model
{
    protected $table = 'machinehasstoragedevice';
    use HasFactory;
    protected $fillable = ['machineId', 'storageDeviceId', 'amount'];
    public $timestamps = false;
}
