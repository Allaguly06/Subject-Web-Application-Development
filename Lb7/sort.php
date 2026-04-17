<?php
//  1. ПОЛУЧЕНИЕ ДАННЫХ 
$elements = isset($_POST['element']) ? $_POST['element'] : [];
$algorithm = isset($_POST['algorithm']) ? $_POST['algorithm'] : 'selection';

// Фильтруем пустые значения
$elements = array_filter($elements, function($val) {
    return trim($val) !== '';
});

//  2. ПРОВЕРКА ВАЛИДНОСТИ 
$is_valid = true;
$invalid_elements = [];

foreach ($elements as $index => $value) {
    $value = trim($value);
    $value = str_replace(',', '.', $value);
    if (!is_numeric($value)) {
        $is_valid = false;
        $invalid_elements[] = $index;
    }
}

$elements_count = count($elements);
$is_empty = ($elements_count == 0);

//  3. ФУНКЦИИ ДЛЯ ВЫВОДА 
function printArrayState($arr, $iteration, &$output) {
    $output .= "<div class='iteration'>";
    $output .= "<span class='iteration-num'>Итерация {$iteration}:</span> ";
    $output .= "<span class='iteration-array'>[ " . implode(", ", $arr) . " ]</span>";
    $output .= "</div>";
}

//  4. АЛГОРИТМЫ СОРТИРОВКИ 

// 4.1 Сортировка выбором
function selectionSort(&$arr, &$output, &$iterations) {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $min_idx = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            if ($arr[$j] < $arr[$min_idx]) {
                $min_idx = $j;
            }
        }
        if ($min_idx != $i) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$min_idx];
            $arr[$min_idx] = $temp;
            $iterations++;
            printArrayState($arr, $iterations, $output);
        }
    }
}

// 4.2 Пузырьковая сортировка
function bubbleSort(&$arr, &$output, &$iterations) {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $swapped = false;
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($arr[$j] > $arr[$j + 1]) {
                $temp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
                $swapped = true;
                $iterations++;
                printArrayState($arr, $iterations, $output);
            }
        }
        if (!$swapped) break;
    }
}

// 4.3 Сортировка Шелла
function shellSort(&$arr, &$output, &$iterations) {
    $n = count($arr);
    $gap = floor($n / 2);
    while ($gap > 0) {
        for ($i = $gap; $i < $n; $i++) {
            $temp = $arr[$i];
            $j = $i;
            while ($j >= $gap && $arr[$j - $gap] > $temp) {
                $arr[$j] = $arr[$j - $gap];
                $j -= $gap;
            }
            $arr[$j] = $temp;
            $iterations++;
            printArrayState($arr, $iterations, $output);
        }
        $gap = floor($gap / 2);
    }
}

// 4.4 Сортировка гнома
function gnomeSort(&$arr, &$output, &$iterations) {
    $i = 1;
    $n = count($arr);
    while ($i < $n) {
        if ($i == 0 || $arr[$i - 1] <= $arr[$i]) {
            $i++;
        } else {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i - 1];
            $arr[$i - 1] = $temp;
            $i--;
            $iterations++;
            printArrayState($arr, $iterations, $output);
        }
    }
}

// 4.5 Быстрая сортировка
function quickSort(&$arr, $left, $right, &$output, &$iterations) {
    if ($left < $right) {
        $pivot = $arr[floor(($left + $right) / 2)];
        $l = $left;
        $r = $right;
        $changed = false;
        
        while ($l <= $r) {
            while ($arr[$l] < $pivot) $l++;
            while ($arr[$r] > $pivot) $r--;
            if ($l <= $r) {
                $temp = $arr[$l];
                $arr[$l] = $arr[$r];
                $arr[$r] = $temp;
                $l++;
                $r--;
                $changed = true;
            }
        }
        
        if ($changed) {
            $iterations++;
            printArrayState($arr, $iterations, $output);
        }
        
        quickSort($arr, $left, $r, $output, $iterations);
        quickSort($arr, $l, $right, $output, $iterations);
    }
}

function quickSortWrapper(&$arr, &$output, &$iterations) {
    quickSort($arr, 0, count($arr) - 1, $output, $iterations);
}

// 4.6 Встроенная сортировка PHP
function builtinSort(&$arr, &$output, &$iterations) {
    sort($arr);
    $iterations = 1;
    printArrayState($arr, $iterations, $output);
}

