// ============================================
// ЛАБОРАТОРНАЯ РАБОТА №6
// JavaScript для управления формой
// ============================================

// Функция для показа/скрытия поля email
function toggleEmail() {
    var checkbox = document.getElementById('send_mail');
    var emailDiv = document.getElementById('email_div');
    
    if (checkbox.checked) {
        emailDiv.style.display = 'block';
    } else {
        emailDiv.style.display = 'none';
    }
}

// Функция валидации формы перед отправкой
function validateForm() {
    var a = document.getElementById('A').value;
    var b = document.getElementById('B').value;
    var c = document.getElementById('C').value;
    
    if (a === '' || b === '' || c === '') {
        alert('Пожалуйста, заполните все поля A, B, C');
        return false;
    }
    
    return true;
}

// Ждём загрузки DOM, затем привязываем обработчики
document.addEventListener('DOMContentLoaded', function() {
    // Получаем элементы
    var checkbox = document.getElementById('send_mail');
    var form = document.getElementById('testForm');
    
    // Привязываем обработчик для чекбокса
    if (checkbox) {
        checkbox.addEventListener('click', toggleEmail);
        // Вызываем toggleEmail при загрузке, чтобы установить правильное состояние
        toggleEmail();
    }
    
    // Привязываем обработчик для формы
    if (form) {
        form.addEventListener('submit', validateForm);
    }
});