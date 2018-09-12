<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 28/08/2018
 * Time: 12:51
 */

namespace App\Validation\Rules;


use App\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{

    public function validate($input)
    {
        return User::where('email',$input)->count()===0;
    }
}