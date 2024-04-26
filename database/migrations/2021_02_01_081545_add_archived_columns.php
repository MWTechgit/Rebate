<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchivedColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->integer('archived_id')->after('remember_token')->nullable();
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->integer('archived_id')->after('admin_id')->nullable();
        });
        Schema::table('application_comments', function (Blueprint $table) {
            $table->integer('archived_id')->after('admin_id')->nullable();
        });
        Schema::table('claims', function (Blueprint $table) {
            $table->integer('archived_id')->after('applicant_id')->nullable();
        });
        Schema::table('claim_comments', function (Blueprint $table) {
            $table->integer('archived_id')->after('admin_id')->nullable();
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('archived_id')->after('years_lived')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('archived_id');
        });
        Schema::table('claim_comments', function (Blueprint $table) {
            $table->dropColumn('archived_id');
        });
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn('archived_id');
        });
        Schema::table('application_comments', function (Blueprint $table) {
            $table->dropColumn('archived_id');
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('archived_id');
        });
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('archived_id');
        });
    }
}
