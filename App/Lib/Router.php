<?php

namespace Project\Lib;

use PDO;

class Router
{

    private static $dbhost = 'localhost';
    private static $dbname = 'alugaki';
    private static $root = 'root';
    private static $senha = '';

    private static $conn;

    public static function getConn()
    {

        if (empty(self::$conn)) {
            self::$conn = new PDO(
                'mysql:host=' . self::$dbhost . ';dbname=' . self::$dbname,
                self::$root,
                self::$senha,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
        }

        return self::$conn;
    }
}
