<?php
// ============================================
// Модуль delete.php — удаление записи
// ============================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli('localhost', 'php_user', '123', 'notebook');

if ($mysqli->connect_error) {
    die('Ошибка подключения: ' . $mysqli->connect_error);
}

$message = '';
$message_type = '';

// Получаем список всех записей
$result = $mysqli->query("SELECT id, lastname, firstname, middlename FROM contacts ORDER BY lastname ASC, firstname ASC");

if (!$result) {
    die('Ошибка запроса: ' . $mysqli->error);
}

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}
$result->free();

// Обработка удаления
$delete_id = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0;

if ($delete_id > 0) {
    // Получаем данные удаляемой записи для сообщения
    $info_result = $mysqli->query("SELECT lastname FROM contacts WHERE id = $delete_id");
    
    if ($info_result && $info_result->num_rows > 0) {
        $deleted = $info_result->fetch_assoc();
        
        $query = "DELETE FROM contacts WHERE id = $delete_id";
        
        if ($mysqli->query($query)) {
            $message = "✅ Запись с фамилией " . $deleted['lastname'] . " удалена";
            $message_type = 'success';
            // Обновляем страницу, чтобы убрать удалённую запись из списка
            header('Location: ?p=delete&msg=' . urlencode($message) . '&type=success');
            exit;
        } else {
            $message = "❌ Ошибка: запись не удалена. " . $mysqli->error;
            $message_type = 'error';
        }
    } else {
        $message = "❌ Запись не найдена";
        $message_type = 'error';
    }
    
    if ($info_result) $info_result->free();
}

// Проверка сообщения из редиректа
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = $_GET['msg'];
    $message_type = $_GET['type'];
}

$mysqli->close();
?>

<div class="delete-container">
    <h3>🗑️ Удаление записи</h3>
    
    <?php if ($message): ?>
        <div class="message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if (count($contacts) > 0): ?>
        <div class="contact-list">
            <h4>Выберите запись для удаления:</h4>
            <?php foreach ($contacts as $contact): ?>
                <?php 
                // Формируем инициалы
                $initials = mb_substr($contact['firstname'], 0, 1) . '.';
                if (!empty($contact['middlename'])) {
                    $initials .= mb_substr($contact['middlename'], 0, 1) . '.';
                }
                $display_name = $contact['lastname'] . ' ' . $initials;
                ?>
                <a href="?p=delete&delete_id=<?php echo $contact['id']; ?>" 
                   class="delete-link"
                   onclick="return confirm('Вы уверены, что хотите удалить запись «<?php echo htmlspecialchars($display_name); ?>»?')">
                    🗑️ <?php echo htmlspecialchars($display_name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="info">Нет записей для удаления</div>
    <?php endif; ?>
</div>