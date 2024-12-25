<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чат</title>
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="icon" href="favicon.png" type="image/png">
</head>
<body>
<div class="header">
    <h1>Чат</h1>
    <div class="auth-buttons">
        <?php if($_COOKIE['name'] == ''):?>
        <button onclick="window.location.href='/views/registration.html'">Регистрация</button>
        <button onclick="window.location.href='/views/authorization.html'">Авторизация</button>
        <?php else: ?>
        <button onclick="window.location.href='/user/logout.php'">Выход</button>
        <?php endif; ?>
    </div>
</div>
<div class="chat-container">
    <div class="chat-header">
        <div class="sort-buttons">
            <button onclick="window.location.href='?sort=created_at'">Сначала новые</button>
            <button onclick="window.location.href='?sort=created_at_asc'">Сначала старые</button>
            <button onclick="window.location.href='?sort=name'">По имени пользователя</button>
            <button onclick="window.location.href='?sort=email'">По email пользователя</button>
        </div>
    </div>

    <div class="messages">
        <?php
        require 'comment/getComment.php';
        ?>
        <?php foreach ($messages as $msg): ?>
            <div class="message">
                <p><strong><?= htmlspecialchars($msg['name']) ?></strong>
                    (<?= htmlspecialchars($msg['email']) ?>):</p>

                <p><?= nl2br(htmlspecialchars($msg['text'])) ?></p>

                <?php if (!empty($msg['file_path'])): ?>
                    <?php if (in_array($msg['file_type'], ['JPEG', 'PNG', 'GIF'])): ?>

                        <img src="<?= htmlspecialchars($msg['file_path']) ?>" alt="Изображение">
                    <?php else: ?>

                        <a href="<?= htmlspecialchars($msg['file_path']) ?>" target="_blank">Посмотреть текстовый файл</a>
                    <?php endif; ?>
                <?php endif; ?>

                <small><?= htmlspecialchars($msg['created_at']) ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    if($_COOKIE['name'] != ''):
    ?>
        <form enctype="multipart/form-data" action="/comment/createComment.php" class="comment-section" method="post">
            <input type="text" name="message" placeholder="Напишите сообщение" id="message" required>
            <input type="file" name="upload">
            <button type="submit">Отправить</button>
        </form>
    <?php else: ?>
        <div class="comment-section">
            <div>Зарегистрируйтесь или авторизуйтесь чтобы оставить комментарий</div>
        </div>
    <?php endif; ?>
</div>
<div class="pagination">

    <?php if ($page > 1): ?>
        <form method="get" action="" class="pagination-form">
            <button type="submit" name="page" value="<?= $page - 1 ?>" class="pagination-button">
                Предыдущая
            </button>
            <input type="hidden" name="sort" value="<?= $sortBy ?>" />
        </form>
    <?php else: ?>
        <button class="pagination-button disabled" disabled>Предыдущая</button>
    <?php endif; ?>

    <span class="pagination-info">Страница <?= $page ?> из <?= $totalPages ?></span>

    <?php if ($page < $totalPages): ?>
        <form method="get" action="" class="pagination-form">
            <button type="submit" name="page" value="<?= $page + 1 ?>" class="pagination-button">
                Следующая
            </button>
            <input type="hidden" name="sort" value="<?= $sortBy ?>" />
        </form>
    <?php else: ?>
        <button class="pagination-button disabled" disabled>Следующая</button>
    <?php endif; ?>
</div>

</body>
</html>
