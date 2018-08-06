<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 23/07/2018
 * Time: 13:26
 */

namespace App\Controllers;


class Controller
{

    protected $container;
    public function __construct($container)
    {

        $this->container=$container;

    }
    public function __get($property)
    {
        if($this->container->{$property}){
            return $this->container->{$property};
        }
    }
}