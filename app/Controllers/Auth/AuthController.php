<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 27/08/2018
 * Time: 21:00
 */

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;
class AuthController extends Controller
{
public function getSignUp($request,$response,$args){

return $this->view->render($response,'auth/signup.twig');
}
public function postSignUp($request,$response,$args){
$validation=$this->validator->validate($request,[
    'email'=>v::noWhitespace()->notEmpty()->email()->emailAvailable(),
    'name'=>v::notEmpty()->alpha(),
    'password'=>v::noWhitespace()->notEmpty(),
]);
if($validation->failed()){
    return $response->withRedirect($this->router->pathFor('auth.signup'));
}
    $user=User::create([
    'email'=>$request->getParam('email'),
    'name'=>$request->getParam('name'),
    'password'=>password_hash($request->getParam('password'),PASSWORD_DEFAULT),
]);

return $response->withRedirect($this->router->pathFor('home'));
}
public function getSignIn($request,$response){
    return $this->view->render($response,'auth/signin.twig');
}
    public function postSignIn($request,$response){
$auth=$this->auth->attempt(
    $request->getParam('email'),
    $request->getParam('password')
);
if(!$auth){
    return $response->withRedirect($this->router->pathFor('auth.signin'));
}
        return $response->withRedirect($this->router->pathFor('home'));
    }
    public function getSignOut($request,$response){
$this->auth->logout();
//$this->flash->addMessage('info','kjkj'); //add message to flash
        return $response->withRedirect($this->router->pathFor('auth.signin'));
    }
}