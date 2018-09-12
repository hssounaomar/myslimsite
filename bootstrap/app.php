    <?php
    session_start();
    use Respect\Validation\Validator as v;

require  __DIR__ . '/../vendor/autoload.php';


$app = new \Slim\App([
    'settings'=>[
        'db'=>[
            'driver'=>'mysql',
            'host'=>'localhost',
            'database'=>'ooredoo',
            'username'=>'root',
            'password'=>'',
            'charset'=>'utf8',
            'collation'=>'utf8_unicode_ci',
            'prefix'=>''
        ]]
]);
$container = $app->getContainer();
$capsule=new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
   $container['errorHandler'] = function ($container) {

        return function ($request, $response, $exception) use ($container) {
            return $container['response']->withRedirect('/error');
        };
    };
   $container['notAllowedHandler'] = function ($container) {

         return function ($request, $response, $exception) use ($container) {
             return $container['response']->withRedirect('/errorNotAllowed');
         };
     };
    $container['notFoundHandler'] = function ($container) {

        return function ($request, $response) use ($container) {
            return $container['response']->withRedirect('/errorPageNotFound');
        };
    };

    //add eloquent
    $container['db']=function ($container) use ($capsule){
        return $capsule;
    };
    //add validator
    $container['validator']=function ($container) {
        return new \App\Validation\Validator();
    };
    //add  csrf middleware
   /* $container['csrf']=function ($container) {
        return new \Slim\Csrf\Guard;
    };*/
    //add class auth
    $container['auth']=function ($container) {
        return new App\Auth\Auth;
    };
    //add flash messages
    $container['flash']=function ($container) {
        return new \Slim\Flash\Messages;
    };
// Register component on container
$container['view'] = function ($container) {

    $view = new \Slim\Views\Twig(__DIR__.'/../resources/views', ['cache' => false]);



    $view->addExtension(new Slim\Views\TwigExtension($container->router, $container->request->getUri()));
   // $view->addExtension (new App\Models\DisplayUsers); add extension to twig
$view->getEnvironment()->addGlobal('auth',[
    'check'=>$container->auth->check(),
    'user'=>$container->auth->user(),
]);
    $view->getEnvironment()->addGlobal('flash',$container->flash);
    return $view;
};

$container['ApplicationsController']=function ($container){
    return new \App\Controllers\ApplicationsController($container);
};
    $container['AuthController']=function ($container){
        return new \App\Controllers\Auth\AuthController($container);
    };

    $app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
    $app->add(new \App\Middleware\OldInputMiddleware($container));
   // $app->add(new \App\Middleware\CsrfViewMiddleware($container));
    //$app->add($container->csrf);
    v::with('App\\Validation\\Rules\\');

require __DIR__ . "/../app/routes.php";