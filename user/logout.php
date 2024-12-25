<?php

//Удаляем COOKIE пользователя
setcookie('name', $_COOKIE['name'], time() - 3600, "/");
setcookie('email', $_COOKIE['email'], time() - 3600, "/");
header('Location: /');