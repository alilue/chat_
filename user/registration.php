<?php

$host = 'localhost';
$user = 'alilue';
$password = 'TimyrTimyr2004';
$dbname = 'chat';

//Создаем подключение к серверу БД
$connection = mysqli_connect($host, $user, $password, $dbname);

if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
}
else {
    //Получаем данные ползьователя
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    //Кешируем пароль пользователя
    $password = md5($password."alilue");

    //Сохраняем данные в БД
    mysqli_query($connection, "INSERT INTO `user` (`name`, `password`, `email`, `ip`, `browser`) VALUES('$name', '$password', '$email', '$ip', '$userAgent')");
    setcookie('name', $name, time() + 3600, "/");
    setcookie('email', $email, time() + 3600, "/");
}

//Закрываем подключение к серверу БД
mysqli_close($connection);

header('Location: /');

