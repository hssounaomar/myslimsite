<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 28/08/2018
 * Time: 11:45
 */

namespace App\Middleware;


class Middleware
{
protected $container;
public  function __construct($container)
{
    $this->container=$container;
}
}