<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $fillable = [
        'created_at',
        'updated_at',
        'name_products',
        'price_product',
        'img_product',
        'id_category',
    ] ;
    public function category(){
        return $this->belongsTo(Category::class,'id_category');
    }
}
