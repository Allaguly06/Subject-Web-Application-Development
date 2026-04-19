<?php
session_start();

// Инициализация сессии
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
    $_SESSION['iteration'] = 0;
}
$_SESSION['iteration']++;

$result = '';
$error = '';
$expression = '';

// === 1. ФУНКЦИЯ: проверка, является ли строка числом ===
function isNumber($str) {
    if ($str === '' || $str === null) return false;
    if ($str[strlen($str) - 1] === '.') return false;
    
    // Разрешаем отрицательные числа
    $start = 0;
    if ($str[0] === '-') {
        if (strlen($str) === 1) return false;
        $start = 1;
    }
    
    $dotCount = 0;
    for ($i = $start; $i < strlen($str); $i++) {
        $ch = $str[$i];
        if ($ch !== '0' && $ch !== '1' && $ch !== '2' && $ch !== '3' &&
            $ch !== '4' && $ch !== '5' && $ch !== '6' && $ch !== '7' &&
            $ch !== '8' && $ch !== '9' && $ch !== '.') {
            return false;
        }
        if ($ch === '.') {
            $dotCount++;
            if ($dotCount > 1) return false;
        }
    }
    
    // Проверка: не начинается ли с точки
    if ($str[$start] === '.') return false;
    
    // Проверка ведущего нуля (разрешаем "0", "0.5", но не "05")
    if ($str[$start] === '0' && strlen($str) > $start + 1 && $str[$start + 1] !== '.') {
        return false;
    }
    
    return true;
}

// === 2. ФУНКЦИЯ: разбиение с учётом отрицательных чисел ===
function smartExplode($expr, $operator) {
    $result = [];
    $current = '';
    $len = strlen($expr);
    $inNumber = false;
    
    for ($i = 0; $i < $len; $i++) {
        $ch = $expr[$i];
        
        if ($ch === $operator) {
            // Проверяем, не является ли этот минус частью отрицательного числа
            if ($operator === '-') {
                // Если это первый символ или перед ним другой оператор или открывающая скобка
                if ($i === 0 || 
                    $expr[$i-1] === '+' || 
                    $expr[$i-1] === '*' || 
                    $expr[$i-1] === '/' || 
                    $expr[$i-1] === ':' ||
                    $expr[$i-1] === '(') {
                    $current .= $ch;
                    continue;
                }
            }
            // Это настоящий оператор
            if ($current !== '') {
                $result[] = $current;
                $current = '';
            }
        } else {
            $current .= $ch;
        }
    }
    
    if ($current !== '') {
        $result[] = $current;
    }
    
    return $result;
}

// === 3. ФУНКЦИЯ: вычисление выражения БЕЗ скобок ===
function calculate($expr) {
    if ($expr === '') return 'Выражение не задано';
    if (isNumber($expr)) return (float)$expr;
    
    // Сложение (самый низкий приоритет)
    $parts = smartExplode($expr, '+');
    if (count($parts) > 1) {
        $sum = 0;
        foreach ($parts as $part) {
            $val = calculate($part);
            if (!is_numeric($val)) return $val;
            $sum += $val;
        }
        return $sum;
    }
    
    // Вычитание
    $parts = smartExplode($expr, '-');
    if (count($parts) > 1) {
        $result = calculate($parts[0]);
        if (!is_numeric($result)) return $result;
        for ($i = 1; $i < count($parts); $i++) {
            $val = calculate($parts[$i]);
            if (!is_numeric($val)) return $val;
            $result -= $val;
        }
        return $result;
    }
    
    // Умножение
    $parts = explode('*', $expr);
    if (count($parts) > 1) {
        $product = 1;
        foreach ($parts as $part) {
            $val = calculate($part);
            if (!is_numeric($val)) return $val;
            $product *= $val;
        }
        return $product;
    }
    
    // Деление
    $parts = preg_split('/[\/:]/', $expr);
    if (count($parts) > 1) {
        $result = calculate($parts[0]);
        if (!is_numeric($result)) return $result;
        for ($i = 1; $i < count($parts); $i++) {
            $val = calculate($parts[$i]);
            if (!is_numeric($val)) return $val;
            if ($val == 0) return 'Деление на ноль';
            $result /= $val;
        }
        return $result;
    }
    
    return 'Недопустимые символы в выражении';
}

