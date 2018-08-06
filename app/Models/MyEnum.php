<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 31/07/2018
 * Time: 11:17
 */

namespace App\Models;


abstract class MyEnum
{
    final public function __construct($value)
    {
        $c = new ReflectionClass($this);
        if(!in_array($value, $c->getConstants())) {
            throw IllegalArgumentException();
        }
        $this->value = $value;
    }

    final public function __toString()
    {
        return $this->value;
    }
}