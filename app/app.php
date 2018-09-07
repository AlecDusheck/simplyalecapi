<?php

include_once '../vendor/autoload.php';

date_default_timezone_set('America/Chicago');
ob_start();
session_start();

$settings = include('config.php');
$config = [ //Put settings into config
    'settings' => $settings
];

$logManager = new \sa\classes\logManager();
$logManager->checkExist();


$app = new \Slim\App($config);
$container = $app->getContainer();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

include_once __DIR__ . '/containers/containers.php';
include_once __DIR__ . '/routes/routes.php';
include_once __DIR__ . '/middleware/gsMiddleware.php';
$app->add(new sa\middleware\gsMiddleware($container));
