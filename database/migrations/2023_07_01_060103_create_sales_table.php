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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('correlativo');
            $table->date('fecha_emision');
            $table->decimal('op_gravadas', 8, 2)->nullable();
            $table->decimal('op_exoneradas', 8, 2)->nullable();
            $table->decimal('op_inafectas', 8, 2)->nullable();
            $table->decimal('total_igv', 6, 2)->nullable();
            $table->decimal('total', 8, 2)->nullable();
            $table->bigInteger('user_id');
            $table->boolean('deleted')->default(false);
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('currency_id')->constrained()->restrictOnDelete();
            $table->foreignId('tax_rate_id')->constrained()->restrictOnDelete();
            $table->foreignId('invoice_series_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('sale_status_id')->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('sales');
    }
};
