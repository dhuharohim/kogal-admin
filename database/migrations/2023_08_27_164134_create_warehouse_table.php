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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code_warehouse')->nullable()->index();
            $table->string('name_warehouse')->nullable()->index();
            $table->string('street')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('province')->nullable()->index();
            $table->string('postal_code')->nullable();
            $table->string('capacity')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('email_warehouse')->nullable();
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
        Schema::dropIfExists('warehouse');
    }
};
