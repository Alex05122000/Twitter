<?php

/**
 * Worker - скрипт для обработки сообщений из очереди Redis и сохранения их в базе данных.
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Modules\DatabaseCon;
use App\Modules\MessageQueue;
use App\Models\TweetsModel;

// Получаем объект Redis и инициализируем очередь сообщений и модель Tweets
$redis = DatabaseCon::getRedisClient();
$messageQueue = new MessageQueue($redis);
$tweetsModel = new TweetsModel(DatabaseCon::getConnection());

// Бесконечный цикл обработки сообщений
while (true) {
    // Блокирующее получение сообщения из очереди
    $messageData = $redis->brpop('message_queue', 0);

    // Обработка сообщения
    $data = json_decode($messageData[1], true);

    // Сохранение в базе данных
    $tweetsModel->saveTweet($data['categoryId'], $data['username'], $data['messageText'], $data['createdAt']);


}
