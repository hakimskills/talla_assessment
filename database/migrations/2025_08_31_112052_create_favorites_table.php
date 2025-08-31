<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->unsignedBigInteger('artwork_id');
            $table->string('title')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('artwork_id');
            $table->unique(['user_id','artwork_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};
