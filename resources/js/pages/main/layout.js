document.addEventListener('DOMContentLoaded', function () {
    // Элементы
    const filterBtns = document.querySelectorAll('.filter-btn');
    const moreManufacturersBtn = document.getElementById('moreManufacturersBtn');
    const manufacturersModal = document.getElementById('manufacturersModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const filterBtnsMobile = document.querySelectorAll('.filter-btn-mobile');
    const activeFilterText = document.getElementById('activeFilterText');
    const productCount = document.getElementById('productCount');
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    const productCards = document.querySelectorAll('.product-card');

    // Текущий активный фильтр
    let activeFilter = 'all';

    // Функция фильтрации товаров
    function filterProducts(manufacturer) {
        activeFilter = manufacturer;

        // Обновляем текст фильтра
        if (manufacturer === 'all') {
            activeFilterText.textContent = 'Показаны все производители';
        } else {
            activeFilterText.textContent = `Показаны: ${manufacturer}`;
        }

        // Симуляция фильтрации (в реальном проекте здесь AJAX запрос)
        productCards.forEach(card => {
            const cardManufacturer = card.querySelector('.text-blue-600')?.textContent;

            if (manufacturer === 'all' || cardManufacturer === manufacturer) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // Обновляем счетчик товаров
        const visibleProducts = document.querySelectorAll('.product-card[style="display: block"], .product-card:not([style])').length;
        productCount.textContent = `${visibleProducts} товаров`;

        // Обновляем стили кнопок фильтра
        filterBtns.forEach(btn => {
            if (btn.dataset.manufacturer === manufacturer) {
                btn.classList.add('active', 'bg-blue-600', 'text-white');
                btn.classList.remove('bg-white', 'text-gray-700', 'hover:bg-blue-50');
            } else {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700', 'hover:bg-blue-50');
            }
        });

        filterBtnsMobile.forEach(btn => {
            if (btn.dataset.manufacturer === manufacturer) {
                btn.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
                btn.classList.remove('bg-white', 'text-gray-700');
            } else {
                btn.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
                btn.classList.add('bg-white', 'text-gray-700');
            }
        });

        // Закрываем модальное окно на мобильных
        manufacturersModal.classList.add('hidden');
    }

    // Обработчики для кнопок фильтра
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterProducts(btn.dataset.manufacturer);
        });
    });

    filterBtnsMobile.forEach(btn => {
        btn.addEventListener('click', () => {
            filterProducts(btn.dataset.manufacturer);
        });
    });

    // Модальное окно с производителями (для мобильных)
    moreManufacturersBtn?.addEventListener('click', () => {
        manufacturersModal.classList.remove('hidden');
    });

    closeModalBtn?.addEventListener('click', () => {
        manufacturersModal.classList.add('hidden');
    });

    manufacturersModal?.addEventListener('click', (e) => {
        if (e.target === manufacturersModal) {
            manufacturersModal.classList.add('hidden');
        }
    });

    // Добавление в корзину
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = this.dataset.productId;

            // Анимация добавления
            this.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
                <span>Добавляем...</span>
            `;
            this.disabled = true;

            // Симуляция AJAX запроса
            setTimeout(() => {
                this.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>В корзине</span>
                `;
                this.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                this.classList.add('bg-green-600', 'hover:bg-green-700');

                // Обновляем счетчик корзины в реальном проекте
            }, 1000);
        });
    });

    // Инициализация
    filterProducts('all');
});

// function updateCartCount(count) {
//     const cartButton = document.querySelector('[aria-label*="Корзина"]');
//     const badge = cartButton.querySelector('.absolute'); // Бейдж
//
//     // Обновляем бейдж
//     badge.textContent = count;
//
//     // Обновляем атрибут данных
//     cartButton.setAttribute('data-cart-count', count);
//
//     // Обновляем aria-label для доступности
//     cartButton.setAttribute('aria-label', `Корзина, товаров: ${count}`);
//
//     // Можно скрывать бейдж, если количество 0
//     if (count === 0) {
//         badge.classList.add('hidden');
//     } else {
//         badge.classList.remove('hidden');
//     }
// }

// Пример использования:
// updateCartCount(1); // Установить 5 товаров
// updateCartCount(0); // Очистить корзину
