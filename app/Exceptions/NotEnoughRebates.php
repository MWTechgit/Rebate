<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class NotEnoughRebates extends \RuntimeException implements HttpExceptionInterface
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message ?? 'There are not enough rebates available', $code, $previous);
    }

    public function getStatusCode()
   {
       return 422;
   }

   /*
    * Returns response headers.
    *
    * @return array Response headers
    */    
   public function getHeaders()
   {
       return [];
   }
}