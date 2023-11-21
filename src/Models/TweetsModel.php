<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Модель для работы с твитами.
 */
class TweetsModel
{
    /**
     * @var PDO Объект для взаимодействия с базой данных.
     */
    private $dbConnection;

    /**
     * Конструктор класса TweetsModel.
     *
     * Инициализирует объект подключения к базе данных.
     *
     * @param PDO $dbConnection Объект подключения к базе данных.
     */
    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * Сохраняет твит в базе данных.
     *
     * @param int $categoryId Идентификатор категории твита.
     * @param string $username Имя пользователя, оставившего твит.
     * @param string $messageText Текст твита.
     * @param string $createdAt Дата и время создания твита.
     */
    public function saveTweet($categoryId, $username, $messageText, $createdAt)
    {
        try {
            // Сохраняем твит в базу данных
            $this->insertTweetToDatabase($categoryId, $username, $messageText, $createdAt);

            echo 'Сообщение успешно сохранено!';
        } catch (PDOException $e) {
            echo 'Ошибка при сохранении сообщения: ' . $e->getMessage();
        }
    }

    /**
     * Получает твиты из базы данных.
     *
     * @return array Массив с твитами.
     * @throws DatabaseException В случае ошибки взаимодействия с базой данных.
     */
    public function getTweets()
    {
        try {
            $sql = "SELECT * FROM tweets ORDER BY created_at DESC";
            $statement = $this->dbConnection->query($sql);

            $tweets = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $tweets;
        } catch (PDOException $e) {
            throw new DatabaseException('Ошибка при получении твитов: ' . $e->getMessage());
        }
    }

    /**
     * Вставляет твит в базу данных.
     *
     * @param int $categoryId Идентификатор категории твита.
     * @param string $username Имя пользователя, оставившего твит.
     * @param string $messageText Текст твита.
     * @param string $createdAt Дата и время создания твита.
     */
    private function insertTweetToDatabase($categoryId, $username, $messageText, $createdAt)
    {
        $sql = "INSERT INTO tweets (category_id, username, content, created_at) VALUES (:categoryId, :username, :content, CONVERT_TZ(NOW(), 'UTC', 'Asia/Yekaterinburg'))";
        $statement = $this->dbConnection->prepare($sql);

        $statement->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':content', $messageText, PDO::PARAM_STR);

        $statement->execute();
    }
}
