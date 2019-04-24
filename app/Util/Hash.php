<?php

namespace App\Util;

class Hash
{
    protected $config;

    public function __construct ($config)
    {
        $this->config = $config;
    }

    public function password ($password)
    {
        return password_hash(
            $password,
            $this->config->get('app.hash.algo'),
            ['cost' => $this->config->get('app.hash.cost')],
        );
    }

    public function check ($password,$hash)
    {
        return password_verify($password,$hash);
    }
}