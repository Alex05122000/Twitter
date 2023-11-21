<?php

namespace App\Controllers;

use App\Models\TweetsModel;
use App\Modules\MessageQueue;
use App\Modules\DatabaseCon;
use PDOException;

/**
 * Контроллер для обработки сообщений.
 */
class MessageController
{
    /**
     * @var TweetsModel Модель для работы с сообщениями.
     */
    private $tweetsModel;

    /**
     * Конструктор класса MessageController.
     *
     * Инициализирует модель для работы с сообщениями.
     *
     * @param TweetsModel $tweetsModel Модель для работы с сообщениями.
     */
    public function __construct(TweetsModel $tweetsModel)
    {
        $this->tweetsModel = $tweetsModel;
    }

    /**
     * Отправляет сообщение в очередь.
     *
     * @param string $username Имя пользователя.
     * @param int $categoryId Идентификатор категории.
     * @param string $messageText Текст сообщения.
     * @param string $createdAt Дата создания сообщения.
     */
    public function send($username, $categoryId, $messageText, $createdAt)
    {
        try {
            // Установка часового пояса для Екатеринбурга
            date_default_timezone_set('Asia/Yekaterinburg');

            // Преобразование формата даты
            $createdAtFormatted = date('Y-m-d H:i:s', strtotime($createdAt));

            // Отправляем сообщение в очередь Redis
            $this->sendMessageToQueue($categoryId, $username, $messageText, $createdAtFormatted);

            echo 'Сообщение успешно отправлено в очередь Redis!';
        } catch (PDOException $e) {
            echo 'Ошибка при отправке сообщения в очередь Redis: ' . $e->getMessage();
        }
    }

    /**
     * Получает сообщения и отправляет их в формате JSON.
     */
    public function get()
    {
        try {
            // Получаем сообщения из базы данных
            $messages = $this->tweetsModel->getTweets();

            // Отправляем сообщения в формате JSON
            header('Content-Type: application/json');
            echo json_encode($messages);
        } catch (PDOException $e) {
            echo 'Ошибка при получении сообщений: ' . $e->getMessage();
        }
    }

    /**
     * Отправляет сообщение в очередь Redis.
     *
     * @param int $categoryId Идентификатор категории.
     * @param string $username Имя пользователя.
     * @param string $messageText Текст сообщения.
     * @param string $createdAt Дата создания сообщения.
     */
    private function sendMessageToQueue($categoryId, $username, $messageText, $createdAt)
    {
        $redis = DatabaseCon::getRedisClient();
        $messageQueue = new MessageQueue($redis);
        $messageQueue->sendToQueue($categoryId, $username, $messageText, $createdAt);
    }
}
