<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an exception.
	 *
	 * @param  \Exception  $exception
	 * @return void
	 */
	public function report(Exception $exception)
	{
		if (app()->environment('production')) {
			# Send exception email
			app('sneaker')->captureException($exception);
		}

		if (app()->bound('sentry') && $this->shouldReport($exception)) {
    		app('sentry')->captureException($exception);
	    }

		if (app()->environment('testing') && $this->shouldntReport($exception)) {
            \Log::warning( get_class($exception) .': ' . $exception->getMessage(), ['e' => $exception->errors()]);
        }

		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $exception
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception) {

		if (($exception instanceof \Illuminate\Database\QueryException || $exception instanceof \PDOException)) {
			return $this->highVolume($request, $exception);
		}

		if ($exception instanceof \App\Exceptions\ShowUserError && $request->expectsJson()) {
			return response()->json(['message' => $exception->getMessage()], $exception->getStatusCode());
		}

		return parent::render($request, $exception);
	}

	protected function highVolume($request, $exception) {
		$message = "We're experiencing higher than normal volume at the moment. Please wait a short while, then try again.";

		if ($request->expectsJson()) {
			return response()->json(['message' => $message], 503);
		}

		return response()->view(503, [], 503);
	}

	/**
	 * Convert an authentication exception into an unauthenticated response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Auth\AuthenticationException  $exception
	 * @return \Illuminate\Http\Response
	 */
	// protected function unauthenticated($request, AuthenticationException $exception)
	// {
	//     if ($request->expectsJson()) {
	//         return response()->json(['message' => $exception->getMessage()], 401);
	//     }

	//     if ($request->is('admin/*')) {
	//         return redirect()->guest(route('admins.login'));
	//     }

	//     return redirect()->guest(route('login'));
	// }
}
