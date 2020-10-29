<?php

use Core\Database;
use Core\App;

if (!function_exists('DB')) {
    /**
     * Instantiate the DB and return it if it hasn't been started before
     * @return \Medoo\Medoo
     */
    function DB() {

        static $db;

        if (!$db) {

            $db = new Database(
                App::getConfig()['database']
            );
        }

        return $db->database;
    }
}

if (!function_exists('sanitizeVar')) {
    /**
     *
     */
    function sanitizeVar($variable) {

        return $variable;
    }
}