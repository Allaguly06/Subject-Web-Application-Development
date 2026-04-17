// Функция для добавления нового элемента массива
function addElement() {
    var table = document.getElementById('elements-table').getElementsByTagName('tbody')[0];
    var rowCount = table.rows.length;
    
    // Создаём новую строку
    var row = table.insertRow(rowCount);
    
    // Ячейка с номером элемента
    var cellNumber = row.insertCell(0);
    cellNumber.className = 'element-number';
    cellNumber.textContent = rowCount;
    
    // Ячейка с полем ввода
    var cellInput = row.insertCell(1);
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'element[]';
    input.className = 'element-input';
    input.placeholder = 'Введите число';
    cellInput.appendChild(input);
}

// Обработчик отправки формы (валидация)
function validateForm(event) {
    var inputs = document.querySelectorAll('.element-input');
    var hasValues = false;
    
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() !== '') {
            hasValues = true;
            break;
        }
    }
    
    if (!hasValues) {
        event.preventDefault();
        alert('Пожалуйста, введите хотя бы один элемент массива!');
        return false;
    }
    
    return true;
}

// Ждём загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    var addButton = document.getElementById('add-element-btn');
    var form = document.getElementById('sortForm');
    
    if (addButton) {
        addButton.addEventListener('click', addElement);
    }
    
    if (form) {
        form.addEventListener('submit', validateForm);
    }
});