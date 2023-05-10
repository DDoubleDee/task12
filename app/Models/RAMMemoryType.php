<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RAMMemoryType extends Model
{
    protected $table = 'rammemorytype';
    use HasFactory;
    protected $fillable = ['name'];
    public $timestamps = false;
}
