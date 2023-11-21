<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-top: 50px;
        }

        #chat {
            height: 200px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="max-width: 300px; margin: 0 auto;">
            <h1 class="mb-4"><?= $title ?></h1>

            <!-- Форма для ввода имени пользователя и сообщения -->
            <form id="messageForm">
                <div class="form-group">
                    <label for="username">Имя пользователя</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="categories">Выберите тему</label>
                    <select class="form-control" id="categories" required>
                        <option value="" selected disabled>Выберите категорию</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <h2>Чат</h2>
                    <div id="chat" class="border p-3"></div>
                </div>

                <div class="form-group">
                    <label for="message">Введите сообщение:</label>
                    <input type="text" class="form-control" id="message" name="message" required>
                </div>

                <!-- Кнопка для отправки сообщения -->
                <button type="button" class="btn btn-primary" onclick="sendMessage()">Отправить</button>
            </form>
        </div>
    </div>

    <!-- Подключение необходимых скриптов -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        // Добавьте этот скрипт для загрузки сообщений при загрузке страницы
        document.addEventListener('DOMContentLoaded', function () {
            loadMessages();
        });

        // Отправка запроса на сервер для получения сообщений
        function loadMessages() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/get', true);

            xhr.onload = function () {
                if (xhr.status == 200) {
                    var messages = JSON.parse(xhr.responseText);
                    displayMessages(messages);
                } else {
                    console.error('Ошибка при загрузке сообщений: ' + xhr.statusText);
                }
            };

            xhr.onerror = function () {
                console.error('Ошибка сети при загрузке сообщений');
            };

            xhr.send();
        }

        // Отображение полученных сообщений
        function displayMessages(messages) {
            var chat = document.getElementById('chat');
            chat.innerHTML = '';

            messages.forEach(function (message) {
                if (message.created_at) {
                    chat.innerHTML += '<p><strong>' + message.username + ':</strong> ' + message.content + ' <small>(' + message.created_at + ')</small></p>';
                } else {
                    console.error('Ошибка: created_at не определено для сообщения:', message);
                }
            });
        }

        // Отправка сообщения на сервер
        function sendMessage() {
            var username = document.getElementById("username").value;
            var message = document.getElementById("message").value;
            var category = document.getElementById("categories").value;

            if (username.trim() === "" || category.trim() === "") {
                alert("Пожалуйста, введите имя пользователя и выберите категорию.");
                return;
            }

            var chat = document.getElementById("chat");
            chat.innerHTML += "<p><strong>" + username + ":</strong> " + message + "</p>";

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/send", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };

            var params = "username=" + encodeURIComponent(username) +
                         "&categoryId=" + encodeURIComponent(category) +
                         "&messageText=" + encodeURIComponent(message) +
                         "&createdAt=" + encodeURIComponent(new Date().toISOString());

            xhr.send(params);

            document.getElementById("username").value = "";
            document.getElementById("message").value = "";
            document.getElementById("categories").selectedIndex = 0;
        }
    </script>

</body>
</html>
