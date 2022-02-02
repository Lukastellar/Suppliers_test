<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'days_valid',
        'priority',
        'part_number',
        'part_desc',
        'quantity',
        'price',
        'condition',
        'category'
    ];
}
