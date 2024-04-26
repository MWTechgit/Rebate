<?php

use App\History;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RebuildAppIdsOnHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history', function (Blueprint $table) {
            $table->integer('justin_app_id')->unsigned()->nullable()->after('application_id');
            $table->integer('application_id')->unsigned()->nullable()->change();
        });

        History::getQuery()->update(['justin_app_id' => DB::raw('`application_id`')]);

        History::leftJoin('applications', 'history.application_id', '=', 'applications.id')
            ->whereNull('applications.status')
            ->orWhereRaw('applications.rebate_code != history.rebate_code')
            ->update(['history.application_id' => null]);

        Schema::table('history', function (Blueprint $table) {
            $table->unique('application_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history', function (Blueprint $table) {
            $table->dropUnique('history_application_id_unique');
        });

        History::getQuery()->update(['application_id' => DB::raw('`justin_app_id`')]);

        Schema::table('history', function (Blueprint $table) {
            $table->dropColumn('justin_app_id');
        });
    }
}
