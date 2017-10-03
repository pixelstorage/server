<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImageInitialMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id')->index();
            $table->string('public')->unique()->required();
            $table->string('secret')->required();
            $table->enum('status', ['new', 'uploading', 'uploaded', 'failed'])->default('new');
            $table->float('height')->default(0);
            $table->float('width')->default(0);
            $table->bigInteger('size');
            $table->string('mime');
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
        Schema::dropIfExists('images');
    }
}
