<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('claim_id')->unsigned();
            $table->string('receipt')->nullable();
            $table->string('installation_photo')->nullable();
            $table->string('upc')->nullable();
            $table->dateTime('purchased_at')->nullable();
            $table->timestamps();

            $table->foreign('claim_id')
                ->references('id')
                ->on('claims')
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
        Schema::dropIfExists('document_sets');
    }
}
