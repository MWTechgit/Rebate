<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_comments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('claim_id')->unsigned();
            $table->integer('admin_id')->unsigned()->nullable();

            $table->text('content');

            $table->timestamps();

            $table->foreign('claim_id')
                ->references('id')
                ->on('claims')
                ->onDelete('cascade')
            ;

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->onDelete('set null')
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
        Schema::dropIfExists('claim_comments');
    }
}
