<?php

namespace App\Middleware;

class CsrfViewMiddleware extends Middleware
{
    public function __invoke ($request,$response,$next)
    {
        $tokenNameKey = $this->container->csrf->getTokenNameKey();
        $tokenName = $this->container->csrf->getTokenName();
        $tokenValueKey = $this->container->csrf->getTokenValueKey();
        $tokenValue = $this->container->csrf->getTokenValue();

        $csrf_field = sprintf('
            <input type="hidden" name="%s" value="%s" />
            <input type="hidden" name="%s" value="%s" />
        ',$tokenNameKey,$tokenName,$tokenValueKey,$tokenValue);

        $this->container->view->getEnvironment()->addGlobal('csrf',[
            'field' => $csrf_field,
        ]);

        return $next($request,$response);
    }
}