//  5. НАЗВАНИЯ АЛГОРИТМОВ 
$algorithm_names = [
    'selection' => 'Сортировка выбором',
    'bubble' => 'Пузырьковый алгоритм',
    'shell' => 'Алгоритм Шелла',
    'gnome' => 'Алгоритм садового гнома',
    'quick' => 'Быстрая сортировка',
    'builtin' => 'Встроенная функция PHP'
];

$algorithm_name = isset($algorithm_names[$algorithm]) ? $algorithm_names[$algorithm] : 'Неизвестный алгоритм';

//  6. ВЫВОД HTML 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР7 — Результат сортировки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h2>Лабораторная работа №7 — Результат сортировки</h2>
    <p>Чарыев Аллагулы | Группа 241-353</p>
</header>

<main>
    <div class="result-container">
        <h1>Результаты сортировки</h1>
        
        <div class="info-block">
            <p><strong>Алгоритм сортировки:</strong> <?php echo $algorithm_name; ?></p>
        </div>
        
        <?php if ($is_empty): ?>
            <div class="error-message">
                ❌ Ошибка: Входных данных нет. Массив пуст.
            </div>
        
        <?php elseif (!$is_valid): ?>
            <div class="error-message">
                ❌ Ошибка: Среди элементов массива есть не числа.
                <?php if (!empty($invalid_elements)): ?>
                    <br>Некорректные элементы: индексы <?php echo implode(', ', $invalid_elements); ?>
                <?php endif; ?>
            </div>
            
            <div class="input-data">
                <h3>Входные данные:</h3>
                <div class="array-display">
                    <?php foreach ($elements as $index => $value): ?>
                        <div class="array-element">
                            <?php echo $index; ?>: <?php echo htmlspecialchars($value); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        
        <?php else: ?>
            <div class="info-block">
                <p><strong>✅ Валидация пройдена:</strong> Все элементы массива — числа.</p>
            </div>
            
            <div class="input-data">
                <h3>Входные данные:</h3>
                <div class="array-display">
                    <?php foreach ($elements as $index => $value): ?>
                        <div class="array-element">
                            <?php echo $index; ?>: <?php echo htmlspecialchars($value); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php
            // Преобразуем элементы в числа
            $arr = [];
            foreach ($elements as $value) {
                $val = trim($value);
                $val = str_replace(',', '.', $val);
                $arr[] = (float)$val;
            }
            
            $output = '';
            $iterations = 0;
            
            // Выводим начальное состояние массива
            $iterations++;
            printArrayState($arr, $iterations, $output);
            
            // Засекаем время
            $start_time = microtime(true);
            
            // Запускаем выбранный алгоритм
            switch ($algorithm) {
                case 'selection':
                    selectionSort($arr, $output, $iterations);
                    break;
                case 'bubble':
                    bubbleSort($arr, $output, $iterations);
                    break;
                case 'shell':
                    shellSort($arr, $output, $iterations);
                    break;
                case 'gnome':
                    gnomeSort($arr, $output, $iterations);
                    break;
                case 'quick':
                    quickSortWrapper($arr, $output, $iterations);
                    break;
                case 'builtin':
                    builtinSort($arr, $output, $iterations);
                    break;
                default:
                    selectionSort($arr, $output, $iterations);
            }
            
            // Засекаем время окончания
            $end_time = microtime(true);
            $execution_time = $end_time - $start_time;
            ?>
            
            <div class="sorting-process">
                <h3>Процесс сортировки:</h3>
                <?php echo $output; ?>
            </div>
            
            <div class="result-summary">
                <p><strong>✅ Сортировка завершена.</strong></p>
                <p><strong>Проведено итераций:</strong> <?php echo $iterations; ?></p>
                <p><strong>Сортировка заняла:</strong> <?php echo number_format($execution_time, 6, '.', ''); ?> секунд</p>
            </div>
            
            <div class="sorted-result">
                <h3>Отсортированный массив:</h3>
                <div class="array-display">
                    <?php foreach ($arr as $index => $value): ?>
                        <div class="array-element">
                            <?php echo $index; ?>: <?php echo $value; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="back-link">
            <a href="index.php" class="back-button">← Вернуться к вводу массива</a>
        </div>
    </div>
</main>

<footer>
    Лабораторная работа №7 | Сортировка массивов
</footer>

</body>
</html>