<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExportBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_batches', function (Blueprint $table) {
            
            $table->increments('id');

            $table->string('url')->nullable();

            $table->string('filename')->nullable();

            $table->integer('admin_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->onDelete('set null')
            ;
        });

        Schema::create('export_batches_claims', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->integer('claim_id')->unsigned();

            $table->integer('export_batch_id')->unsigned();

            $table->foreign('claim_id')
                ->references('id')
                ->on('claims')
                ->onDelete('cascade')
            ;

            $table->foreign('export_batch_id')
                ->references('id')
                ->on('export_batches')
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
        Schema::dropIfExists('export_batches_claims');
        Schema::dropIfExists('export_batches');
    }
}
