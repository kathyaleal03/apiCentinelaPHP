<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fotosreportes', function (Blueprint $table) {
            $table->id();
            $table->string('url_foto');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fotosreportes');
    }
};
