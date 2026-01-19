<?php
declare(strict_types=1);

class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo) return self::$pdo;

        $config = require __DIR__ . '/../../config/config.php';
        $db = $config['database'];

        self::$pdo = new PDO($db['dsn'], $db['user'], $db['pass'], $db['options']);
        return self::$pdo;
    }
}
