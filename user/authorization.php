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
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    //Кешируем пароль пользователя
    $password = md5($password."alilue");

    //Ищем пользователя в БД
    $result = mysqli_query($connection, "SELECT * FROM `user` WHERE `email` = '$email' AND `password` = '$password'");
    $user = $result->fetch_assoc();
    if(count($user) == 0) {

    }
    setcookie('name', $user['name'], time() + 3600, "/");
    setcookie('email', $user['email'], time() + 3600, "/");
}

//Закрываем подключение к серверу БД
mysqli_close($connection);

header('Location: /');

