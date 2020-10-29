<?php


namespace Core;

use Exception;

class BaseController
{
    public $response = [
        'code' => 400,
        'message' => 'An error has occurred'
    ];

    public function __construct()
    {

    }

    public function __call($method, $args = [])
    {
        try {
            if (method_exists($this, $method)) {
                return call_user_func_array([$this, $method], $args);
            }
            throw new Exception(get_class($this) . " doesn't have a valid method for {$method}");
        } catch (Exception $e) {
            $response = (new App())->container->get('Response');
        }
    }
}