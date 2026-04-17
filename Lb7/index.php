<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР7 — Ввод массива</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h2>Лабораторная работа №7</h2>
    <p>Чарыев Аллагулы | Группа 241-353</p>
</header>

<main>
    <div class="form-container">
        <h1>Ввод массива для сортировки</h1>
        
        <form action="sort.php" method="post" target="_blank" id="sortForm">
            <div class="form-group">
                <label>Выберите алгоритм сортировки:</label>
                <select name="algorithm" id="algorithm">
                    <option value="selection">Сортировка выбором</option>
                    <option value="bubble">Пузырьковый алгоритм</option>
                    <option value="shell">Алгоритм Шелла</option>
                    <option value="gnome">Алгоритм садового гнома</option>
                    <option value="quick">Быстрая сортировка</option>
                    <option value="builtin">Встроенная функция PHP</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Элементы массива:</label>
                <table id="elements-table">
                    <tbody>
                        <tr>
                            <td class="element-number">0</td>
                            <td><input type="text" name="element[]" class="element-input" placeholder="Введите число"></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="add-element-btn" class="add-btn">+ Добавить еще один элемент</button>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="submit-btn">🔄 Сортировать массив</button>
            </div>
        </form>
    </div>
</main>

<footer>
    Лабораторная работа №7 | Ввод и сортировка массивов
</footer>

<script src="script.js"></script>
</body>
</html>