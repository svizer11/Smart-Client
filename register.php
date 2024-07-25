<?php
// Подключение к базе данных MySQL
$host = 'localhost';
$dbname = 'имя_базы_данных';
$username = 'пользователь_базы_данных';
$password = 'пароль_базы_данных';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Не удалось подключиться к базе данных: " . $e->getMessage());
}

// Обработка данных из формы регистрации
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Хэширование пароля (лучше использовать более безопасные методы, например, bcrypt)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Подготовленный запрос для вставки данных в таблицу пользователей
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);

    // Выполнение запроса
    try {
        $stmt->execute();
        echo "Регистрация прошла успешно.";
    } catch (PDOException $e) {
        echo "Ошибка при регистрации: " . $e->getMessage();
    }
}
?>
