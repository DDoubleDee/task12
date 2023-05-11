<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RAMMemoryType extends Model
{
    protected $table = 'rammemorytype';
    use HasFactory;
    protected $fillable = ['name'];
    public $timestamps = false;
}
