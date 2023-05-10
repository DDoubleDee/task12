<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $table = 'machine';
    use HasFactory;
    protected $fillable = ['name', 'description', 'imageUrl', 'motherboardId', 'processorId', 'ramMemoryId', 'ramMemoryAmount', 'graphicCardId', 'graphicCardAmount', 'powerSupplyId'];
    public $timestamps = false;
}
