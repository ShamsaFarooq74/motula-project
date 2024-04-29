<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TopicQuiz extends Model
{
    protected $table = "sub_categories";
    public function files(){
        return $this->hasMany(QuizQuestions::class,'sub_category_id');
    }
    public function Category()
    {
        return $this->belongsTo(SessionTopics::class, 'category_id');
    }
}
