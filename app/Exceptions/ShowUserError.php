<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ShowUserError extends \RuntimeException implements HttpExceptionInterface {

	protected $returnCode = 422;

	public function __construct($message, $code = 422, \Exception $previous = null) {

		$this->returnCode = $code;

		parent::__construct($message, $code, $previous);
	}

	public function getStatusCode() {
		return $this->returnCode;
	}

	/*
		    * Returns response headers.
		    *
		    * @return array Response headers
	*/
	public function getHeaders() {
		return [];
	}
}