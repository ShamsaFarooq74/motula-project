<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizOptionsDragDropTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_options_drag_drop', function (Blueprint $table) {
            $table->id();
            $table->integer('quiz_id');
            // $table->foreign('quiz_id')->references('id')->on('topic_quiz')->onDelete('cascade')->onUpdate('cascade');
            $table->text('statement');
            $table->string('correct_answer',255); 
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
        Schema::dropIfExists('quiz_options_drag_drop');
    }
}
