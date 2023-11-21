<?php

namespace App\Modules;

use PDO;
use PDOException;
use Predis\Client;

/**
 * Класс для управления подключением к базе данных и Redis.
 */
class DatabaseCon
{
    /**
     * @var PDO|null Объект для взаимодействия с базой данных.
     */
    private static $connection;

    /**
     * Получает соединение с базой данных.
     *
     * @return PDO Объект для взаимодействия с базой данных.
     * @throws PDOException В случае ошибки при подключении к базе данных.
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            $config = include(__DIR__ . '/../../configs/database.php');

            try {
                $dsn = "mysql:host={$config['servername']};dbname={$config['dbname']}";
                self::$connection = new PDO($dsn, $config['username'], $config['password']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException("Ошибка подключения: " . $e->getMessage());
            }
        }

        return self::$connection;
    }

    /**
     * Получает клиент Redis.
     *
     * @return Client Клиент Redis.
     */
    public static function getRedisClient()
    {
        return new Client([
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => 6379, // порт Redis
            // другие параметры подключения
        ]);
    }
}
