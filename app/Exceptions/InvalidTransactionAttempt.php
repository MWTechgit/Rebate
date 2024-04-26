<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class InvalidTransactionAttempt extends \RuntimeException  implements HttpExceptionInterface
{
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