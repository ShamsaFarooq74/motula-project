<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestions extends Model
{
    protected $table = "files";
    public function subCategory()
    {
        return $this->belongsTo(TopicQuiz::class, 'sub_category_id');
    }

}
