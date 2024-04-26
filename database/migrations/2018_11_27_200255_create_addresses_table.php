<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('addressable_id')->unsigned();
            $table->string('addressable_type', 50);

            $table->string('line_one');
            $table->string('line_two')->nullable();
            $table->string('city', 100);
            $table->string('state', 30);
            $table->string('postcode', 30);
            $table->string('country', 100)->default('US')->nullable();
            $table->string('lat', 80)->nullable();
            $table->string('lng', 80)->nullable();

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
        Schema::dropIfExists('addresses');
    }
}
