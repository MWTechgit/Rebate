<?php

namespace App\Http\Controllers\Admin\Json;

use App\Application;
use App\ApplicationComment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationCommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.can_write')->only('store', 'update', 'destroy');
    }

    public function index(Application $application)
    {
        return response()->json(['data' => $application->comments()->with('admin')->get() ]);
    }

    public function store(Request $request, Application $application)
    {
        $data = $this->validate($request, ['content' => 'string|required']);

        $comment = $application->comments()->save( new ApplicationComment($data) );

        return $comment->load('admin');
    }

    public function show(Application $application, ApplicationComment $comment)
    {
        return $comment->load('admin');
    }

    public function update(Request $request, Application $application, ApplicationComment $comment)
    {
        $this->validate($request, ['content' => 'string|required']);

        $comment->update(['content' => $request->content]);

        return $comment->load('admin');
    }

    public function destroy(Application $application, ApplicationComment $comment)
    {
        $comment->delete();

        return $comment;
    }
}
