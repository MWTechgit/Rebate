<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('application_id')->unsigned();
            $table->integer('applicant_id')->unsigned();

            $table->integer('fy_year')->unsigned();
            $table->string('status', 45);
            $table->string('amount_awarded', 15)->nullable();

            # ========================================================
            # Skipping upc_, receipt_, and product_ fields for now
            # These are the file fields we'll need for applicants
            # to upload documents to get their claim approved.
            # ========================================================

            $table->string('submission_type', 20)->nullable();

            $table->dateTime('expires_at')->nullable();
            $table->dateTime('expired_at')->nullable();

            $table->boolean('skip_document_upload')->default(0);
            $table->boolean('expire_notification_sent')->default(0);

            # TODO: Remove?
            $table->dateTime('post_marked_at')->nullable();

            $table->dateTime('admin_first_viewed_at')->nullable();

            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'fy_year']);

            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade')
            ;

            $table->foreign('applicant_id')
                ->references('id')
                ->on('applicants')
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
        Schema::dropIfExists('claims');
    }
}
