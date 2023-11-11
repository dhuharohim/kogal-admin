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
        Schema::create('invoice_shipment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_shipment_id')->reference('id')->on('invoice_shipments');
            $table->unsignedBigInteger('item_id')->nullable();  
            $table->string('item_name')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('price', 28,2)->nullable();
            $table->text('description')->nullable();
            $table->decimal('length', 28,2)->nullable();
            $table->decimal('width', 28,2)->nullable();
            $table->decimal('height', 28,2)->nullable();
            $table->decimal('weight', 28,2)->nullable();
            $table->decimal('total_weight', 28,2)->nullable();
            $table->decimal('total_price', 28,2)->nullable();
            $table->softDeletes()->index();
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
        Schema::dropIfExists('invoice_shipment_items');
    }
};
