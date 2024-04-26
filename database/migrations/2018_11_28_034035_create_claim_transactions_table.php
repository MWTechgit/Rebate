<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_transactions', function (Blueprint $table) {
            $table->increments('id');

            # Replaces transaction_by_id, denied_by_id, approved_by_id
            # on the old system rebate_claims table
            $table->integer('admin_id')->unsigned();

            # Belongs to claim
            $table->integer('claim_id')->unsigned();

            # Replaces denied, approved
            $table->string('type', 40)->index();

            # Replaces denial_reason on claim
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('admin_id')
                ->references('id')->on('admins')
            ;

            $table->foreign('claim_id')
                ->references('id')->on('claims')
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
        Schema::dropIfExists('claim_transactions');
    }
}
