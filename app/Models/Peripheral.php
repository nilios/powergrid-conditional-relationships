<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peripheral extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'manufacturer',
        'base_cost'
    ];
}
