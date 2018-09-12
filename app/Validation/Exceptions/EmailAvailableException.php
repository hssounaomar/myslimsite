<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 28/08/2018
 * Time: 13:04
 */

namespace App\Validation\Exceptions;


use Respect\Validation\Exceptions\ValidationException;

class EmailAvailableException extends ValidationException
{
public static $defaultTemplates =[
    self::MODE_DEFAULT =>[
        self::STANDARD =>'Email is already taken.',
    ]
];
}