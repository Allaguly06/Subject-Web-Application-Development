<?php
// === 1. ФУНКЦИЯ getTR() ===
// Формирует HTML-код одной строки таблицы
// Принимает:
//   $row_data - строка вида "C1*C2*C3" (ячейки через *)
//   $cols_count - требуемое число колонок
// Возвращает:
//   HTML-код строки таблицы <tr>...</tr>
function getTR($row_data, $cols_count) {
    // Разбиваем строку на ячейки
    $cells = explode('*', $row_data);
    
    // Если ячеек больше, чем нужно — обрезаем
    // Если меньше — добавляем пустые
    $cells = array_pad($cells, $cols_count, '');
    
    // Формируем строку таблицы
    $ret = '<tr>';
    for ($i = 0; $i < $cols_count; $i++) {
        $content = isset($cells[$i]) ? htmlspecialchars($cells[$i]) : '';
        $ret .= '<td>' . $content . '</td>';
    }
    $ret .= '</tr>';
    
    return $ret;
}

// === 2. ФУНКЦИЯ outTable() ===
// Выводит HTML-код таблицы (или сообщение об ошибке)
// Принимает:
//   $structure - строка вида "C1*C2*C3#C4*C5*C6"
//   $cols_count - требуемое число колонок
//   $table_num - номер таблицы (для заголовка)
function outTable($structure, $cols_count, $table_num) {
    // Проверка: число колонок
    if ($cols_count <= 0) {
        echo "<h2>Таблица №{$table_num}</h2>";
        echo "<p><strong>Ошибка:</strong> Неправильное число колонок ({$cols_count})</p>";
        return;
    }
    
    // Разбиваем структуру на строки
    $rows = explode('#', $structure);
    
    // Фильтруем пустые строки (если есть лишние # подряд)
    $rows = array_filter($rows, function($row) {
        return trim($row) !== '';
    });
    
    // Проверка: есть ли строки
    if (count($rows) == 0) {
        echo "<h2>Таблица №{$table_num}</h2>";
        echo "<p><strong>Ошибка:</strong> В таблице нет строк</p>";
        return;
    }
    
    // Формируем строки таблицы
    $table_rows = '';
    $has_cells = false;
    
    foreach ($rows as $row) {
        // Проверяем, есть ли в строке ячейки
        $cells = explode('*', $row);
        $has_cells_in_row = (count($cells) > 0 && !(count($cells) == 1 && $cells[0] === ''));
        
        if ($has_cells_in_row) {
            $has_cells = true;
            $table_rows .= getTR($row, $cols_count);
        }
    }
    
    // Проверка: есть ли строки с ячейками
    if (!$has_cells) {
        echo "<h2>Таблица №{$table_num}</h2>";
        echo "<p><strong>Ошибка:</strong> В таблице нет строк с ячейками</p>";
        return;
    }
    
    // Выводим таблицу
    echo "<h2>Таблица №{$table_num}</h2>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo $table_rows;
    echo "</table>";
    echo "<br>";
}

// === 3. ИНИЦИАЛИЗАЦИЯ ПЕРЕМЕННЫХ ===
// Число колонок для таблиц
$cols_count = 3;

// Массив структур таблиц (не менее 10 элементов)
$structures = [
    // 1. Обычная таблица 3x3
    "Иванов*25*Москва#Петров*30*СПб#Сидоров*28*Казань",
    
    // 2. Таблица с разным количеством ячеек в строках
    "Январь*31*Зима#Февраль*28*Зима#Март*31*Весна#Апрель*30*Весна",
    
    // 3. Таблица с пустыми ячейками
    "Программирование*PHP**Веб#Базы данных*MySQL*#Бэкенд*Python*Django",
    
    // 4. Таблица с одной строкой
    "Единственная строка*100*Тест",
    
    // 5. Таблица с пустой строкой (должна быть обработана)
    "Пустая строка**#*пустая*ячейка",
    
    // 6. Таблица с разными типами данных
    "ID*Имя*Возраст#1*Анна*22#2*Борис*25#3*Виктория*30#4*Глеб*27",
    
    // 7. Таблица с длинными текстами
    "Описание*Количество*Цена#Монитор 27 дюймов*5*25000#Клавиатура механическая*12*4500",
    
    // 8. Таблица с числами
    "x*10*20*30#y*15*25*35#z*18*28*38",
    
    // 9. Таблица со специальными символами
    "HTML*<div>*Тег#CSS*.class*Селектор#PHP*<?php>*Тег",
    
    // 10. Таблица с пустыми строками между данными
    "Строка1*значение1*данные1##Строка3*значение3*данные3"
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР4 — Чарыев Аллагулы, группа 241-353</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">
        <img src="logo.png" alt="Логотип университета" onerror="this.style.display='none'">
    </div>
    <h2>Лабораторная работа №4</h2>
    <p>Чарыев Аллагулы | Группа 241-353</p>
</header>

<main>
    <h1>Вывод таблиц на основе строковых структур</h1>
    <p>Число колонок: <strong><?php echo $cols_count; ?></strong></p>
    <hr>
    
    <?php
    // Вывод всех таблиц из массива
    for ($i = 0; $i < count($structures); $i++) {
        outTable($structures[$i], $cols_count, $i + 1);
    }
    ?>
</main>

<footer>
    Лабораторная работа №4 | Вывод таблиц через пользовательские функции
</footer>

</body>
</html>