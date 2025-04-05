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
            $table->enum('type', ['debit', 'credit']);
            $table->unsignedInteger('user_id');
            $table->float('quantity');
            $table->float('price');
            $table->unsignedInteger('category_id');
            $table->text('comment')->nullable();
            $table->date('date')->nullable();
            $table->uuid('family_id');
            $table->unsignedInteger('month_year_id')->nullable();
            $table->boolean('is_blue_print');
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
