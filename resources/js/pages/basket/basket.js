document.addEventListener('DOMContentLoaded', function () {

  function showErrorToast(message) {
    const toast = document.getElementById('errorToast');
    const messageSpan = document.getElementById('errorMessage');

    messageSpan.textContent = message;
    toast.classList.remove('hidden');

    setTimeout(() => {
      toast.classList.add('hidden');
    }, 3000);
  }

  const dialog = document.getElementById('successDialog');
  const orderBtn = document.querySelector('button[type="submit"]');

  if (orderBtn && dialog) {
    orderBtn.addEventListener('click', function (e) {
      e.preventDefault();

      const name = document.getElementById('name')?.value.trim();
      const phone = document.getElementById('phone')?.value.trim();
      let errorMessage = '';

      if (!name) {
        errorMessage = 'Пожалуйста, введите ваше имя';
        document.getElementById('name')?.classList.add('border-red-500');
      } else if (!phone) {
        errorMessage = 'Пожалуйста, введите номер телефона';
        document.getElementById('phone')?.classList.add('border-red-500');
      } else {
        dialog.showModal();
        return;
      }

      showErrorToast(errorMessage);
    });
  }

  // Очистка ошибок при вводе
  const nameInput = document.getElementById('name');
  const phoneInput = document.getElementById('phone');

  nameInput?.addEventListener('input', function () {
    this.classList.remove('border-red-500');
  });

  phoneInput?.addEventListener('input', function () {
    this.classList.remove('border-red-500');
  });

  class CartManager {
    constructor() {
      this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      this.baseUrl = '/basket';
      this.init();
    }

    init() {
      this.initEventListeners();
      this.loadCartState();
      this.addStyles();
    }

    addStyles() {
      if (!document.querySelector('#cart-animation-style')) {
        const style = document.createElement('style');
        style.id = 'cart-animation-style';
        style.textContent = `
                        @keyframes slideIn {
                            from { transform: translateX(100%); opacity: 0; }
                            to { transform: translateX(0); opacity: 1; }
                        }
                        .animate-slideIn { animation: slideIn 0.3s ease-out; }
                        @keyframes pulse { 50% { opacity: 0.5; } }
                        .animate-pulse { animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
                        .cart-item { transition: all 0.3s ease; }
                    `;
        document.head.appendChild(style);
      }
    }

    initEventListeners() {
      // Удаление
      document.querySelectorAll('.remove-from-cart').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          this.removeFromCart(btn);
        });
      });

      // Уменьшение количества
      document.querySelectorAll('.decrease-quantity').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          this.decreaseQuantity(btn);
        });
      });

      // Увеличение количества
      document.querySelectorAll('.increase-quantity').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          this.increaseQuantity(btn);
        });
      });

      // Промокод
      const promoBtn = document.querySelector('.apply-promo');
      if (promoBtn) {
        promoBtn.addEventListener('click', (e) => {
          e.preventDefault();
          this.applyPromoCode();
        });
      }
    }

    async loadCartState() {
      try {
        const response = await fetch('/basket/state', {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        if (response.ok) {
          const data = await response.json();
          if (data.count !== undefined) this.updateCartCount(data.count);
        }
      } catch (error) {
        console.error('Error loading cart state:', error);
      }
    }

    decreaseQuantity(button) {
      const cartItem = button.closest('.cart-item');
      if (!cartItem) return;

      const itemId = cartItem.dataset.itemId;
      const quantityElement = cartItem.querySelector('.product-quantity');
      const currentQuantity = parseInt(quantityElement.textContent);

      if (currentQuantity <= 1) {
        this.showNotification('Нельзя уменьшить количество ниже 1', 'error');
        return;
      }

      this.makeRequest(`${this.baseUrl}/${itemId}/decrease`, 'PATCH')
        .then(data => this.handleQuantityResponse(data, cartItem))
        .catch(error => this.showNotification(error.message, 'error'));
    }

    increaseQuantity(button) {
      const cartItem = button.closest('.cart-item');
      if (!cartItem) return;

      const itemId = cartItem.dataset.itemId;
      const quantityElement = cartItem.querySelector('.product-quantity');
      const currentQuantity = parseInt(quantityElement.textContent);

      if (currentQuantity >= 10) {
        this.showNotification('Максимальное количество - 10 шт.', 'error');
        return;
      }

      this.makeRequest(`${this.baseUrl}/${itemId}/increase`, 'PATCH')
        .then(data => this.handleQuantityResponse(data, cartItem))
        .catch(error => this.showNotification(error.message, 'error'));
    }

    handleQuantityResponse(data, cartItem) {
      if (data.success) {
        const quantityElement = cartItem.querySelector('.product-quantity');
        quantityElement.textContent = data.new_quantity;

        const totalPriceElement = cartItem.querySelector('.product-total-price');
        if (totalPriceElement && data.item_total) {
          totalPriceElement.textContent = `${this.formatPrice(data.item_total)} ₽`;
        }

        const decreaseBtn = cartItem.querySelector('.decrease-quantity');
        const increaseBtn = cartItem.querySelector('.increase-quantity');

        if (decreaseBtn) decreaseBtn.disabled = data.new_quantity <= 1;
        if (increaseBtn) increaseBtn.disabled = data.new_quantity >= 10;

        this.updateCartSummary(data);

        if (data.cart_count !== undefined) {
          this.updateCartCount(data.cart_count);
        }

        this.showNotification(data.message || 'Количество обновлено', 'success');
      }
    }

    removeFromCart(button) {
      if (!confirm('Вы уверены, что хотите удалить товар из корзины?')) return;

      const cartItem = button.closest('.cart-item');
      if (!cartItem) return;

      const itemId = cartItem.dataset.itemId;

      this.makeRequest(`${this.baseUrl}/${itemId}`, 'DELETE')
        .then(data => {
          if (data.success) {
            this.animateRemove(cartItem);

            if (data.cart_count !== undefined) {
              this.updateCartCount(data.cart_count);
            }

            this.updateCartSummary(data);
            this.showNotification(data.message || 'Товар удален', 'success');
          }
        })
        .catch(error => this.showNotification(error.message, 'error'));
    }

    animateRemove(element) {
      element.style.transition = 'all 0.3s ease';
      element.style.height = element.offsetHeight + 'px';
      element.offsetHeight;
      element.style.height = '0';
      element.style.opacity = '0';
      element.style.overflow = 'hidden';

      setTimeout(() => {
        element.remove();
        if (document.querySelectorAll('.cart-item').length === 0) {
          this.showEmptyCart();
        }
      }, 300);
    }

    async makeRequest(url, method, body = null) {
      const response = await fetch(url, {
        method,
        headers: {
          'X-CSRF-TOKEN': this.csrfToken,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: body ? JSON.stringify(body) : null
      });

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || 'Ошибка сервера');
      }

      return response.json();
    }

    updateCartSummary(data) {
      const elements = {
        totalItems: document.getElementById('summary-total-items'),
        itemsPrice: document.getElementById('summary-items-price'),
        totalPrice: document.getElementById('summary-total-price'),
        discountRow: document.getElementById('discount-row'),
        discountAmount: document.getElementById('discount-amount')
      };

      if (elements.totalItems && data.total_items !== undefined) {
        elements.totalItems.textContent = data.total_items;
      }

      if (elements.itemsPrice && data.cart_total !== undefined) {
        elements.itemsPrice.textContent = `${this.formatPrice(data.cart_total)} ₽`;
      }

      if (elements.totalPrice && data.cart_total !== undefined) {
        elements.totalPrice.textContent = `${this.formatPrice(data.cart_total)} ₽`;
      }

      if (elements.discountRow && elements.discountAmount && data.discount_total > 0) {
        elements.discountAmount.textContent = `-${this.formatPrice(data.discount_total)} ₽`;
        elements.discountRow.style.display = 'flex';
      } else if (elements.discountRow) {
        elements.discountRow.style.display = 'none';
      }
    }

    updateCartCount(count) {
      document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = count;
        if (count > 0) {
          el.classList.remove('hidden');
          el.classList.add('flex', 'items-center', 'justify-center');
        } else {
          el.classList.add('hidden');
          el.classList.remove('flex', 'items-center', 'justify-center');
        }
      });

      const cartLink = document.querySelector('a[href*="basket"], a[href*="cart"]');
      if (cartLink) {
        const plural = this.getPlural(count);
        cartLink.setAttribute('title', `Корзина: ${count} товар${plural}`);
      }
    }

    showEmptyCart() {
      const container = document.getElementById('cart-items-container');
      if (container) {
        container.innerHTML = `
                        <div class="empty-cart p-12 text-center">
                            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h2 class="text-2xl font-semibold text-gray-700 mb-2">Корзина пуста</h2>
                            <p class="text-gray-500 mb-6">Добавьте товары, чтобы оформить заказ</p>
                            <a href="{{ route('products.index') }}"
                               class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300">
                                Перейти в каталог
                            </a>
                        </div>
                    `;
      }
    }

    formatPrice(price) {
      return new Intl.NumberFormat('ru-RU').format(Math.round(price));
    }

    getPlural(number) {
      const lastDigit = number % 10;
      const lastTwoDigits = number % 100;

      if (lastDigit === 1 && lastTwoDigits !== 11) return '';
      if (lastDigit >= 2 && lastDigit <= 4 && (lastTwoDigits < 10 || lastTwoDigits >= 20)) return 'а';
      return 'ов';
    }

    showNotification(message, type = 'success') {
      const notificationId = 'cart-notification-' + Date.now();
      const notification = document.createElement('div');
      notification.id = notificationId;
      notification.className = `fixed top-4 right-4 z-50 animate-slideIn px-4 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'
      }`;

      const icon = type === 'success'
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';

      notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="h-5 w-5 ${type === 'success' ? 'text-green-500' : 'text-red-500'} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${icon}
                        </svg>
                        <span>${message}</span>
                    </div>
                `;

      document.body.appendChild(notification);

      setTimeout(() => {
        const notif = document.getElementById(notificationId);
        if (notif) {
          notif.style.opacity = '0';
          notif.style.transform = 'translateX(100%)';
          notif.style.transition = 'all 0.3s ease';
          setTimeout(() => notif.remove(), 300);
        }
      }, 3000);
    }

    applyPromoCode() {
      const input = document.getElementById('promo-code');
      const code = input?.value.trim();
      const messageDiv = document.getElementById('promo-message');

      if (!code) {
        this.showNotification('Введите промокод', 'error');
        return;
      }

      this.makeRequest('/basket/apply-promo', 'POST', {code})
        .then(data => {
          if (data.success) {
            if (messageDiv) {
              messageDiv.textContent = data.message;
              messageDiv.className = 'mt-2 text-sm text-green-600';
              messageDiv.classList.remove('hidden');
            }
            this.updateCartSummary(data);
            this.showNotification(data.message, 'success');
          } else {
            if (messageDiv) {
              messageDiv.textContent = data.message;
              messageDiv.className = 'mt-2 text-sm text-red-600';
              messageDiv.classList.remove('hidden');
            }
            this.showNotification(data.message, 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          this.showNotification('Ошибка применения промокода', 'error');
        });
    }
  }

  // Инициализация
  new CartManager();
});
