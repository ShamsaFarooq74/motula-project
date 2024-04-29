<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SubCatChild extends Model
{
    //
    protected $table = "sub_cat_childs";
    public function file(){
        return $this->hasMany(Files::class,'child_id');
    }
}
