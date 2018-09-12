<?php
$app->get('/errorPageNotFound', 'ApplicationsController:pageNotFound');
$app->get('/error', 'ApplicationsController:error');
$app->get('/errorNotAllowed', 'ApplicationsController:errorNotAllowed');

$app->group('',function () use ($app){
    $app->get('/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $app->post('/signup', 'AuthController:postSignUp');
    $app->get('/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $app->post('/signin', 'AuthController:postSignIn');
})->add(new \App\Middleware\GuestMiddleware($container));
$app->group('',function () use ($app){

    $app->get('/apps', 'ApplicationsController:index')->setName('home');
    $app->get('/apps/{name}/users', 'ApplicationsController:displayUsersByApplication');
    $app->post('/apps/createApp', 'ApplicationsController:createApplication');
    $app->get('/apps/createApp', 'ApplicationsController:createApplication');
    $app->get('/apps/ajax/{name}', 'ApplicationsController:ajax');
    $app->post('/apps/ajax/{name}', 'ApplicationsController:ajax');
    $app->get('/users', 'ApplicationsController:displayUsers');
    $app->Post('/users/ajax', 'ApplicationsController:sendEmail');
    $app->post('/apps/{name}/delete', 'ApplicationsController:deleteApplication');
    $app->get('/apps/{name}/update', 'ApplicationsController:updateApplication');
    $app->post('/apps/{name}/update', 'ApplicationsController:updateApplication');
    $app->get('/signout', 'AuthController:getSignOut')->setName('auth.signout');
    $app->post('/apps/ajax/excel/{name}', 'ApplicationsController:generateTableExcel');
    $app->post('/users/ajax/excel', 'ApplicationsController:generateUsersExcel');
})->add(new \App\Middleware\AuthMiddleware($container));