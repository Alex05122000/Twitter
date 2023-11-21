<?php

namespace Migrations;

use App\Modules\DatabaseCon;
use PDO;
use PDOException;

/**
 * Класс миграции для работы с твитами.
 */
class TweetsMigration
{
    /**
     * @var PDO Объект для взаимодействия с базой данных.
     */
    private $pdo;

    /**
     * Конструктор класса TweetsMigration.
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
            // Создаем таблицу tweets
            $this->createTweetsTable();

            echo "Таблица 'tweets' успешно создана";
        } catch (PDOException $e) {
            echo "Ошибка при создании таблицы: " . $e->getMessage();
        }
    }

    /**
     * Выполняет миграцию "Down".
     */
    public function down()
    {
        try {
            // Удаляем таблицу tweets
            $this->dropTweetsTable();

            echo "Таблица 'tweets' успешно удалена";
        } catch (PDOException $e) {
            echo "Ошибка при удалении таблицы: " . $e->getMessage();
        }
    }

    /**
     * Создает таблицу tweets в базе данных.
     *
     * @throws PDOException В случае ошибки выполнения SQL-запроса.
     */
    private function createTweetsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tweets (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            category_id INT(6) UNSIGNED,
            username VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )";

        $this->pdo->exec($sql);
    }

    /**
     * Удаляет таблицу tweets из базы данных.
     *
     * @throws PDOException В случае ошибки выполнения SQL-запроса.
     */
    private function dropTweetsTable()
    {
        $sql = "DROP TABLE IF EXISTS tweets";

        $this->pdo->exec($sql);
    }
}
