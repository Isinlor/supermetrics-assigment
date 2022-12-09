<?php

namespace App\Dispatcher;

use App\Controller\ControllerInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RouteDispatcher
 *
 * @package App\Dispatcher
 */
class RouteDispatcher
{

    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    /**
     * @param string $requestUri
     */
    public function dispatch(string $requestUri): void
    {
        $parts = explode('?', $requestUri);

        list($path, $query) = array_pad($parts, 2, null);

        parse_str($query, $params);

        $routes = $this->container->getParameter('routes');
        if (!array_key_exists($path, $routes)) {
            $path = '/404';
        }

        $parts = explode('@', $routes[$path]);
        list($controllerName, $action) = $parts;

        $controller = $this->locateController($controllerName);

        /** @var ResponseInterface $response */
        $response = $controller->$action($params);

        (new SapiEmitter())->emit($response);
    }

    /**
     * @param string $name
     *
     * @return ControllerInterface
     */
    private function locateController(string $name): ControllerInterface
    {
        if(!$this->container->has($name)) {
            throw new \RuntimeException(sprintf('Unable instantiate controller %s', $name));
        }
        return $this->container->get($name);
    }
}
