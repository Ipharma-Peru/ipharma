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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30);
            $table->string('descripcion', 250);
            $table->boolean('activo')->default(true);
            $table->boolean('fraccionable')->default(false);
            $table->boolean('deleted')->default(false);
            $table->foreignId('presentation_id')->constrained()->restrictOnDelete();
            $table->foreignId('laboratory_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_subclass_id')->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('products');
    }
};
