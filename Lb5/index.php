<?php
// ============================================
// ЛАБОРАТОРНАЯ РАБОТА №5
// Таблица умножения с двумя типами верстки
// ============================================

// === 1. ПОЛУЧЕНИЕ ПАРАМЕТРОВ ===
// html_type - тип верстки (TABLE или DIV)
// content   - содержимое (2-9 или отсутствует для полной таблицы)

$html_type = isset($_GET['html_type']) ? $_GET['html_type'] : 'TABLE';
$content   = isset($_GET['content'])   ? (int)$_GET['content'] : null;

// === 2. ФУНКЦИЯ: число как ссылка ===
// Возвращает число, обернутое в ссылку (если число от 2 до 9)
// Ссылка сбрасывает тип верстки (не передает html_type)
function outNumAsLink($x) {
    if ($x >= 2 && $x <= 9) {
        return '<a href="?content=' . $x . '">' . $x . '</a>';
    }
    return $x;
}

// === 3. ФУНКЦИЯ: вывод строки таблицы умножения ===
// Формирует строку вида: "2 x 3 = 6" с ссылками на числа
function outRow($n) {
    $result = '';
    for ($i = 2; $i <= 9; $i++) {
        $result .= outNumAsLink($n) . ' x ' . outNumAsLink($i) . ' = ' . outNumAsLink($i * $n);
        $result .= '<br>';
    }
    return $result;
}

// === 4. ФУНКЦИЯ: вывод всей таблицы умножения ===
// Возвращает HTML-код всей таблицы (8 столбцов)
function outFullTable($html_type) {
    $result = '';
    
    if ($html_type == 'TABLE') {
        // Табличная верстка
        $result .= '<table class="multiplication-table">';
        for ($i = 2; $i <= 9; $i++) {
            $result .= '<tr>';
            $result .= '<td class="col-label">' . outNumAsLink($i) . '</td>';
            $result .= '<td class="col-values">' . outRow($i) . '</td>';
            $result .= '</tr>';
        }
        $result .= '</table>';
    } else {
        // Блочная верстка
        $result .= '<div class="multiplication-block">';
        for ($i = 2; $i <= 9; $i++) {
            $result .= '<div class="block-col">';
            $result .= '<div class="col-label">' . outNumAsLink($i) . '</div>';
            $result .= '<div class="col-values">' . outRow($i) . '</div>';
            $result .= '</div>';
        }
        $result .= '</div>';
    }
    
    return $result;
}

// === 5. ФУНКЦИЯ: вывод одного столбца ===
// Возвращает HTML-код одного столбца (умножение на число)
function outSingleColumn($n, $html_type) {
    $result = '';
    
    if ($html_type == 'TABLE') {
        // Табличная верстка
        $result .= '<table class="single-column">';
        $result .= '<tr><th class="col-label">' . outNumAsLink($n) . '</th></tr>';
        $result .= '<tr><td class="col-values">' . outRow($n) . '</td></tr>';
        $result .= '</table>';
    } else {
        // Блочная верстка
        $result .= '<div class="single-block">';
        $result .= '<div class="col-label">' . outNumAsLink($n) . '</div>';
        $result .= '<div class="col-values">' . outRow($n) . '</div>';
        $result .= '</div>';
    }
    
    return $result;
}

// === 6. ФУНКЦИЯ: информация для подвала ===
function getFooterInfo($html_type, $content) {
    $info = '';
    
    // Тип верстки
    if ($html_type == 'TABLE') {
        $info .= 'Табличная верстка. ';
    } else {
        $info .= 'Блочная верстка. ';
    }
    
    // Содержание таблицы
    if ($content === null) {
        $info .= 'Полная таблица умножения. ';
    } else {
        $info .= 'Таблица умножения на ' . $content . '. ';
    }
    
    // Дата и время
    $info .= date('d.m.Y в H:i:s');
    
    return $info;
}

// === 7. ВЫВОД HTML ===
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР5 — Чарыев Аллагулы, группа 241-353</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h2>Лабораторная работа №5</h2>
    <p>Чарыев Аллагулы | Группа 241-353</p>
    
    <!-- Главное меню (горизонтальное) -->
    <div class="main-menu">
        <?php
        // Пункт "Табличная верстка"
        echo '<a href="?html_type=TABLE';
        if ($content !== null) echo '&content=' . $content;
        echo '"';
        if ($html_type == 'TABLE') echo ' class="selected"';
        echo '>Табличная верстка</a>';
        
        // Пункт "Блочная верстка"
        echo '<a href="?html_type=DIV';
        if ($content !== null) echo '&content=' . $content;
        echo '"';
        if ($html_type == 'DIV') echo ' class="selected"';
        echo '>Блочная верстка</a>';
        ?>
    </div>
</header>

<div class="container">
    <!-- Основное меню (вертикальное, слева) -->
    <aside class="sidebar">
        <div class="side-menu">
            <?php
            // Пункт "Всё"
            echo '<a href="?';
            if ($html_type !== null) echo 'html_type=' . $html_type;
            echo '"';
            if ($content === null) echo ' class="selected"';
            echo '>Всё</a>';
            
            // Пункты 2-9
            for ($i = 2; $i <= 9; $i++) {
                echo '<a href="?content=' . $i;
                if ($html_type !== null) echo '&html_type=' . $html_type;
                echo '"';
                if ($content === $i) echo ' class="selected"';
                echo '>' . $i . '</a>';
            }
            ?>
        </div>
    </aside>
    
    <!-- Основной контент (таблица умножения) -->
    <main class="content">
        <h1>Таблица умножения</h1>
        
        <?php
        // Вывод таблицы умножения в зависимости от параметров
        if ($content === null) {
            // Полная таблица
            echo outFullTable($html_type);
        } else {
            // Один столбец
            echo outSingleColumn($content, $html_type);
        }
        ?>
    </main>
</div>

<footer>
    <?php echo getFooterInfo($html_type, $content); ?>
</footer>

</body>
</html>