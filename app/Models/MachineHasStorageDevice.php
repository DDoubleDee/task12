<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineHasStorageDevice extends Model
{
    protected $table = 'machinehasstoragedevice';
    use HasFactory;
    protected $fillable = ['machineId', 'storageDeviceId', 'amount'];
    public $timestamps = false;
}
