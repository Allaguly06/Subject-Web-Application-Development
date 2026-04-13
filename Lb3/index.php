<?php
// ============================================
// ЛАБОРАТОРНАЯ РАБОТА №3
// Калькулятор нажатий с GET-параметрами
// ============================================

// === 1. ИНИЦИАЛИЗАЦИЯ ПЕРЕМЕННЫХ ===
// store - текущая строка результата
// count - счётчик нажатий
// key   - нажатая кнопка

if (!isset($_GET['store'])) {
    // Первая загрузка страницы
    $store = '';
    $count = 0;
} else {
    // Передано предыдущее состояние
    $store = $_GET['store'];
    $count = (int)$_GET['count'];
}

// === 2. ОБРАБОТКА НАЖАТИЯ КНОПКИ ===
if (isset($_GET['key'])) {
    $key = $_GET['key'];
    
    if ($key === 'reset') {
        // Кнопка СБРОС - очищаем строку
        $store = '';
        // Счётчик нажатий при сбросе не увеличиваем
    } else {
        // Нажата цифра - добавляем к строке
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

    <!-- Кнопка СБРОС -->
    <div class="buttons reset-container">
        <a href="?key=reset" class="btn reset">СБРОС</a>
    </div>
</main>

<footer>
    Общее число нажатий: <?php echo $count; ?>
</footer>

</body>
</html>