<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            // Foreign key for month_year_id
            $table->foreign('month_year_id')
                ->references('id')
                ->on('month_years')
                ->onDelete('set null');

            // Foreign key for user_id
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Foreign key for parent_id (self-referential)
            $table->foreign('parent_id')
                ->references('id')
                ->on('todos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropForeign(['month_year_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['parent_id']);
        });
    }
};
