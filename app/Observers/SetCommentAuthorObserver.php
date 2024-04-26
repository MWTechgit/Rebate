<?php

namespace App\Observers;

use App\Comment;
use Illuminate\Contracts\Auth\Factory as Auth;

class SetCommentAuthorObserver
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function creating(Comment $comment)
    {
        if ( !$comment->admin_id || !app()->runningInConsole() ) {
            $comment->admin_id = $this->auth->guard('admin')->id();
        }
    }

    public function updated(Comment $comment)
    {
    }

    public function deleted(Comment $comment)
    {
    }

    public function restored(Comment $comment)
    {
    }

    public function forceDeleted(Comment $comment)
    {
    }
}
