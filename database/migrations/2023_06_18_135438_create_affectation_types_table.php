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
        Schema::create('affectation_types', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 5);
            $table->string('descripcion', 20);
            $table->string('codigo_afectacion', 5);
            $table->string('tipo_afectacion', 10);
            $table->string('nombre_afectacion', 10);
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('affectation_types');
    }
};
