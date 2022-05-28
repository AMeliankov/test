<?php

namespace App\Database;

use PDO;
use PDOException;

/**
 * Подключение к бд.
 */
class DBConnection
{
    private static $instance;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            new DBConnection();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $config = require('config/database.php');

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};";

        try {
            self::$instance = new PDO($dsn, $config['user'], $config['password']);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function __clone() {}
}