<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = "files";
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function child()
    {
        return $this->belongsTo(SubCatChild::class, 'child_id');
    }
    public function subChild()
    {
        return $this->belongsTo(SubCatSubChild::class, 'sub_child_id');
    }

}
