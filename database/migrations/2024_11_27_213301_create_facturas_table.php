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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('nota_entrega',100);
            $table->datetime('fec_emis');
            $table->datetime('fec_vcto');
            $table->text('empresa');
            $table->decimal('total_factura');
            $table->unsignedBigInteger('user_id');
            $table->enum('procesada',[1,2])->comment("1 => PROCESADA, 2=> NO PROCESADA");
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
