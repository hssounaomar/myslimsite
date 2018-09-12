<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 07/09/2018
 * Time: 12:43
 */

namespace App\Validation\Exceptions;


use Respect\Validation\Exceptions\ValidationException;

class ConnectionException extends ValidationException
{
    public static $defaultTemplates =[
        self::MODE_DEFAULT =>[
            self::STANDARD =>'Error Of Connection',
        ]
    ];
}