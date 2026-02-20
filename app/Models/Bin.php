<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    protected $fillable = ['name', 'type', 'current_weight', 'max_capacity', 'total_points'];
}
