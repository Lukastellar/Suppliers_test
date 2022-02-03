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

    public function condition(){
        return $this->hasOne(Condition::class,'id','condition_id')->select(['id', 'name']);
    }

    public function category(){
        return $this->hasOne(Category::class,'id','category_id')->select(['id', 'name']);
    }
}
