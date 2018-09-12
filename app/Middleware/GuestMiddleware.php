<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 03/09/2018
 * Time: 12:15
 */

namespace App\Middleware;


class GuestMiddleware extends  Middleware
{
    public  function  __invoke($request,$response,$next)
    {
        if ($this->container->auth->check()){

            return $response->withRedirect($this->container->router->pathFor('home'));
        }
        $response=$next($request,$response);
        return $response;
    }
}