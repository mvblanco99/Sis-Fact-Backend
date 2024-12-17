<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salidas', function (Blueprint $table) {
            // Eliminar la relación anterior
            $table->dropForeign(['articulo_id']);
            $table->dropColumn('articulo_id'); // Si deseas eliminar la columna

            // Agregar la nueva relación
            $table->unsignedBigInteger('unidad_articulo_id'); // Agrega la nueva columna
            $table->foreign('unidad_articulo_id')->references('id')->on('unidad_articulos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('salidas', function (Blueprint $table) {
            // Revertir los cambios
            $table->dropForeign(['unidad_articulo_id']);
            $table->dropColumn('unidad_articulo_id'); // Si deseas eliminar la columna

            // Restaurar la relación anterior
            $table->unsignedBigInteger('articulo_id')->nullable();
            $table->foreign('articulo_id')->references('id')->on('articulos')->onDelete('cascade');
        });
    }
};
