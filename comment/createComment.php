<?php

$host = 'localhost';
$user = 'alilue';
$password = 'TimyrTimyr2004';
$dbname = 'chat';

$uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/comment/upload/';

// Допустимые типы файлов
$allowedMimeTypes = [
    'image/png' => 'PNG',
    'image/gif' => 'GIF',
    'image/jpeg' => 'JPG',
    'text/plain' => 'TXT'
];

// Максимальные размеры
$maxFileSize = 100 * 1024; // 100 KB для текстовых файлов
$maxWidth = 320; // Максимальная ширина изображения
$maxHeight = 240; // Максимальная высота изображения

function connectToDatabase($host, $user, $password, $dbname)
{
    $connection = mysqli_connect($host, $user, $password, $dbname);
    if (!$connection) {
        die("Ошибка подключения к базе данных: " . mysqli_connect_error());
    }
    return $connection;
}

function validateImageDimensions($filePath, $maxWidth, $maxHeight)
{
    $imageInfo = getimagesize($filePath);
    if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
        return false;
    }
    return true;
}

function saveMessage($connection, $message, $name, $email, $filePath = null, $fileType = null)
{
    $message = mysqli_real_escape_string($connection, $message);
    $query = "INSERT INTO `message` (`text`, `name`, `email`, `file_path`, `file_type`) VALUES ('$message', '$name', '$email', '$filePath', '$fileType')";
    if (!mysqli_query($connection, $query)) {
        die("Ошибка при вставке в базу данных: " . mysqli_error($connection));
    }
}

// Проверяем, был ли файл загружен
if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
    $fileMimeType = mime_content_type($_FILES['upload']['tmp_name']);
    $fileSize = $_FILES['upload']['size'];

    if (array_key_exists($fileMimeType, $allowedMimeTypes)) {
        $fileType = $allowedMimeTypes[$fileMimeType];

        if ($fileMimeType === 'text/plain' && $fileSize > $maxFileSize) {
            die("Текстовый файл превышает допустимый размер в 100 КБ.");
        }

        if (strpos($fileMimeType, 'image/') === 0 && !validateImageDimensions($_FILES['upload']['tmp_name'], $maxWidth, $maxHeight)) {
            die("Изображение превышает допустимые размеры 320x240 пикселей.");
        }

        $uniqueName = uniqid() . '_' . basename($_FILES['upload']['name']);
        $filePath = $uploadPath . $uniqueName;

        if (move_uploaded_file($_FILES['upload']['tmp_name'], $filePath)) {
            $connection = connectToDatabase($host, $user, $password, $dbname);

            $message = $_POST['message'] ?? '';
            $name = $_COOKIE['name'] ?? 'Unknown';
            $email = $_COOKIE['email'] ?? 'unknown@example.com';

            $relativePath = '/comment/upload/' . $uniqueName;
            saveMessage($connection, $message, $name, $email, $relativePath, $fileType);

            mysqli_close($connection);
        } else {
            die("Не удалось загрузить файл.");
        }
    } else {
        die("Недопустимый тип файла.");
    }
} else {
    $connection = connectToDatabase($host, $user, $password, $dbname);

    $message = $_POST['message'] ?? '';
    $name = $_COOKIE['name'] ?? 'Unknown';
    $email = $_COOKIE['email'] ?? 'unknown@example.com';

    saveMessage($connection, $message, $name, $email);

    mysqli_close($connection);
}

header('Location: /');
