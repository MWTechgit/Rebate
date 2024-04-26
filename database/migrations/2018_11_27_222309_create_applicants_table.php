<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name', 45);
            $table->string('last_name', 45);
            $table->string('full_name', 90)->nullable();
            $table->string('email', 100)->index();
            $table->string('company', 120)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('mobile', 15)->nullable();

            $table->boolean('feature_on_water_saver')->default(0);
            $table->boolean('email_opt_in')->default(0);

            $table->rememberToken();
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
        Schema::dropIfExists('applicants');
    }
}
