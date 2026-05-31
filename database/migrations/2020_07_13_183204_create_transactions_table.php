<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id()->autoIncrement()->unsigned();
            $table->string('name');
            $table->enum('direction', ['debit', 'credit']);
            $table->unsignedInteger('user_id');
            $table->float('quantity')->nullable();
            $table->float('price')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->text('comment')->nullable();
            $table->date('date')->nullable();
            $table->uuid('family_id');
            $table->unsignedInteger('month_year_id')->nullable();
            $table->enum('type', ['normal', 'blue_print', 'draft']);
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
        Schema::dropIfExists('transactions');
    }
}
