<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 28/08/2018
 * Time: 15:24
 */

namespace App\Auth;

use App\Models\User;
class Auth
{
    public function user(){
        if(isset($_SESSION['user']))
        return User::find($_SESSION['user']);
        else
            return null;
    }
    public function check(){
        return isset($_SESSION['user']);
    }
public function attempt($email,$password){
    $user=User::where('email',$email)->first();
    if(!$user){
        return false;
    }
    if(password_verify($password,$user->password)){
        $_SESSION['user']=$user->id;
        return true;
    }
    return false;
}

public function logout(){
    unset($_SESSION['user']);
}
}