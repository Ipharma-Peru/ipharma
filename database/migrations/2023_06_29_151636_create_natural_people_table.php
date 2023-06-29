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
        Schema::create('natural_people', function (Blueprint $table) {
            $table->id();
            $table->string('numero_documento', 15);
            $table->string('nombres', 50);
            $table->string('apellido_paterno', 40)->nullable();
            $table->string('apellido_materno', 40)->nullable();
            $table->string('fecha_nacimiento', 40)->nullable();
            $table->foreignId('person_id')->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('natural_people');
    }
};
