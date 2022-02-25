<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autores_has_libros', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('id_autor');
            $table->unsignedBigInteger('isbn');
            $table->foreign('id_autor')->references('id')->on('autores')->onDelete('cascade');
            $table->foreign('isbn')->references('isbn')->on('libros')->onDelete('cascade');
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
        Schema::dropIfExists('autores_has_libros');
    }
};
