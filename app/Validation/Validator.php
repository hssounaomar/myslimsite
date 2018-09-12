<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 28/08/2018
 * Time: 11:08
 */

namespace App\Validation;



use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as Respect;
class Validator
{
    protected $errors;
public  function  validate($request,array  $rules){
    foreach ($rules as $field => $rule){
        try{
     $rule->setName(ucfirst($field))->assert($request->getParam($field));
        }catch (NestedValidationException $e){
            $this->errors[$field]=$e->getMessages();
        }
    }
    $_SESSION['errors']=$this->errors;
    return $this;
}

public function failed(){
    return ! empty($this->errors);
}
}