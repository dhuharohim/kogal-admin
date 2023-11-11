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
        Schema::create('invoice_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_header_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('invoice_number')->nullable()->index();
            $table->string('shipment_number')->nullable()->index();
            // shipper details
            $table->string('shipper_name')->nullable();
            $table->string('shipper_phone')->nullable();
            $table->text('shipper_address')->nullable();
            $table->string('shipper_email')->nullable();
            // receiver details
            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->text('receiver_address')->nullable();
            $table->string('receiver_email')->nullable();
            // details
            $table->unsignedBigInteger('type_of_shipment_id')->nullable()->index();
            $table->unsignedBigInteger('payment_id')->nullable()->index();
            $table->unsignedBigInteger('carrier_id')->nullable()->index();
            $table->time('departure_time')->nullable()->index();
            $table->unsignedBigInteger('destination_id')->nullable()->index();
            $table->string('courier')->nullable();
            $table->unsignedBigInteger('mode_id')->nullable()->index();
            $table->decimal('total_freight', 28, 2)->nullable();
            $table->string('carrier_ref')->nullable()->index();
            $table->unsignedBigInteger('origin_id')->nullable()->index();
            $table->dateTime('pickup_date_time')->nullable()->index();
            $table->dateTime('expected_delivery_date_time')->nullable()->index();

            $table->string('status')->nullable()->index();
            $table->string('remarks')->nullable()->index();

            // total
            $table->decimal('total_vol_weight', 28, 2)->nullable();
            $table->decimal('total_vol', 28, 2)->nullable();
            $table->decimal('total_actual_weight', 28, 2)->nullable();
            $table->decimal('total_price', 28, 2)->nullable();
            $table->decimal('vat', 28, 2)->nullable();
            $table->decimal('total_price_vat', 28, 2)->nullable();
            $table->unsignedBigInteger('updated_by')->reference('id')->on('users')->nullable();

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
        Schema::dropIfExists('invoice_shipments');
    }
};
