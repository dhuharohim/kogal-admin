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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id')->index()->nullable();
            $table->string('sku')->index()->nullable();
            $table->string('item_name');
            $table->string('description');
            $table->decimal('price', 28,2)->nullable();
            $table->decimal('length', 28,2)->nullable();
            $table->decimal('width', 28,2)->nullable();
            $table->decimal('height', 28,2)->nullable();
            $table->decimal('weight', 28,2)->nullable();
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('items');
    }
};
