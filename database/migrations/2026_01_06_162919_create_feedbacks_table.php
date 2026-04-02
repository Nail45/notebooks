<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('feedbacks', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('author')->nullable();
      $table->timestamp('date')->useCurrent();
      $table->integer('rating');
      $table->text('advantage')->nullable();
      $table->text('disadvantages')->nullable();
      $table->text('summary')->nullable();
      $table->unsignedBigInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->unsignedBigInteger('notebook_id');
      $table->foreign('notebook_id')->references('id')->on('notebooks')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('feedbacks');
  }
};
