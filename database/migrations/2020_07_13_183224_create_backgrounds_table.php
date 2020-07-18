<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backgrounds', function (Blueprint $table) {
            $table->id()->autoIncrement()->unsigned();
            $table->unsignedInteger('user_id');
            $table->string('file_name')->nullable();
            $table->string('type')->nullable();
            $table->float('size')->nullable();
            $table->text('caption')->nullable();
            $table->string('temp_name')->nullable();

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
        Schema::dropIfExists('backgrounds');
    }
}
