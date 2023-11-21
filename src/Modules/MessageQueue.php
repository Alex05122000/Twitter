<?php

namespace App\Modules;

use Predis\Client;

/**
 * Класс для работы с очередью сообщений в Redis.
 */
class MessageQueue
{
    /**
     * @var Client Клиент Redis.
     */
    private $redis;

    /**
     * Конструктор класса MessageQueue.
     *
     * @param Client $redis Клиент Redis.
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Отправляет сообщение в очередь Redis.
     *
     * @param int $categoryId Идентификатор категории сообщения.
     * @param string $username Имя пользователя, отправившего сообщение.
     * @param string $messageText Текст сообщения.
     * @param string $createdAt Дата и время создания сообщения.
     */
    public function sendToQueue($categoryId, $username, $messageText, $createdAt)
    {
        $messageData = json_encode([
            'categoryId' => $categoryId,
            'username' => $username,
            'messageText' => $messageText,
            'createdAt' => $createdAt,
        ]);

        // Помещаем сообщение в очередь Redis
        $this->redis->lpush('message_queue', $messageData);
    }

    /**
     * Обрабатывает сообщения из очереди.
     */
    public function processQueue()
    {
        // Обработка сообщений из очереди
        while ($messageData = $this->redis->rpop('message_queue')) {
            // Обработка сообщения
            $data = json_decode($messageData, true);
            // Вызываем нужные методы для сохранения сообщения в базе данных
            $this->processMessageData($data);
        }
    }

    /**
     * Обрабатывает данные сообщения и сохраняет их в базе данных.
     *
     * @param array $data Данные сообщения.
     */
    private function processMessageData(array $data)
    {
        // Ваш код для обработки данных сообщения и сохранения в базе данных
    }
}
