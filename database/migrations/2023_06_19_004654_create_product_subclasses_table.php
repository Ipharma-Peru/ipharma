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
        Schema::create('product_subclasses', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20);
            $table->string('descripcion', 50);
            $table->boolean('activo')->default(true);
            $table->foreignId('product_class_id')->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('product_subclasses');
    }
};
