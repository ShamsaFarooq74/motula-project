<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = "sub_categories";
    public function file(){
        return $this->hasMany(Files::class,'sub_category_id');
    }
    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
