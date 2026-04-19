<?php
// ============================================
// Модуль edit.php — редактирование записи
// ============================================

$mysqli = new mysqli('localhost', 'php_user', '123', 'notebook');
$message = '';
$message_type = '';
$current_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$current_record = null;

// Обработка отправки формы (обновление)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $lastname = $mysqli->real_escape_string($_POST['lastname']);
    $firstname = $mysqli->real_escape_string($_POST['firstname']);
    $middlename = $mysqli->real_escape_string($_POST['middlename']);
    $gender = $mysqli->real_escape_string($_POST['gender']);
    $birthdate = $mysqli->real_escape_string($_POST['birthdate']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $address = $mysqli->real_escape_string($_POST['address']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $comment = $mysqli->real_escape_string($_POST['comment']);
    
    $query = "UPDATE contacts SET 
              lastname='$lastname', firstname='$firstname', middlename='$middlename', 
              gender='$gender', birthdate='$birthdate', phone='$phone', 
              address='$address', email='$email', comment='$comment' 
              WHERE id=$id";
    
    if ($mysqli->query($query)) {
        $message = '✅ Запись успешно обновлена';
        $message_type = 'success';
        $current_id = $id;
    } else {
        $message = '❌ Ошибка: запись не обновлена. ' . $mysqli->error;
        $message_type = 'error';
    }
}

// Получаем список всех записей для ссылок
$list_result = $mysqli->query("SELECT id, lastname, firstname FROM contacts ORDER BY lastname ASC, firstname ASC");
$contacts_list = [];
while ($row = $list_result->fetch_assoc()) {
    $contacts_list[] = $row;
}

// Определяем текущую запись
if ($current_id > 0) {
    $result = $mysqli->query("SELECT * FROM contacts WHERE id = $current_id");
    if ($result && $result->num_rows > 0) {
        $current_record = $result->fetch_assoc();
    }
    if ($result) $result->free();
}

// Если текущая запись не найдена, берём первую
if (!$current_record && count($contacts_list) > 0) {
    $current_id = $contacts_list[0]['id'];
    $result = $mysqli->query("SELECT * FROM contacts WHERE id = $current_id");
    if ($result) {
        $current_record = $result->fetch_assoc();
        $result->free();
    }
}

$mysqli->close();
?>

<div class="edit-container">
    <h3>✏️ Редактирование записи</h3>
    
    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <!-- Список ссылок -->
    <div class="contact-list">
        <h4>Выберите запись для редактирования:</h4>
        <?php foreach ($contacts_list as $contact): ?>
            <?php $is_current = ($contact['id'] == $current_id); ?>
            <?php if ($is_current): ?>
                <div class="contact-item current"><?php echo htmlspecialchars($contact['lastname'] . ' ' . $contact['firstname']); ?></div>
            <?php else: ?>
                <a href="?p=edit&id=<?php echo $contact['id']; ?>" class="contact-link">
                    <?php echo htmlspecialchars($contact['lastname'] . ' ' . $contact['firstname']); ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    
    <!-- Форма редактирования -->
    <?php if ($current_record): ?>
        <form method="post" class="contact-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo $current_record['id']; ?>">
            
            <div class="form-row">
                <label>Фамилия:</label>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($current_record['lastname']); ?>" required>
            </div>
            <div class="form-row">
                <label>Имя:</label>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($current_record['firstname']); ?>" required>
            </div>
            <div class="form-row">
                <label>Отчество:</label>
                <input type="text" name="middlename" value="<?php echo htmlspecialchars($current_record['middlename']); ?>">
            </div>
            <div class="form-row">
                <label>Пол:</label>
                <select name="gender">
                    <option value="М" <?php echo ($current_record['gender'] == 'М') ? 'selected' : ''; ?>>Мужской</option>
                    <option value="Ж" <?php echo ($current_record['gender'] == 'Ж') ? 'selected' : ''; ?>>Женский</option>
                </select>
            </div>
            <div class="form-row">
                <label>Дата рождения:</label>
                <input type="date" name="birthdate" value="<?php echo htmlspecialchars($current_record['birthdate']); ?>">
            </div>
            <div class="form-row">
                <label>Телефон:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($current_record['phone']); ?>">
            </div>
            <div class="form-row">
                <label>Адрес:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($current_record['address']); ?>">
            </div>
            <div class="form-row">
                <label>E-mail:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($current_record['email']); ?>">
            </div>
            <div class="form-row">
                <label>Комментарий:</label>
                <textarea name="comment" rows="3"><?php echo htmlspecialchars($current_record['comment']); ?></textarea>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="submit-btn">💾 Сохранить изменения</button>
            </div>
        </form>
    <?php else: ?>
        <div class="info">Нет записей для редактирования</div>
    <?php endif; ?>
</div>