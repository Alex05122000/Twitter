<?php
/**
 * Главный входной файл приложения.
 *
 * Этот файл обрабатывает входящий запрос, определяет соответствующий маршрут
 * и вызывает соответствующий контроллер и метод действия.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Проверка загрузки
//var_dump(class_exists(\Migrations\CategoriesMigration::class));
//var_dump(class_exists(\Migrations\TweetsMigration::class));

use Migrations\CategoriesMigration;
use Migrations\TweetsMigration;
use App\Modules\DatabaseCon;

// Получаем команду из консоли (если есть)
$command = isset($argv[1]) ? $argv[1] : null;

if ($command === 'migrate') {
    // Если команда - миграция
    handleMigrateCommand($argv);
} else {
    // Ваш текущий код для обработки HTTP-запросов
    handleHttpRequest();
}

/**
 * Обрабатывает команду миграции из консоли.
 *
 * @param array $arguments Аргументы командной строки.
 */
function handleMigrateCommand(array $arguments)
{
    // Получаем направление миграции (up или down)
    $direction = isset($arguments[2]) ? $arguments[2] : null;

    if ($direction === 'up' || $direction === 'down') {
        // Выполнение миграций "up" или "down"
        $categoriesMigration = new CategoriesMigration();
        $tweetsMigration = new TweetsMigration();

        // Вызываем соответствующий метод в зависимости от направления
        $method = $direction === 'up' ? 'up' : 'down';

        $categoriesMigration->{$method}();
        $tweetsMigration->{$method}();

        echo "Миграции выполнены успешно.\n";
    } else {
        echo "Неверное направление миграции. Используйте 'up' или 'down'.\n";
    }
}

/**
 * Обрабатывает HTTP-запросы.
 */
function handleHttpRequest()
{
    // Ваш текущий код для обработки HTTP-запросов
    $uri = $_SERVER['REQUEST_URI'];
    $routes = include __DIR__ . '/../router/routes.php';

    $routeFound = false;

    foreach ($routes as $route => $controllerAction) {
        if ($uri === $route) {
            $routeFound = true;
            list($controller, $action) = explode('@', $controllerAction);

            $controllerFile = __DIR__ . '/../src/Controllers/' . $controller . '.php';

            if (file_exists($controllerFile)) {
                require_once $controllerFile;

                $controllerClass = "\\App\\Controllers\\$controller";

                // Создаем экземпляр TweetsModel и передаем его в конструктор MessageController
                $tweetsModel = new \App\Models\TweetsModel(DatabaseCon::getConnection());
                $instance = new $controllerClass($tweetsModel);

                // Передаем параметры из $_POST в метод $action
                call_user_func_array([$instance, $action], $_POST);

            } else {
                echo '404 Страница не найдена';
            }

            break;
        }
    }

    if (!$routeFound) {
        echo '404 Страница не найдена';
    }
}
