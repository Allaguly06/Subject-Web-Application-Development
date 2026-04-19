<?php
// ============================================
// Модуль menu.php — формирование меню
// ============================================

function getMenu($current_page, $current_sort) {
    $menu_items = [
        'view' => 'Просмотр',
        'add' => 'Добавление записи',
        'edit' => 'Редактирование записи',
        'delete' => 'Удаление записи'
    ];
    
    $sort_items = [
        'default' => 'По умолчанию',
        'lastname' => 'По фамилии',
        'birthdate' => 'По дате рождения'
    ];
    
    $html = '<div class="main-menu">';
    
    // Основное меню
    foreach ($menu_items as $key => $label) {
        $active = ($current_page == $key) ? 'active' : '';
        $html .= '<a href="?p=' . $key . '" class="menu-btn ' . $active . '">' . $label . '</a>';
    }
    $html .= '</div>';
    
    // Подменю сортировки (только для страницы просмотра)
    if ($current_page == 'view') {
        $html .= '<div class="sub-menu">';
        $html .= '<span class="sub-label">Сортировка:</span>';
        foreach ($sort_items as $key => $label) {
            $active = ($current_sort == $key) ? 'active' : '';
            $html .= '<a href="?p=view&sort=' . $key . '" class="sub-btn ' . $active . '">' . $label . '</a>';
        }
        $html .= '</div>';
    }
    
    return $html;
}
?>