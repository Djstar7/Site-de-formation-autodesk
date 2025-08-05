<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlansModel extends Model
{
    use HasFactory;
    protected $table = 'plans';
    protected $fillable = [
        'title_plans',
        'description_plans',
        'price_plans',
        'image_plans',
        'file_plans',
    ];
}