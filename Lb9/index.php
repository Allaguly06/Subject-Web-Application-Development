<?php
// Подключаем модуль меню
require_once 'menu.php';

// Определяем параметры
$page = isset($_GET['p']) ? $_GET['p'] : 'view';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$pg = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР9 — Записная книжка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h2>Лабораторная работа №9</h2>
    <p>Чарыев Аллагулы | Группа 241-353</p>
</header>

<main>
    <!-- Вывод меню -->
    <?php echo getMenu($page, $sort); ?>
    
    <!-- Вывод контента в зависимости от выбранного пункта -->
    <div class="content">
        <?php
        switch ($page) {
            case 'view':
                require_once 'viewer.php';
                echo getContactsList($sort, $pg);
                break;
            case 'add':
                require_once 'add.php';
                break;
            case 'edit':
                require_once 'edit.php';
                break;
            case 'delete':
                require_once 'delete.php';
                break;
            default:
                require_once 'viewer.php';
                echo getContactsList('default', 0);
        }
        ?>
    </div>
</main>

<footer>
    Лабораторная работа №9 | Записная книжка
</footer>

</body>
</html>