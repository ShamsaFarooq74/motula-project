<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_quiz', function (Blueprint $table) {
            $table->id();
            $table->integer('topic_id');
            // $table->foreign('topic_id')->references('id')->on('session_topics')->onDelete('cascade')->onUpdate('cascade');
            $table->string('quiz_title',255);
            $table->integer('duration'); 
            $table->integer('passing_grade'); 
            $table->text('quiz_guidelines');
            $table->enum('is_active', ['0', '1'])->default('1');
            $table->enum('is_delete', ['0', '1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_quiz');
    }
}
