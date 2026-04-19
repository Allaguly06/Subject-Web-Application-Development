<?php
// ============================================
// Модуль add.php — добавление записи
// ============================================

// Подключение к БД
$mysqli = new mysqli('localhost', 'php_user', '123', 'notebook');
$message = '';
$message_type = '';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $lastname = $mysqli->real_escape_string($_POST['lastname']);
    $firstname = $mysqli->real_escape_string($_POST['firstname']);
    $middlename = $mysqli->real_escape_string($_POST['middlename']);
    $gender = $mysqli->real_escape_string($_POST['gender']);
    $birthdate = $mysqli->real_escape_string($_POST['birthdate']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $address = $mysqli->real_escape_string($_POST['address']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $comment = $mysqli->real_escape_string($_POST['comment']);
    
    $query = "INSERT INTO contacts (lastname, firstname, middlename, gender, birthdate, phone, address, email, comment) 
              VALUES ('$lastname', '$firstname', '$middlename', '$gender', '$birthdate', '$phone', '$address', '$email', '$comment')";
    
    if ($mysqli->query($query)) {
        $message = '✅ Запись добавлена';
        $message_type = 'success';
    } else {
        $message = '❌ Ошибка: запись не добавлена. ' . $mysqli->error;
        $message_type = 'error';
    }
}

$mysqli->close();
?>

<div class="form-container">
    <h3>➕ Добавление новой записи</h3>
    
    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form method="post" class="contact-form">
        <input type="hidden" name="action" value="add">
        
        <div class="form-row">
            <label>Фамилия:</label>
            <input type="text" name="lastname" required>
        </div>
        <div class="form-row">
            <label>Имя:</label>
            <input type="text" name="firstname" required>
        </div>
        <div class="form-row">
            <label>Отчество:</label>
            <input type="text" name="middlename">
        </div>
        <div class="form-row">
            <label>Пол:</label>
            <select name="gender">
                <option value="М">Мужской</option>
                <option value="Ж">Женский</option>
            </select>
        </div>
        <div class="form-row">
            <label>Дата рождения:</label>
            <input type="date" name="birthdate">
        </div>
        <div class="form-row">
            <label>Телефон:</label>
            <input type="text" name="phone">
        </div>
        <div class="form-row">
            <label>Адрес:</label>
            <input type="text" name="address">
        </div>
        <div class="form-row">
            <label>E-mail:</label>
            <input type="email" name="email">
        </div>
        <div class="form-row">
            <label>Комментарий:</label>
            <textarea name="comment" rows="3"></textarea>
        </div>
        
        <div class="form-buttons">
            <button type="submit" class="submit-btn">💾 Добавить запись</button>
        </div>
    </form>
</div>