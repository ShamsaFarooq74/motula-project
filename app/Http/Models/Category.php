<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";
    public function sub_category(){
        return $this->hasMany(SubCategory::class,'category_id');
    }
    public function file(){
        return $this->hasMany(Files::class,'category_id');
    }

}
