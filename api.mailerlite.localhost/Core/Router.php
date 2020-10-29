<?php


namespace Core;

use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;

class Router
{
    private $dispatcher;

    public function __construct()
    {
        $this->dispatcher = simpleDispatcher(function(RouteCollector $r) {
                require 'routes.php';
            }
        );
    }
    /**
     * Dispatch a route
     * @param App $app
     */
    public static function dispatch(App $app)
    {
        //Instantiate the dispatcher
        $self = new self();

        /** @var Request $request */
        $request = $app->container->get('Request');

        $httpMethod = $request->requestMethod();

        $uri = $request->requestUri();

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $routeInfo = $self->dispatcher->dispatch($httpMethod, $uri);

        //Default response code, message and headers
        $code = \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND;
        $message = ['message' => 'Route not found'];
        $headers = [];

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $code = \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND;
                $message = ['message' => 'Route not found'];
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $code = \Symfony\Component\HttpFoundation\Response::HTTP_METHOD_NOT_ALLOWED;
                $message = ['message' => 'Method not allowed for this route'];
                $headers = ['Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE'];

                //Exceptions for OPTIONS request sent first by Vue before sending the real PUT request
                if ($httpMethod === 'OPTIONS') {
                    $code = \Symfony\Component\HttpFoundation\Response::HTTP_OK;
                    $message = ['message' => 'Ok'];
                }
                break;
            case Dispatcher::FOUND:
                [$controller, $method] = explode('@', $routeInfo[1]);

                //Call the respective controller
                $class = "App\\Controllers\\{$controller}";

                $action = new $class();

                $vars[] = $request->request();

                if(isset($routeInfo[2])) $vars[] = $routeInfo[2];

                $result = $action->{$method}(...$vars);

                if ($result) {
                    $message = $result;
                    $code = $message['code'];
                }

                break;
        }

        return [$code, $message, $headers];

    }
}