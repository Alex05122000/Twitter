<?php

namespace Migrations;

use App\Modules\DatabaseCon;
use PDO;
use PDOException;

/**
 * Класс миграции для работы с категориями.
 */
class CategoriesMigration
{
    /**
     * @var PDO Объект для взаимодействия с базой данных.
     */
    private $pdo;

    /**
     * Конструктор класса CategoriesMigration.
     *
     * Инициализирует подключение к базе данных.
     */
    public function __construct()
    {
        // Используем метод getConnection из класса DatabaseCon
        $this->pdo = DatabaseCon::getConnection();
    }

    /**
     * Выполняет миграцию "Up".
     */
    public function up()
    {
        try {
            // Проверка подключения к базе данных
            $this->checkDatabaseConnection();

            // Ваш остальной код миграции "Up"...

            echo "Миграция 'Up' выполнена успешно.\n";
        } catch (PDOException $e) {
            echo "Ошибка при выполнении миграции 'Up': " . $e->getMessage();
        }
    }

    /**
     * Выполняет миграцию "Down".
     */
    public function down()
    {
        try {
            // Проверка подключения к базе данных
            $this->checkDatabaseConnection();

            // Ваш остальной код миграции "Down"...

            echo "Миграция 'Down' выполнена успешно.\n";
        } catch (PDOException $e) {
            echo "Ошибка при выполнении миграции 'Down': " . $e->getMessage();
        }
    }

    /**
     * Проверяет подключение к базе данных.
     *
     * @throws PDOException В случае ошибки подключения.
     */
    private function checkDatabaseConnection()
    {
        try {
            $result = $this->pdo->query('SELECT 1');
            $result->fetch(PDO::FETCH_ASSOC);

            echo 'Подключение к базе данных успешно установлено.';
        } catch (PDOException $e) {
            throw new PDOException('Ошибка подключения к базе данных: ' . $e->getMessage());
        }
    }
}
