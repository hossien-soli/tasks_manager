<?php

namespace App\Middleware;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke ($request,$response,$next)
    {
        $validation_errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : null;
        $this->container->view->getEnvironment()->addGlobal('errors',$validation_errors);
        if (isset($_SESSION['errors']))
            unset($_SESSION['errors']);

        return $next($request,$response);
    }
}