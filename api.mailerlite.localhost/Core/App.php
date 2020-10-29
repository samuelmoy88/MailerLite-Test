<?php


namespace Core;


use DI\Container;
use Symfony\Component\HttpFoundation\JsonResponse;

class App
{

    public Container $container;

    private static array $config;

    public function __construct()
    {
        $this->container = new Container();

        $this->container->set('Request', \DI\create('Core\Request'));

        $this->container->set('Response', \DI\create('Symfony\Component\HttpFoundation\JsonResponse'));

        $this->setConfig(require 'config.php');

    }

    /**
     * @return array
     */
    public static function getConfig(): array
    {
        return self::$config;
    }

    /**
     * @param array $config
     */
    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    /**
     * @param $code
     * @param array $message
     * @param array $headers
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function sendResponse($code, $message = [], $headers = [])
    {
        /** @var JsonResponse $response */
        $response = $this->container->get('Response');

        $response->setData($message);

        if ($headers) {
            foreach ($headers as $key => $value) {
                $response->headers->set(
                    $key,
                    $value
                );
            }
        }

        $response->setStatusCode($code);

        $response->send();

        exit;
    }
}