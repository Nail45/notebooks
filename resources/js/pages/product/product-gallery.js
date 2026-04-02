// product-gallery.js

// Функционал галереи для Alpine.js
function productGallery() {
  return {
    images: [],
    currentSlide: 0,

    init() {
      // Инициализация с данными из атрибута data-images
      if (this.$el.dataset.images) {
        this.images = JSON.parse(this.$el.dataset.images);
      }
    },

    prevSlide() {
      this.currentSlide = (this.currentSlide - 1 + this.images.length) % this.images.length;
    },

    nextSlide() {
      this.currentSlide = (this.currentSlide + 1) % this.images.length;
    }
  };
}

// Функционал корзины
function initCart() {
  const addToCartBtn = document.getElementById('add-to-cart-btn');
  const cartNotification = document.getElementById('cart-notification');

  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function (e) {
      e.preventDefault();

      if (this.disabled) return;

      const productId = this.dataset.productId;
      const productName = this.dataset.productName;
      const productPrice = this.dataset.productPrice;

      fetch('/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          product_id: productId,
          quantity: 1
        })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Показываем уведомление
            cartNotification.classList.remove('hidden');

            // Анимируем кнопку
            const btnText = this.querySelector('.btn-text');
            const originalText = btnText.textContent;
            btnText.textContent = 'Добавлено!';
            this.classList.add('bg-green-600', 'from-green-600', 'to-green-700');
            this.classList.remove('from-purple-600', 'to-purple-700');

            // Обновляем счетчик корзины в шапке (если есть)
            if (typeof updateCartCounter === 'function') {
              updateCartCounter(data.cartCount);
            }

            // Скрываем уведомление через 3 секунды
            setTimeout(() => {
              cartNotification.classList.add('hidden');
            }, 3000);

            // Возвращаем кнопку в исходное состояние через 2 секунды
            setTimeout(() => {
              btnText.textContent = originalText;
              this.classList.remove('bg-green-600', 'from-green-600', 'to-green-700');
              this.classList.add('from-purple-600', 'to-purple-700');
            }, 2000);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Произошла ошибка при добавлении в корзину');
        });
    });
  }
}

// Функционал показа/скрытия характеристик
function initSpecsToggle() {
  const showAllSpecsBtn = document.getElementById('show-all-specs');
  const fullSpecs = document.getElementById('full-specs');

  if (showAllSpecsBtn && fullSpecs) {
    showAllSpecsBtn.addEventListener('click', function () {
      if (fullSpecs.classList.contains('hidden')) {
        fullSpecs.classList.remove('hidden');
        this.textContent = 'Скрыть характеристики';
      } else {
        fullSpecs.classList.add('hidden');
        this.textContent = 'Показать все характеристики';
      }
    });
  }
}

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function () {
  initCart();
  initSpecsToggle();
});

// Делаем функцию доступной глобально для Alpine.js
window.productGallery = productGallery;
