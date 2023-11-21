<?php

// Подключение конфигурационного файла базы данных
$config = include('../configs/database.php');
use App\Modules\DatabaseCon;

/**
 * Класс для посева категорий в базу данных.
 */
class CategorySeeder
{
    /**
     * @var \PDO Объект для взаимодействия с базой данных.
     */
    private $pdo;

    /**
     * Конструктор класса CategorySeeder.
     *
     * Инициализирует подключение к базе данных.
     */
    public function __construct()
    {
        // Используем метод getConnection из класса DatabaseCon
        $this->pdo = DatabaseCon::getConnection();
    }

    /**
     * Посев категорий в базу данных.
     */
    public function seedCategories()
    {
        try {
            // Массив с категориями
            $categories = [
                'Юмор',
                'Знакомства',
                'Флудилка',
            ];

            foreach ($categories as $category) {
                // Подготавливаем запрос
                $stmt = $this->pdo->prepare("INSERT INTO categories (title) VALUES (:title)");
                // Привязываем параметры
                $stmt->bindParam(':title', $category);
                // Выполняем запрос
                $stmt->execute();
            }

            echo "Категории успешно посеяны";
        } catch (\PDOException $e) {
            echo "Ошибка при посеве категорий: " . $e->getMessage();
        } finally {
            // Опционально: закрываем соединение после использования
            $this->pdo = null;
        }
    }
}

// Создаем экземпляр класса CategorySeeder
$categorySeeder = new CategorySeeder();

// Вызываем метод для посева категорий
$categorySeeder->seedCategories();
