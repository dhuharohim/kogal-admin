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
        Schema::create('shipment_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_header_id')->nullable();
            $table->date('date')->index()->nullable();
            $table->time('time')->index()->nullable();
            $table->string('location_history')->nullable();
            $table->string('status')->index()->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('shipment_histories');
    }
};
