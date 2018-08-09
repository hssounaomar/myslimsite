    <?php
require  __DIR__ . '/../vendor/autoload.php';


$app = new \Slim\App;
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {    $view = new \Slim\Views\Twig(__DIR__.'/../resources/views', [
    'cache' => false
]);



    $view->addExtension(new Slim\Views\TwigExtension($container->router, $container->request->getUri()));
    $view->addExtension (new App\Models\DisplayUsers);

    return $view;
};
$container['ApplicationsController']=function ($container){
    return new \App\Controllers\ApplicationsController($container);
};
require __DIR__ . "/../app/routes.php";