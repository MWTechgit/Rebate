<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned();
            $table->integer('application_id')->unsigned();

            # approved/denied
            $table->string('type', 40);

            # Denial reason
            $table->text('description')->nullable();

            # Denial more info
            $table->text('extended_description')->nullable();

            $table->timestamps();

            $table->foreign('application_id')
                ->references('id')->on('applications')
                ->onDelete('cascade')
            ;

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
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
        Schema::dropIfExists('application_transactions');
    }
}
