<?php

namespace GenTux\Http;

use GenTux\JwtToken;
use GenTux\Drivers\JwtDriverInterface;

/**
 * This trait can either be used in middlewares or Illuminate Request objects.
 * It will provide methods for retrieving the token from the request
 * that it's either used in, or provided.
 */
trait GetsJwtToken
{

    /**
     * Get the JWT token from the request
     *
     * @param \Illuminate\Http\Request|null $request
     *
     * @return string|null
     */
    public function getToken($request = null)
    {
        $name = $this->getInputName();

        return $request ? $request->input($name) : $this->input($name);
    }

    /**
     * Create a new JWT token object from the token in the request
     *
     * @return JwtToken
     */
    public function jwtToken()
    {
        $token = $this->input($this->getInputName());
        $driver = $this->makeDriver();

        $jwt = new JwtToken($driver);
        $jwt->setToken($token);

        return $jwt;
    }

    /**
     * Get the input name to search for the token in the request
     *
     * This can be customized by setting the JWT_INPUT env variable.
     * It will default to using `token` if not defined.
     *
     * @return string
     */
    private function getInputName()
    {
        return getenv('JWT_INPUT') ?: 'token';
    }

    /**
     * Create a driver to use for the token from the IoC
     *
     * @return JwtDriverInterface
     */
    private function makeDriver()
    {
        return app(JwtDriverInterface::class);
    }
}