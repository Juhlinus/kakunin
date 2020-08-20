<?php

namespace Kakunin\Exceptions;

use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatedException extends \Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render(Request $request): Response
    {
        return Redirect::back();
    }
}