<?php
// === 1. ПОЛУЧЕНИЕ ПЕРЕМЕННЫХ ИЗ GET ===
// store - текущая строка результата
// count - счётчик нажатий
// key   - нажатая кнопка

// Получаем store (если нет — пустая строка)
$store = isset($_GET['store']) ? $_GET['store'] : '';

// Получаем счётчик (если нет — 0)
$count = isset($_GET['count']) ? (int)$_GET['count'] : 0;

// === 2. ОБРАБОТКА НАЖАТИЯ КНОПКИ ===
if (isset($_GET['key'])) {
    $key = $_GET['key'];
    
    if ($key === 'reset') {
        // Кнопка СБРОС - очищаем строку
        $store = '';
        $count++;
    } else {
        // Нажата цифра - добавляем к строке и увеличиваем счётчик
        $store .= $key;
        $count++;
    }
}

// === 3. ВЫВОД HTML ===
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ЛР3 — Чарыев Аллагулы, группа 241-353</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h2>Лабораторная работа №3</h2>
    <p>Чарыев Аллагулы | Группа 241-353</p>
</header>

<main>
    <!-- Окно результата -->
    <div class="result">
        <?php echo htmlspecialchars($store); ?>
    </div>

    <!-- Кнопки цифр 1-9 -->
    <div class="buttons">
        <?php for ($i = 1; $i <= 9; $i++): ?>
            <a href="?key=<?php echo $i; ?>&store=<?php echo urlencode($store); ?>&count=<?php echo $count; ?>" class="btn"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

    <!-- Кнопка цифры 0 -->
    <div class="buttons">
        <a href="?key=0&store=<?php echo urlencode($store); ?>&count=<?php echo $count; ?>" class="btn">0</a>
    </div>

    <!-- Кнопка СБРОС - ПЕРЕДАЁМ store и count, чтобы счётчик не сбрасывался -->
    <div class="buttons reset-container">
        <a href="?key=reset&store=<?php echo urlencode($store); ?>&count=<?php echo $count; ?>" class="btn reset">СБРОС</a>
    </div>
</main>

<footer>
    Общее число нажатий: <?php echo $count; ?>
</footer>

</body>
</html>