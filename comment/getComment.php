<?php
$host = 'localhost';
$user = 'alilue';
$password = 'TimyrTimyr2004';
$dbname = 'chat';

// Создаем подключение к серверу БД
$connection = mysqli_connect($host, $user, $password, $dbname);

if (mysqli_connect_errno()) {
    echo "Ошибка подключения к базе данных: " . mysqli_connect_error();
    exit();
}

// Получаем параметры сортировки и пагинации из URL
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'created_at DESC'; // По умолчанию сортировка по дате

// Определяем порядок сортировки в зависимости от параметра
switch ($sortBy) {
    case 'name':
        $order = 'ORDER BY `name`';
        break;
    case 'email':
        $order = 'ORDER BY `email`';
        break;
    case 'created_at':
        $order = 'ORDER BY `created_at` DESC'; // Сначала новые
        break;
    case 'created_at_asc':
        $order = 'ORDER BY `created_at` ASC'; // Сначала старые
        break;
    default:
        $order = 'ORDER BY `created_at` DESC'; // По умолчанию сортировка по дате (новые вначале)
}

// Страницы: задаем количество сообщений на странице
$limit = 10; // Количество сообщений на одной странице
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Текущая страница
$offset = ($page - 1) * $limit; // Смещение для SQL запроса

// Получение сообщений из БД с учетом сортировки и страниц
$query = "SELECT `name`, `email`, `created_at`, `text`, `file_path`, `file_type` FROM `message` $order LIMIT $limit OFFSET $offset";
$result = mysqli_query($connection, $query);

// Массив для хранения сообщений
$messages = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
}

// Получение общего количества сообщений для расчета количества страниц
$countQuery = "SELECT COUNT(*) AS total FROM `message`";
$countResult = mysqli_query($connection, $countQuery);
$totalMessages = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalMessages / $limit); // Общее количество страниц

// Закрываем подключение
mysqli_close($connection);

