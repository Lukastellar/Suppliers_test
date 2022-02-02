<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'days_valid',
        'priority',
        'part_number',
        'part_desc',
        'quantity',
        'price',
        'condition_id',
        'category_id'
    ];
}
