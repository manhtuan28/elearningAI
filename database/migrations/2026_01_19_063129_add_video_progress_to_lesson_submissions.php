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
        Schema::table('lesson_submissions', function (Blueprint $table) {
            $table->double('video_progress')->default(0)->after('status');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_submissions', function (Blueprint $table) {
            //
        });
    }
};
