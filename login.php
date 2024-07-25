<?php
session_start();

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

// Обработка данных из формы входа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Подготовленный запрос для выборки данных пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка пароля
    if ($user && password_verify($password, $user['password'])) {
        // Авторизация успешна, сохраняем пользователя в сессию
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: welcome.php"); // Редирект на защищенную страницу
        exit();
    } else {
        echo "Неверное имя пользователя или пароль.";
    }
}
?>