// === 4. ФУНКЦИЯ: проверка правильности скобок ===
function validateBrackets($expr) {
    $open = 0;
    for ($i = 0; $i < strlen($expr); $i++) {
        if ($expr[$i] === '(') {
            $open++;
        } elseif ($expr[$i] === ')') {
            $open--;
            if ($open < 0) return false;
        }
    }
    return $open === 0;
}

// === 5. ФУНКЦИЯ: вычисление выражения СО скобками ===
function calculateWithBrackets($expr) {
    // Проверка скобок
    if (!validateBrackets($expr)) {
        return 'Неправильная расстановка скобок';
    }
    
    // Поиск первой открывающей скобки
    $start = strpos($expr, '(');
    if ($start === false) {
        return calculate($expr);
    }
    
    // Поиск соответствующей закрывающей скобки
    $end = $start + 1;
    $open = 1;
    while ($open > 0 && $end < strlen($expr)) {
        if ($expr[$end] === '(') $open++;
        if ($expr[$end] === ')') $open--;
        $end++;
    }
    
    // Вычисляем содержимое скобок
    $inner = substr($expr, $start + 1, $end - $start - 2);
    $innerResult = calculateWithBrackets($inner);
    if (!is_numeric($innerResult)) return $innerResult;
    
    // Заменяем скобки на результат
    $newExpr = substr($expr, 0, $start) . $innerResult . substr($expr, $end);
    return calculateWithBrackets($newExpr);
}

// === 6. ОБРАБОТКА ФОРМЫ ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expression'])) {
    $expression = trim($_POST['expression']);
    $submittedIteration = isset($_POST['iteration']) ? (int)$_POST['iteration'] : 0;
    
    // Проверка на дублирование (F5)
    if ($submittedIteration + 1 == $_SESSION['iteration']) {
        // Убираем пробелы
        $expression = str_replace(' ', '', $expression);
        
        // Проверка на допустимые символы (добавили минус для отрицательных чисел)
        if (!preg_match('/^[0-9+\-*\/:.()]+$/', $expression)) {
            $result = 'Ошибка: недопустимые символы в выражении';
            $error = $result;
        } elseif ($expression === '') {
            $result = 'Ошибка: выражение не задано';
            $error = $result;
        } else {
            // Вычисляем
            $computed = calculateWithBrackets($expression);
            if (is_numeric($computed)) {
                $result = round($computed, 10);
                $error = '';
            } else {
                $result = $computed;
                $error = $result;
            }
        }
        
        // Сохраняем в историю
        if ($submittedIteration + 1 == $_SESSION['iteration']) {
            $_SESSION['history'][] = $expression . ' = ' . $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР10 — Калькулятор</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h2>Лабораторная работа №10</h2>
    <p>Чарыев Аллагулы | Группа 241-353</p>
</header>

<main>
    <div class="calculator-container">
        <h1>🧮 Арифметический калькулятор</h1>
        
        <?php if ($result !== ''): ?>
            <div class="result-display <?php echo $error ? 'error' : 'success'; ?>">
                <strong>Результат:</strong> <?php echo htmlspecialchars($result); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" class="calculator-form">
            <input type="hidden" name="iteration" value="<?php echo $_SESSION['iteration']; ?>">
            
            <div class="form-group">
                <label for="expression">Введите выражение:</label>
                <input type="text" 
                       name="expression" 
                       id="expression" 
                       value="<?php echo htmlspecialchars($expression); ?>" 
                       placeholder="Пример: -5+3 или 2+(-3) или 10/-2" 
                       autofocus>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="calc-btn">🔢 Вычислить</button>
            </div>
        </form>
        
        <div class="info">
            <p><strong>Поддерживаются:</strong> + - * / : ( ) и отрицательные числа</p>
            <p><strong>Примеры:</strong> -5+3, 2+(-3), 10/-2, (2+3)*-4</p>
        </div>
    </div>
</main>

<footer>
    <div class="history">
        <h3>📜 История вычислений</h3>
        <?php if (empty($_SESSION['history'])): ?>
            <p class="history-empty">История пуста</p>
        <?php else: ?>
            <?php foreach ($_SESSION['history'] as $item): ?>
                <div class="history-item"><?php echo htmlspecialchars($item); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <p class="footer-copyright">Лабораторная работа №10 | Калькулятор</p>
</footer>

</body>
</html>