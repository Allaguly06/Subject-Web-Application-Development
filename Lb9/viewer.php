<?php
// ============================================
// Модуль viewer.php — вывод таблицы контактов
// ============================================

function getContactsList($sort, $page) {
    // Подключение к БД
    $mysqli = new mysqli('localhost', 'php_user', '123', 'notebook');
    
    if ($mysqli->connect_error) {
        return '<div class="error">Ошибка подключения к БД: ' . $mysqli->connect_error . '</div>';
    }
    
    // Определяем сортировку
    switch ($sort) {
        case 'lastname':
            $order_by = 'lastname ASC, firstname ASC';
            break;
        case 'birthdate':
            $order_by = 'birthdate ASC';
            break;
        default:
            $order_by = 'id ASC';
    }
    
    // Получаем общее количество записей
    $result = $mysqli->query("SELECT COUNT(*) as total FROM contacts");
    $total_row = $result->fetch_assoc();
    $total = $total_row['total'];
    $result->free();
    
    if ($total == 0) {
        $mysqli->close();
        return '<div class="info">В записной книжке пока нет контактов.</div>';
    }
    
    // Пагинация
    $per_page = 10;
    $total_pages = ceil($total / $per_page);
    if ($page < 0) $page = 0;
    if ($page >= $total_pages) $page = $total_pages - 1;
    $offset = $page * $per_page;
    
    // Запрос данных
    $query = "SELECT id, lastname, firstname, middlename, gender, birthdate, phone, address, email, comment 
              FROM contacts 
              ORDER BY $order_by 
              LIMIT $offset, $per_page";
    $result = $mysqli->query($query);
    
    if (!$result) {
        $mysqli->close();
        return '<div class="error">Ошибка выполнения запроса</div>';
    }
    
    // Формируем таблицу
    $html = '<table class="contacts-table" border="1">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>ID</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Пол</th>';
    $html .= '<th>Дата рождения</th><th>Телефон</th><th>Адрес</th><th>E-mail</th><th>Комментарий</th>';
    $html .= '</tr>';
    $html .= '</thead><tbody>';
    
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . htmlspecialchars($row['lastname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['firstname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['middlename']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['birthdate']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>';
    $result->free();
    
    // Пагинация
    if ($total_pages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 0; $i < $total_pages; $i++) {
            $page_num = $i + 1;
            if ($i == $page) {
                $html .= '<span class="current-page">' . $page_num . '</span>';
            } else {
                $html .= '<a href="?p=view&sort=' . $sort . '&pg=' . $i . '" class="page-link">' . $page_num . '</a>';
            }
        }
        $html .= '</div>';
    }
    
    $mysqli->close();
    return $html;
}
?>