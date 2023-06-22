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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('precio_unidad', 4, 2)->nullable();
            $table->decimal('precio_blister', 4, 2)->nullable();
            $table->decimal('precio_caja', 4, 2);
            $table->boolean('activo')->default(true);
            $table->boolean('deleted')->default(false);
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('product_prices');
    }
};
