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
        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->id();
            $table->boolean('estado_facturacion');
            $table->string('codigo_error', 10);
            $table->text('mensaje_sunat');
            $table->string('nombre_xml', 60);
            $table->text('xml_base64');
            $table->text('cdr_base64');
            $table->boolean('deleted')->default(false);
            $table->foreignId('sale_id')->constrained()->restrictOnDelete();
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
        Schema::dropIfExists('invoice_statuses');
    }
};
