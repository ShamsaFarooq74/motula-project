<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SubCatSubChild extends Model
{
    //
    protected $table = "sub_cat_sub_childs";
    public function file(){
        return $this->hasMany(Files::class,'sub_child_id');
    }
}
