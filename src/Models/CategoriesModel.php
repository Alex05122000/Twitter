<?php
namespace App\Models;

use App\Modules\DatabaseCon;
use PDO;
use PDOException;

class DatabaseException extends \Exception {}

/**
 * Class CategoriesModel
 * 
 * Модель для работы с категориями.
 */
class CategoriesModel
{
   /**
     * @var PDO Объект подключения к базе данных.
     */
    private $pdo;

    /**
     * Конструктор класса CategoriesModel.
     * 
     * @param PDO $dbConnection Объект подключения к базе данных.
     */
    public function __construct($dbConnection)
    {
        $this->pdo = DatabaseCon::getConnection();
    }

    /**
     * Получает список категорий из базы данных.
     * 
     * @return array Массив категорий.
     */
    public function getCategories()
    {
        try {
            $query = "SELECT id, title FROM categories";
            $statement = $this->pdo->prepare($query);

            $statement->execute();

            $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $categories;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}