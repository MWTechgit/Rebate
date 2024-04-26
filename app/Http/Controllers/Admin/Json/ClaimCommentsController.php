<?php

namespace App\Http\Controllers\Admin\Json;

use App\Claim;
use App\ClaimComment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClaimCommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.can_write')->only('store', 'update', 'destroy');
    }

    public function index(Claim $claim)
    {
        return response()->json(['data' => $claim->comments()->with('admin')->get() ]);
    }

    public function store(Request $request, Claim $claim)
    {

        $data = $this->validate($request, ['content' => 'string|required']);

        $comment = $claim->comments()->save( new ClaimComment($data) );

        return $comment->load('admin');
    }

    public function show(Claim $claim, ClaimComment $comment)
    {
        return $comment->load('admin');
    }

    public function update(Request $request, Claim $claim, ClaimComment $comment)
    {
        $this->validate($request, ['content' => 'string|required']);

        $comment->update(['content' => $request->content]);

        return $comment->load('admin');
    }

    public function destroy(Claim $claim, ClaimComment $comment)
    {
        $comment->delete();

        return $comment;
    }
}
