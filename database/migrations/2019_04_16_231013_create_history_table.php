<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->unsigned();
            $table->string('line_one');
            $table->string('line_two')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postcode');
            $table->string('account_number');
            $table->string('full_name');
            $table->string('email');

            $table->string('partner');
            $table->dateTime('submitted_at');
            $table->string('rebate_code');
            $table->string('status');

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
        Schema::dropIfExists('history');
    }
}
