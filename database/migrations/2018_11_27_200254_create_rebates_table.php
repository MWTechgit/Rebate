<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRebatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rebates', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('rebate_type_id')->unsigned();
            $table->integer('partner_id')->unsigned();

            $table->integer('fy_year')->index();
            $table->string('name')->index();
            $table->integer('remaining')->default(0)->unsigned();
            $table->integer('inventory')->default(0)->unsigned();
            $table->string('value', 10);
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('rebate_type_id')
                ->references('id')
                ->on('rebate_types')
                ->onDelete('cascade')
            ;

            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
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
        Schema::dropIfExists('rebates');
    }
}
