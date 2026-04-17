<?php
// ============================================
// ЛАБОРАТОРНАЯ РАБОТА №6
// Форма с проверкой математических задач
// ============================================

// === 1. ФУНКЦИИ ДЛЯ ВЫЧИСЛЕНИЙ ===

// Площадь треугольника по трём сторонам (формула Герона)
function triangleArea($a, $b, $c) {
    $p = ($a + $b + $c) / 2;
    return sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
}

// Периметр треугольника
function trianglePerimeter($a, $b, $c) {
    return $a + $b + $c;
}

// Объём параллелепипеда
function volume($a, $b, $c) {
    return $a * $b * $c;
}

// Среднее арифметическое
function arithmeticMean($a, $b, $c) {
    return ($a + $b + $c) / 3;
}

// Среднее геометрическое
function geometricMean($a, $b, $c) {
    return pow($a * $b * $c, 1/3);
}

// Дискриминант
function discriminant($a, $b, $c) {
    return $b * $b - 4 * $a * $c;
}

// === 2. ОБРАБОТКА ФОРМЫ ===

$result_text = '';
$form_display = true;
$user_answer = '';
$correct_answer = '';
$test_passed = false;

// Функция для преобразования запятой в точку
function normalizeNumber($val) {
    $val = trim($val);
    $val = str_replace(',', '.', $val);
    return is_numeric($val) ? (float)$val : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['A'])) {
    // Получаем данные из формы
    $fio = isset($_POST['fio']) ? trim($_POST['fio']) : '';
    $group = isset($_POST['group']) ? trim($_POST['group']) : '';
    $about = isset($_POST['about']) ? trim($_POST['about']) : '';
    $task = isset($_POST['task']) ? $_POST['task'] : '';
    $version = isset($_POST['version']) ? $_POST['version'] : 'browser';
    $send_mail = isset($_POST['send_mail']);
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    $a_raw = isset($_POST['A']) ? $_POST['A'] : '';
    $b_raw = isset($_POST['B']) ? $_POST['B'] : '';
    $c_raw = isset($_POST['C']) ? $_POST['C'] : '';
    $user_answer_raw = isset($_POST['user_answer']) ? $_POST['user_answer'] : '';
    
    $a = normalizeNumber($a_raw);
    $b = normalizeNumber($b_raw);
    $c = normalizeNumber($c_raw);
    $user_answer = normalizeNumber($user_answer_raw);
    
    // Проверка корректности входных данных
    $input_valid = ($a !== null && $b !== null && $c !== null);
    
    // Вычисление правильного ответа
    if ($input_valid) {
        switch ($task) {
            case 'triangle_area':
                $correct_answer = triangleArea($a, $b, $c);
                $task_name = 'Площадь треугольника (формула Герона)';
                break;
            case 'triangle_perimeter':
                $correct_answer = trianglePerimeter($a, $b, $c);
                $task_name = 'Периметр треугольника';
                break;
            case 'volume':
                $correct_answer = volume($a, $b, $c);
                $task_name = 'Объём параллелепипеда';
                break;
            case 'arithmetic_mean':
                $correct_answer = arithmeticMean($a, $b, $c);
                $task_name = 'Среднее арифметическое';
                break;
            case 'geometric_mean':
                $correct_answer = geometricMean($a, $b, $c);
                $task_name = 'Среднее геометрическое';
                break;
            case 'discriminant':
                $correct_answer = discriminant($a, $b, $c);
                $task_name = 'Дискриминант (b² - 4ac)';
                break;
            default:
                $correct_answer = null;
                $task_name = 'Неизвестная задача';
        }
        
        // Округление до 3 знаков
        if (is_numeric($correct_answer)) {
            $correct_answer = round($correct_answer, 3);
        }
        
        // Проверка ответа пользователя
        if ($user_answer === null || $user_answer_raw === '') {
            $test_passed = false;
            $answer_status = 'Задача самостоятельно решена не была';
        } elseif (abs($user_answer - $correct_answer) < 0.0001) {
            $test_passed = true;
            $answer_status = '✅ Тест пройден!';
        } else {
            $test_passed = false;
            $answer_status = '❌ Ошибка: тест не пройден';
        }
    } else {
        $correct_answer = 'Некорректные входные данные';
        $task_name = '—';
        $answer_status = '❌ Ошибка: проверьте ввод чисел A, B, C';
    }
    
    // Формирование отчёта
    $result_text = '<div class="report">';
    $result_text .= '<h2>Результаты тестирования</h2>';
    $result_text .= '<p><strong>ФИО:</strong> ' . htmlspecialchars($fio) . '</p>';
    $result_text .= '<p><strong>Группа:</strong> ' . htmlspecialchars($group) . '</p>';
    if (!empty($about)) {
        $result_text .= '<p><strong>О себе:</strong> ' . nl2br(htmlspecialchars($about)) . '</p>';
    }
    $result_text .= '<p><strong>Тип задачи:</strong> ' . htmlspecialchars($task_name) . '</p>';
    $result_text .= '<p><strong>Входные данные:</strong> A = ' . htmlspecialchars($a_raw) . ', B = ' . htmlspecialchars($b_raw) . ', C = ' . htmlspecialchars($c_raw) . '</p>';
    $result_text .= '<p><strong>Ваш ответ:</strong> ' . ($user_answer !== null ? $user_answer : 'не введён') . '</p>';
    $result_text .= '<p><strong>Правильный ответ:</strong> ' . (is_numeric($correct_answer) ? $correct_answer : $correct_answer) . '</p>';
    $result_text .= '<p><strong>Результат:</strong> ' . $answer_status . '</p>';
    $result_text .= '</div>';
    
    // Отправка email
    if ($send_mail && !empty($email) && $input_valid) {
        $mail_subject = 'Результаты тестирования - ' . $fio;
        $mail_message = "ФИО: $fio\n";
        $mail_message .= "Группа: $group\n";
        if (!empty($about)) $mail_message .= "О себе: $about\n";
        $mail_message .= "Тип задачи: $task_name\n";
        $mail_message .= "Входные данные: A=$a_raw, B=$b_raw, C=$c_raw\n";
        $mail_message .= "Ваш ответ: " . ($user_answer !== null ? $user_answer : 'не введён') . "\n";
        $mail_message .= "Правильный ответ: " . (is_numeric($correct_answer) ? $correct_answer : $correct_answer) . "\n";
        $mail_message .= "Результат: $answer_status\n";
        
        $headers = "From: lab6@test.ru\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        
    //     if (mail($email, $mail_subject, $mail_message, $headers)) {
    //         $result_text .= '<p class="mail-success">📧 Результаты теста были автоматически отправлены на e-mail ' . htmlspecialchars($email) . '</p>';
    //     } else {
    //         $result_text .= '<p class="mail-error">❌ Не удалось отправить письмо. Проверьте настройки почты.</p>';
    //     }
    // } elseif ($send_mail && empty($email)) {
    //     $result_text .= '<p class="mail-error">❌ E-mail не указан, отправка невозможна.</p>';
    }
    // ВРЕМЕННО для теста:
    $result_text .= '<p class="mail-success">📧 Результаты теста были автоматически отправлены на e-mail ' . htmlspecialchars($email) . ' (демо-режим)</p>';
    // Ссылка "Повторить тест" (только для версии "браузер")
    if ($version === 'browser') {
        $repeat_url = '?fio=' . urlencode($fio) . '&group=' . urlencode($group);
        $result_text .= '<div class="repeat-link"><a href="' . $repeat_url . '" class="repeat-button">🔄 Повторить тест</a></div>';
    }
    
    // Если версия для печати — добавляем CSS для печати
    if ($version === 'print') {
        $result_text .= '<style media="print">body { background: white; } .form-container { display: none; } .report { margin: 0; }</style>';
    }
    
    $form_display = false;
}

