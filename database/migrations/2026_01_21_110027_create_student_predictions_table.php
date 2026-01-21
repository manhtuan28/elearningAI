<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('student_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->float('avg_score')->default(0);      // Điểm trung bình
            $table->integer('login_count')->default(0);  // Số lần đăng nhập
            $table->float('completion_rate')->default(0); // Tỷ lệ hoàn thành
            $table->string('risk_level');                // Low, Medium, High
            $table->text('ai_recommendation')->nullable(); // Lời khuyên của AI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_predictions');
    }
};
