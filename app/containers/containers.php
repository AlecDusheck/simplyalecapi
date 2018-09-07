<?php

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig("../app/views", [
        "cache" => false,
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->addExtension(new Twig_Extension_Debug());
    $twig = $view->getEnvironment();
    return $view;
};

$container['ResumeSender'] = function ($container) {
    return new \sa\controllers\ResumeSender($container);
};
$container['Home'] = function ($container) {
    return new \sa\controllers\Home($container);
};