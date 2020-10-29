<?php


namespace Core;

use Medoo\Medoo;

/**
 * Just a wrapper around Medoo package
 * @package Core
 */
Class Database
{
    public Medoo $database;

    public function __construct(array $options)
    {
        $this->database = new Medoo($options);

    }
}