<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SessionTopics extends Model
{
    protected $table = "categories";
    public function sub_category(){
        return $this->hasMany(TopicQuiz::class,'category_id');
    }

}
