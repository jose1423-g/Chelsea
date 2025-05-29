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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('rfc');
            $table->string('razon_social');
            $table->string('nombre_comercial');
            $table->string('representante_legal');
            $table->string('antiguedad');
            $table->string('afluencia');
            $table->string('numero_colaboradores');
            $table->string('pdf_colaboradores');
            $table->string('pdf_cif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
