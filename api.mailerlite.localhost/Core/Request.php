<?php


namespace Core;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Just a wrapper around the HttpFoundation\Request component
 * @package Core
 */
class Request
{
    CONST EXPECTED_CONTENT = 'application/json';

    private static ?SymfonyRequest $request = null;

    public function __construct()
    {
        $this->request();
    }

    /**
     * @return SymfonyRequest
     */
    public function request()
    {
        if (!self::$request) {
            self::$request = SymfonyRequest::createFromGlobals();

            //Check if the incoming request comes in JSON format
            if (0 === strpos(
                self::$request->headers->get('CONTENT_TYPE'),
                'application/json')
            ) {
                $data = json_decode(self::$request->getContent(), true);
                self::$request->request->replace(is_array($data) ? $data : array());
            }
        }
        return self::$request;
    }

    /**
     * Return whether an incoming request is valid
     * @return bool
     */
    public function isValid()
    {
        return stripos($this->request()->server->get('HTTP_ACCEPT'), '*/*') !== false ||
            stripos($this->request()->server->get('HTTP_ACCEPT'), self::EXPECTED_CONTENT) !== false;

    }
    
    /**
     * Return an incoming request's method
     */
    public function requestMethod()
    {
        return $this->request()->getMethod();
    }

    /**
     * Return an incoming request's method
     */
    public function requestUri()
    {
        return $this->request()->server->get('REQUEST_URI');
    }
}