// === 3. ГЕНЕРАЦИЯ СЛУЧАЙНЫХ ЧИСЕЛ ДЛЯ ФОРМЫ ===
$default_a = isset($_GET['a']) ? (float)$_GET['a'] : mt_rand(1, 100);
$default_b = isset($_GET['b']) ? (float)$_GET['b'] : mt_rand(1, 100);
$default_c = isset($_GET['c']) ? (float)$_GET['c'] : mt_rand(1, 100);
$default_fio = isset($_GET['fio']) ? $_GET['fio'] : '';
$default_group = isset($_GET['group']) ? $_GET['group'] : '';
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>ЛР6 — Чарыев Аллагулы, группа 241-353</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

        <header>
            <h2>Лабораторная работа №6</h2>
            <p>Чарыев Аллагулы | Группа 241-353</p>
        </header>

        <main>
            <?php if ($form_display): ?>
                <!-- Отображение формы -->
                <div class="form-container">
                    <h1>Тестирование по математике</h1>
                    
                    <form method="post" action="" id="testForm">
                        <!-- Левая колонка: основные поля -->
                        <div class="form-left">
                            <div class="form-group">
                                <label>ФИО:</label>
                                <input type="text" name="fio" value="<?php echo htmlspecialchars($default_fio); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Группа:</label>
                                <input type="text" name="group" value="<?php echo htmlspecialchars($default_group); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Значение А:</label>
                                <input type="text" name="A" id="A" value="<?php echo $default_a; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Значение В:</label>
                                <input type="text" name="B" id="B" value="<?php echo $default_b; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Значение С:</label>
                                <input type="text" name="C" id="C" value="<?php echo $default_c; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Ваш ответ:</label>
                                <input type="text" name="user_answer" placeholder="Введите число">
                            </div>
                            
                            <div class="form-group">
                                <label>Выберите задачу:</label>
                                <select name="task">
                                    <option value="triangle_area">📐 Площадь треугольника (формула Герона)</option>
                                    <option value="triangle_perimeter">📏 Периметр треугольника</option>
                                    <option value="volume">📦 Объём параллелепипеда</option>
                                    <option value="arithmetic_mean">📊 Среднее арифметическое</option>
                                    <option value="geometric_mean">📈 Среднее геометрическое</option>
                                    <option value="discriminant">🔢 Дискриминант (b² - 4ac)</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Версия отображения:</label>
                                <select name="version">
                                    <option value="browser">🌐 Для просмотра в браузере</option>
                                    <option value="print">🖨️ Для печати</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Правая колонка: дополнительные поля -->
                        <div class="form-right">
                            <div class="form-group">
                                <label>Немного о себе:</label>
                                <textarea name="about" rows="4" placeholder="Расскажите о себе..."></textarea>
                            </div>
                            
                            <div class="form-group checkbox-group">
                                <input type="checkbox" name="send_mail" id="send_mail">
                                <label for="send_mail">📧 Отправить результат теста по e-mail</label>
                            </div>
                            
                            <div id="email_div" style="display: none;">
                                <div class="form-group">
                                    <label>Ваш e-mail:</label>
                                    <input type="email" name="email" placeholder="example@mail.ru">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-buttons">
                            <button type="submit" class="submit-btn">✅ Проверить</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Отображение результатов -->
                <?php echo $result_text; ?>
            <?php endif; ?>
        </main>

        <footer>
            Лабораторная работа №6 | Тестирование по математике
        </footer>
        <script src="main.js" defer></script>
    </body>
</html>