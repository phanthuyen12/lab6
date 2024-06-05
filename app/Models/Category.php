<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categorys';

    protected $fillable = [
        'created_at',
        'updated_at',
        'images',
        'name_category'
    ];
    public function products(){
        return $this->hasMany(Product::class,'id_category');
    }
}
