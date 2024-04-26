<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('application_id')->unsigned();

            $table->string('property_type', 50)->nullable();
            $table->string('building_type', 50)->nullable();
            $table->string('subdivision_or_development', 45)->nullable();
            $table->string('bathrooms', 15)->nullable();
            $table->string('toilets', 15)->nullable();
            $table->integer('full_bathrooms')->nullable();
            $table->integer('half_bathrooms')->nullable();
            $table->string('year_built', 35)->nullable();
            $table->string('original_toilet', 15)->nullable();
            $table->string('gallons_per_flush')->nullable();
            $table->string('occupants', 25)->default(0);
            $table->string('years_lived', 25);

            $table->timestamps();

            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
