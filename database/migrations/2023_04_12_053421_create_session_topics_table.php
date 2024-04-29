<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_topics', function (Blueprint $table) {
            $table->id();
            $table->integer('session_id');
            // $table->foreign('session_id')->references('id')->on('course_sessions')->onDelete('cascade')->onUpdate('cascade');
            $table->string('topic_name',255);
            $table->text('description');
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
        Schema::dropIfExists('session_topics');
    }
}
