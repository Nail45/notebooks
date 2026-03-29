// Глобальная переменная для хранения состояния корзины
let cartState = {
  items: {},
  count: 0
};

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', function () {
  // Навешиваем обработчики на все кнопки
  document.querySelectorAll('[id^="cart-btn-"]').forEach(button => {
    const notebookId = button.id.replace('cart-btn-', '');
    button.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      addToCart(notebookId, button);
    });
  });

  // Загружаем состояние корзины с сервера
  loadCartState().then(() => {
    // После загрузки состояния обновляем все кнопки
    updateAllCartButtons();

    // Обновляем счетчик
    updateCartCounter(cartState.count);
  });
});

// Загрузка состояния корзины с сервера
async function loadCartState() {
  try {
    const response = await fetch('/basket/state', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (response.ok) {
      cartState = await response.json();
      console.log('Загружено состояние корзины:', cartState);
    }
  } catch (error) {
    console.error('Ошибка загрузки состояния корзины:', error);
  }
}

// Обновление ВСЕХ кнопок на странице
function updateAllCartButtons() {
  document.querySelectorAll('[id^="cart-btn-"]').forEach(button => {
    const notebookId = button.id.replace('cart-btn-', '');

    // Если товар есть в корзине - обновляем кнопку
    if (cartState.items && cartState.items[notebookId]) {
      updateButtonToInCart(button);
    } else {
      // Если товара нет в корзине - сбрасываем кнопку
      resetButtonToDefault(button);
    }
  });
}

// Основная функция добавления в корзину
function addToCart(notebookId, button) {
  console.log('Добавляем товар ID:', notebookId);

  // Проверяем, не добавлен ли уже
  if (cartState.items && cartState.items[notebookId]) {
    showNotification('Товар уже в корзине', 'info');
    return;
  }

  // Сохраняем оригинальное состояние
  const originalHTML = button.innerHTML;
  const originalClass = button.className;

  // Показываем загрузку
  button.innerHTML = 'Добавляем...';
  button.disabled = true;

  // Подготавливаем данные
  const data = new FormData();
  data.append('notebook_id', notebookId);
  data.append('count', 1);
  data.append('_token', '{{ csrf_token() }}');

  // Отправляем запрос
  fetch('{{ route("basket.store") }}', {
    method: 'POST',
    body: data,
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(response => {
      console.log('Response status:', response.status);
      return response.json().then(data => ({status: response.status, data}));
    })
    .then(({status, data}) => {
      console.log('Response data:', data);

      if (status === 200 && data.success) {
        // Успех - обновляем состояние
        updateButtonToInCart(button);
        button.disabled = false;

        // Обновляем глобальное состояние
        cartState.items[notebookId] = {id: notebookId, count: 1};

        // Обновляем счетчик
        if (data.cart_count !== undefined) {
          cartState.count = data.cart_count;
          updateCartCounter(data.cart_count);
        }

        showNotification(data.message || 'Товар добавлен!', 'success');
      } else {
        throw new Error(data.message || 'Ошибка сервера');
      }
    })
    .catch(error => {
      console.error('Error:', error);

      // Восстанавливаем кнопку
      button.innerHTML = originalHTML;
      button.className = originalClass;
      button.disabled = false;

      // Показываем ошибку
      showNotification(error.message || 'Ошибка при добавлении', 'error');
    });
}

// Функция для обновления кнопки в состояние "В корзине"
function updateButtonToInCart(button) {
  button.innerHTML = `
            <svg class="h-3.5 w-3.5 mr-1.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            В корзине
        `;
  button.className = button.className
    .replace('from-purple-600 to-purple-700', 'from-green-600 to-green-700')
    .replace('hover:from-purple-700 hover:to-purple-800', 'hover:from-green-700 hover:to-green-800');
  button.classList.add('in-cart');
}

// Функция для сброса кнопки в исходное состояние
function resetButtonToDefault(button) {
  button.innerHTML = `
            <svg class="h-3.5 w-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            В корзину
        `;
  button.className = button.className
    .replace('from-green-600 to-green-700', 'from-purple-600 to-purple-700')
    .replace('hover:from-green-700 hover:to-green-800', 'hover:from-purple-700 hover:to-purple-800');
  button.classList.remove('in-cart');
}

// Обновление счетчика в шапке
function updateCartCounter(count) {
  console.log('Обновляем счетчик на:', count);

  const counters = document.querySelectorAll('.cart-count, [class*="cart-count"], [id*="cart-badge"]');

  counters.forEach(counter => {
    counter.textContent = count;
    if (count > 0) {
      counter.classList.remove('hidden');
      counter.style.display = 'flex';
    } else {
      counter.classList.add('hidden');
      counter.style.display = 'none';
    }
  });
}

// Показ уведомлений
function showNotification(message, type = 'success') {
  const colors = {
    success: {bg: 'bg-green-100', border: 'border-green-400', text: 'text-green-700', icon: 'text-green-500'},
    error: {bg: 'bg-red-100', border: 'border-red-400', text: 'text-red-700', icon: 'text-red-500'},
    info: {bg: 'bg-blue-100', border: 'border-blue-400', text: 'text-blue-700', icon: 'text-blue-500'}
  };

  const color = colors[type] || colors.success;

  const notification = document.createElement('div');
  notification.className = `fixed top-4 right-4 z-50 ${color.bg} ${color.border} ${color.text} px-4 py-3 rounded-lg shadow-lg`;
  notification.innerHTML = `
            <div class="flex items-center">
                <svg class="h-5 w-5 ${color.icon} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' :
    type === 'error' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' :
      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
                </svg>
                <span>${message}</span>
            </div>
        `;

  document.body.appendChild(notification);

  setTimeout(() => {
    notification.remove();
  }, 3000);
}
