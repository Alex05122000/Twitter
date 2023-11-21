<?php

namespace App\Controllers;

use App\Models\CategoriesModel;

/**
 * Class HomeController
 * 
 * Контроллер главной страницы приложения.
 */
class HomeController
{
    private $pdo;
    private $categoriesModel;

    public function __construct()
    {
        $this->categoriesModel = new CategoriesModel($this->pdo);
    }

 /**
     * Метод для отображения главной страницы.
     * 
     * Загружает данные и отображает шаблон главной страницы.
     */
    public function index()
    {
       // Установка заголовка страницы
        $title = "My own Twitter";

        // Временное содержание чата (может быть заменено реальными данными)
        $chatContent = 'Тест';

        // Получение списка категорий из модели
        $categories = $this->categoriesModel->getCategories();

        // Используем абсолютный путь к файлу шаблона
        include __DIR__ . '/../../templates/main.php';
    }
}
