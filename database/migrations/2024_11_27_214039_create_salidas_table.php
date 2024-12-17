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
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->datetime('fecha');
            $table->text('motivo');
            $table->unsignedBigInteger('articulo_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('departamento_id');
            $table->unsignedBigInteger('destinatario_id');
            $table->timestamps();
            $table->foreign('articulo_id')->references('id')->on('articulos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('destinatario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salidas');
    }
};
