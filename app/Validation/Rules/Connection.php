<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 07/09/2018
 * Time: 12:41
 */

namespace App\Validation\Rules;


use Respect\Validation\Rules\AbstractRule;

class Connection extends AbstractRule
{

    public function validate($rep)
    {
       return $rep->testConnection();
    }
}