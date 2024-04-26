<?php

namespace Bwp\QuickAudit;

use App\Application;
use App\Http\Controllers\Controller;
use Bwp\QuickAudit\Fetcher;

class ToolController extends Controller
{

    public function application($id)
    {
        $fetcher = new Fetcher;

        $result = $fetcher->fetchCount( Application::getCached($id) ?: abort (404) );

        return $result;
    }

    public function items($id)
    {
        $fetcher = new Fetcher;

        $result = $fetcher->fetchItems( Application::getCached($id) ?: abort(404) );

        return $result;
    }

}
