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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('departamento_id')->default(4);
            $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir los cambios
            $table->dropForeign(['departamento_id']);
            $table->dropColumn('departamento_id'); // Si deseas eliminar la columna
        });
    }
};
