<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('rebate_id')->unsigned();
            $table->integer('applicant_id')->unsigned();

            # This is set if the app was submitted by an admin.
            $table->integer('admin_id')->unsigned()->nullable();

            $table->integer('fy_year')->unsigned();
            $table->string('status', 45);

            $table->string('rebate_code', 25)->unique();
            $table->integer('rebate_count');
            $table->integer('desired_rebate_count')->default(0);

            $table->boolean('wait_listed')->default(0);
            $table->boolean('notification_sent')->default(0);
            $table->dateTime('notification_sent_at')->nullable();
            $table->string('notification_status', 20)->nullable();

            $table->dateTime('admin_first_viewed_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'fy_year']);

            $table->foreign('rebate_id')
                ->references('id')
                ->on('rebates')
                ->onDelete('cascade')
            ;

            $table->foreign('applicant_id')
                ->references('id')
                ->on('applicants')
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
        Schema::dropIfExists('applications');
    }
}
