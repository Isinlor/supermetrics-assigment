<?php

use App\Kernel\AppKernel;
use Dotenv\Dotenv;
use App\Dispatcher\RouteDispatcher;

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = Dotenv::createImmutable(__DIR__ . '/..');
$dotEnv->load();

$kernel = new AppKernel('config.yaml');

/** @var RouteDispatcher $routeDispatcher */
$routeDispatcher = $kernel->getContainer()->get(RouteDispatcher::class);

$routeDispatcher->dispatch($_SERVER['REQUEST_URI']);