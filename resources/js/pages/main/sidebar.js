// Функция открытия фильтров
function openFiltersSidebar() {
    const sidebar = document.getElementById('filters-sidebar');
    const overlay = document.getElementById('filters-overlay');

    if (sidebar && overlay) {
        // Убираем класс, который скрывает сайдбар
        sidebar.classList.remove('translate-x-full');
        // Показываем оверлей
        overlay.classList.remove('hidden');
        // Блокируем скролл страницы
        document.body.style.overflow = 'hidden';

    } else {
        console.error('Не найден сайдбар или оверлей');
    }
}

// Функция закрытия фильтров
function closeFiltersSidebar() {
    const sidebar = document.getElementById('filters-sidebar');
    const overlay = document.getElementById('filters-overlay');

    if (sidebar && overlay) {
        // Добавляем класс, который скрывает сайдбар
        sidebar.classList.add('translate-x-full');
        // Скрываем оверлей
        overlay.classList.add('hidden');
        // Разблокируем скролл
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Переменные состояния
    let currentManufacturer = 'all';
    let currentSort = 'default';
    let isLoading = false;

    // Инициализация из URL
    const urlParams = new URLSearchParams(window.location.search);
    currentManufacturer = urlParams.get('manufacturer') || 'all';
    currentSort = urlParams.get('sort') || 'default';

    // === ОСНОВНЫЕ ФУНКЦИИ ===

    // Функция обновления активной кнопки производителя
    function updateActiveManufacturerButton(manufacturer) {
        const buttons = document.querySelectorAll('.manufacturer-filter-btn');
        if (!buttons.length) return;

        buttons.forEach(btn => {
            const btnManufacturer = btn.getAttribute('data-manufacturer');
            const isActive = btnManufacturer === manufacturer;

            if (isActive) {
                // Активная кнопка
                btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200', 'hover:bg-blue-50', 'hover:text-blue-600', 'hover:border-blue-200');
                btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
            } else {
                // Неактивная кнопка
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200', 'hover:bg-blue-50', 'hover:text-blue-600', 'hover:border-blue-200');
            }
        });
    }

    // Функция обновления информации о фильтре
    function updateFilterInfo(manufacturer) {
        const activeFilterText = document.getElementById('activeFilterText');
        if (activeFilterText) {
            if (manufacturer === 'all') {
                activeFilterText.textContent = 'Показаны все производители';
            } else {
                activeFilterText.textContent = 'Производитель: ' + manufacturer;
            }
        }
    }

    // Функция закрытия меню сортировки
    function closeSortDropdown() {
        const sortDropdownMenu = document.getElementById('sort-dropdown-menu');
        const sortDropdownButton = document.getElementById('sort-dropdown-button');

        if (sortDropdownMenu) {
            sortDropdownMenu.classList.add('hidden');
        }

        if (sortDropdownButton) {
            sortDropdownButton.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
        }
    }

    // Функция показа индикатора загрузки
    function showLoadingIndicator() {
        const container = document.getElementById('products-container');
        if (container) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600"></div>
                    <div class="mt-2 text-gray-600">Загрузка товаров...</div>
                </div>
            `;
        }
    }

    // Функция для склонения слова "товар"
    function getPluralEnding(number) {
        const n = Math.abs(number) % 100;
        const n1 = n % 10;

        if (n > 10 && n < 20) return 'ов';
        if (n1 === 1) return '';
        if (n1 >= 2 && n1 <= 4) return 'а';
        return 'ов';
    }

    // Основная функция загрузки товаров
    function loadProducts(page = 1) {
        if (isLoading) return;

        isLoading = true;
        showLoadingIndicator();

        // Формируем URL с параметрами
        const url = new URL(window.location.origin + window.location.pathname);

        // Добавляем все параметры из текущего URL
        const currentParams = new URLSearchParams(window.location.search);
        for (const [key, value] of currentParams.entries()) {
            if (key !== 'page') {
                url.searchParams.set(key, value);
            }
        }

        // Обновляем специфичные параметры
        if (currentManufacturer && currentManufacturer !== 'all') {
            url.searchParams.set('manufacturer', currentManufacturer);
        } else {
            url.searchParams.delete('manufacturer');
        }

        if (currentSort && currentSort !== 'default') {
            url.searchParams.set('sort', currentSort);
        } else {
            url.searchParams.delete('sort');
        }

        // Добавляем номер страницы
        if (page > 1) {
            url.searchParams.set('page', page);
        } else {
            url.searchParams.delete('page');
        }

        // Отправляем AJAX запрос
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                // Проверяем Content-Type
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Если ответ не JSON, получаем текст для диагностики
                    return response.text().then(text => {
                        console.error('Expected JSON but got:', text.substring(0, 200));
                        throw new Error('Server returned non-JSON response');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(data.error || 'Request failed');
                }

                // Обновляем контент
                const container = document.getElementById('products-container');
                if (container && data.products_html) {
                    container.innerHTML = data.products_html;
                }

                const paginationContainer = document.getElementById('pagination-container');
                if (paginationContainer) {
                    paginationContainer.innerHTML = data.pagination_html || '';
                    attachPaginationListeners();
                }

                // Обновляем информацию
                updateFilterInfo(data.active_filters || {});
                updateActiveManufacturerButton(data.active_manufacturer);
                updateActiveSort(data.active_sort);

                // Обновляем историю
                window.history.pushState({
                    manufacturer: data.active_manufacturer,
                    sort: data.active_sort,
                    page: data.current_page,
                    filters: data.active_filters || {}
                }, '', url.toString());

                // Обновляем текущие значения
                currentManufacturer = data.active_manufacturer;
                currentSort = data.active_sort;

                // Показываем активные фильтры
                if (data.filter_count > 0) {
                    // Используем существующую функцию или создаем новую
                    if (typeof showActiveFilters === 'function') {
                        showActiveFilters(data.active_filters, data.filter_count);
                    }
                } else {
                    // Если функция hideActiveFilters не существует, просто скрываем контейнер
                    const filterContainer = document.getElementById('active-filters-container');
                    if (filterContainer) {
                        filterContainer.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Ошибка загрузки товаров:', error);

                // Показываем сообщение об ошибке пользователю
                const container = document.getElementById('products-container');
                if (container) {
                    container.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <p class="font-semibold">Ошибка загрузки товаров</p>
                    <p class="text-sm mt-2">${error.message}</p>
                    <button onclick="window.location.reload()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">
                        Обновить страницу
                    </button>
                </div>
            `;
                }

                // Скрываем индикатор загрузки
                hideLoadingIndicator();
            })
            .finally(() => {
                isLoading = false;
                hideLoadingIndicator();
            });
    }

// Функции для показа/скрытия индикатора загрузки
    function showLoadingIndicator() {
        let indicator = document.getElementById('loading-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'loading-indicator';
            indicator.className = 'fixed top-0 left-0 w-full h-1 bg-blue-500 z-50';
            indicator.innerHTML = `
            <div class="h-full bg-blue-600 animate-pulse"></div>
        `;
            document.body.appendChild(indicator);
        }
        indicator.style.display = 'block';
    }

    function hideLoadingIndicator() {
        const indicator = document.getElementById('loading-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }
    }

    // function showActiveFilters(filters, count) {
    //     const filterContainer = document.getElementById('active-filters-container');
    //     if (!filterContainer) return;
    //
    //     let html = `<div class="flex flex-wrap items-center gap-2 mb-4 p-3 bg-gray-50 rounded-lg">
    //     <span class="font-medium text-gray-700">Активные фильтры (${count}):</span>`;
    //
    //     Object.entries(filters).forEach(([key, value]) => {
    //         const displayName = getFilterDisplayName(key);
    //         const displayValue = getFilterValueDisplayName(key, value);
    //
    //         html += `
    //         <span class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-gray-300 rounded-full text-sm">
    //             ${displayName}: ${displayValue}
    //             <button onclick="removeFilter('${key}')" class="ml-1 text-gray-500 hover:text-red-500">
    //                 ×
    //             </button>
    //         </span>
    //     `;
    //     });
    //
    //     html += `</div>`;
    //     filterContainer.innerHTML = html;
    //     filterContainer.style.display = 'block';
    // }

    function getFilterDisplayName(key) {
        const names = {
            'min_price': 'Мин. цена',
            'max_price': 'Макс. цена',
            'release_year_from': 'Год от',
            'release_year_to': 'Год до',
            'screen_size': 'Диагональ',
            'resolution': 'Разрешение',
            'ram': 'ОЗУ',
            'ram_type': 'Тип ОЗУ',
            'processor_series': 'Серия процессора',
            'processor_model': 'Модель процессора',
            'cores': 'Ядра',
            'gpu_type': 'Тип видеокарты',
            'gpu_model': 'Модель видеокарты',
            'storage_config': 'Конфигурация дисков',
            'ssd_capacity': 'Объем SSD',
            'hdd_capacity': 'Объем HDD',
            'os': 'ОС'
        };

        return names[key] || key;
    }

    function getFilterValueDisplayName(key, value) {
        if (Array.isArray(value)) {
            return value.map(v => getSingleValueDisplayName(key, v)).join(', ');
        }
        return getSingleValueDisplayName(key, value);
    }

    function getSingleValueDisplayName(key, value) {
        // Добавьте преобразования значений если нужно
        return value;
    }

    function removeFilter(filterName) {
        const url = new URL(window.location);
        url.searchParams.delete(filterName);

        // Если это фильтр производителя, сбрасываем его
        if (filterName === 'manufacturer') {
            currentManufacturer = 'all';
        }

        // Перезагружаем страницу с новыми параметрами
        loadProducts(1);
    }

// Функция для обновления информации о фильтрах
    function updateFilterInfo(filters) {
        const filterInfo = document.getElementById('filter-info');
        if (filterInfo) {
            const count = Object.keys(filters).length;
            if (count > 0) {
                filterInfo.textContent = `Активных фильтров: ${count}`;
                filterInfo.style.display = 'block';
            } else {
                filterInfo.style.display = 'none';
            }
        }
    }

// Функция для обновления активной сортировки в UI
    function updateActiveSort(sort) {
        const sortButtons = document.querySelectorAll('.sort-button');
        sortButtons.forEach(button => {
            button.classList.remove('active');
            if (button.dataset.sort === sort) {
                button.classList.add('active');
            }
        });
    }

// Функция для обновления активного производителя в UI
    function updateActiveManufacturerButton(manufacturer) {
        const manufacturerButtons = document.querySelectorAll('.manufacturer-button');
        manufacturerButtons.forEach(button => {
            button.classList.remove('active');
            if (button.dataset.manufacturer === manufacturer) {
                button.classList.add('active');
            }
        });
    }

// Функции для показа/скрытия индикатора загрузки
    function showLoadingIndicator() {
        let indicator = document.getElementById('loading-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'loading-indicator';
            indicator.className = 'fixed top-0 left-0 w-full h-1 bg-blue-500 z-50';
            indicator.innerHTML = `
            <div class="h-full bg-blue-600 animate-pulse"></div>
        `;
            document.body.appendChild(indicator);
        }
        indicator.style.display = 'block';
    }

    function hideLoadingIndicator() {
        const indicator = document.getElementById('loading-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }
    }

    // Функция обновления активной сортировки
    function updateActiveSort(sort) {
        const currentSortText = document.getElementById('current-sort-text');
        if (!currentSortText) return;

        const activeSortBtn = document.querySelector(`.sort-option-btn[data-sort="${sort}"]`);
        if (activeSortBtn) {
            currentSortText.textContent = activeSortBtn.textContent.trim();

            // Обновляем активный элемент в меню
            document.querySelectorAll('.sort-option-btn').forEach(btn => {
                btn.classList.remove('bg-blue-100', 'text-blue-700');
            });
            activeSortBtn.classList.add('bg-blue-100', 'text-blue-700');
        }
    }

    // Функция для прикрепления обработчиков к пагинации
    function attachPaginationListeners() {
        const paginationContainer = document.getElementById('pagination-container');
        if (!paginationContainer) return;

        // Используем делегирование событий
        paginationContainer.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (!link) return;

            e.preventDefault();

            if (isLoading) return;

            const href = link.getAttribute('href');
            if (!href || href === '#') return;

            // Извлекаем номер страницы из URL
            const url = new URL(href, window.location.origin);
            const page = url.searchParams.get('page') || 1;

            // Загружаем указанную страницу
            loadProducts(parseInt(page));
        });
    }

    // Глобальная функция сброса фильтров
    window.resetFilters = function () {
        currentManufacturer = 'all';
        currentSort = 'default';

        updateActiveManufacturerButton('all');
        updateFilterInfo('all');
        updateActiveSort('default');

        loadProducts(1);
    };

    // === ИНИЦИАЛИЗАЦИЯ ПРИ ЗАГРУЗКЕ СТРАНИЦЫ ===

    function initializePage() {
        // Устанавливаем активного производителя
        updateActiveManufacturerButton(currentManufacturer);
        updateFilterInfo(currentManufacturer);
        updateActiveSort(currentSort);

        // Инициализация сортировки
        const sortDropdownButton = document.getElementById('sort-dropdown-button');
        const sortDropdownMenu = document.getElementById('sort-dropdown-menu');

        if (sortDropdownButton && sortDropdownMenu) {
            // Открытие/закрытие меню сортировки
            sortDropdownButton.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const isHidden = sortDropdownMenu.classList.toggle('hidden');

                if (!isHidden) {
                    this.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
                } else {
                    this.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
                }
            });

            // Закрытие меню при клике вне его
            document.addEventListener('click', function (e) {
                const isClickInsideMenu = sortDropdownMenu.contains(e.target);
                const isClickOnButton = sortDropdownButton.contains(e.target);

                if (!isClickInsideMenu && !isClickOnButton) {
                    closeSortDropdown();
                }
            });

            // Закрытие меню при нажатии Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeSortDropdown();
                }
            });

            // Обработка выбора опции сортировки
            document.querySelectorAll('.sort-option-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (isLoading) return;

                    const newSort = this.getAttribute('data-sort');
                    if (newSort === currentSort) {
                        closeSortDropdown();
                        return;
                    }

                    currentSort = newSort;

                    // Сразу закрываем меню
                    closeSortDropdown();

                    // Загружаем товары с новой сортировкой
                    loadProducts(1);
                });
            });
        }

        // Обработчики фильтров производителей
        document.querySelectorAll('.manufacturer-filter-btn').forEach(button => {
            button.addEventListener('click', function () {
                if (isLoading) return;

                currentManufacturer = this.getAttribute('data-manufacturer');
                loadProducts(1);
            });
        });

        // Инициализация пагинации
        attachPaginationListeners();

        // Кнопка "Еще" для мобильных
        const moreBtn = document.getElementById('moreManufacturersBtn');
        if (moreBtn) {
            moreBtn.addEventListener('click', function () {
                const hiddenOnMobile = document.querySelectorAll('.hidden.md\\:contents');
                hiddenOnMobile.forEach(element => {
                    element.classList.remove('hidden');
                });
                moreBtn.style.display = 'none';
            });
        }

        // Извлекаем текущую страницу из URL и загружаем данные
        const currentPage = urlParams.get('page') || 1;
        if (window.location.search.includes('page') || window.location.search.includes('manufacturer') || window.location.search.includes('sort')) {
            // Если есть параметры в URL, загружаем через AJAX для единообразия
            loadProducts(parseInt(currentPage));
        }
    }

    // Запускаем инициализацию
    initializePage();

    // Обработка кнопки "назад/вперед" в браузере
    window.addEventListener('popstate', function (event) {
        const urlParams = new URLSearchParams(window.location.search);
        const newManufacturer = urlParams.get('manufacturer') || 'all';
        const newSort = urlParams.get('sort') || 'default';
        const newPage = urlParams.get('page') || 1;

        currentManufacturer = newManufacturer;
        currentSort = newSort;

        updateActiveManufacturerButton(currentManufacturer);
        updateFilterInfo(currentManufacturer);
        updateActiveSort(currentSort);

        loadProducts(parseInt(newPage));
    });
});


// Альтернативный вариант для динамически добавляемых кнопок
document.addEventListener('click', function (e) {
    // Проверяем, была ли нажата кнопка фильтров или её дочерние элементы
    const filterButton = e.target.closest('[data-testid="open-products-filters"]');
    if (filterButton) {
        e.preventDefault();
        openFiltersSidebar();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    let currentManufacturer = 'all';

    // Обработчик для кнопок фильтра
    document.querySelectorAll('.manufacturer-filter-btn').forEach(button => {
        button.addEventListener('click', function () {
            const manufacturer = this.getAttribute('data-manufacturer');

            // Обновляем активную кнопку
            updateActiveButton(manufacturer);

            // Сохраняем текущий производитель
            currentManufacturer = manufacturer;

            // Обновляем информацию о фильтре
            updateFilterInfo(manufacturer);

            // Загружаем товары
            loadProducts(manufacturer);

            // Обновляем URL в браузере без перезагрузки
            updateUrl(manufacturer);
        });
    });

    // Функция обновления активной кнопки
    function updateActiveButton(manufacturer) {
        document.querySelectorAll('.manufacturer-filter-btn').forEach(btn => {
            const btnManufacturer = btn.getAttribute('data-manufacturer');
            if (btnManufacturer === manufacturer) {
                // Активная кнопка
                btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200', 'hover:bg-blue-50', 'hover:text-blue-600', 'hover:border-blue-200');
                btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
            } else {
                // Неактивная кнопка
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200', 'hover:bg-blue-50', 'hover:text-blue-600', 'hover:border-blue-200');
            }
        });
    }

    // Функция обновления информации о фильтре
    function updateFilterInfo(manufacturer) {
        const activeFilterText = document.getElementById('activeFilterText');
        if (activeFilterText) {
            if (manufacturer === 'all') {
                activeFilterText.textContent = 'Показаны все производители';
            } else {
                activeFilterText.textContent = 'Производитель: ' + manufacturer;
            }
        }
    }

    // Функция загрузки товаров через AJAX
    function loadProducts(manufacturer) {
        // Показываем индикатор загрузки
        const container = document.getElementById('products-container');
        container.innerHTML = '<div class="text-center py-12"><div class="spinner-border text-blue-600" role="status"><span class="sr-only">Загрузка...</span></div></div>';

        // Формируем URL с параметрами
        const url = new URL(window.location.href);
        url.searchParams.set('manufacturer', manufacturer);
        url.searchParams.set('ajax', '1'); // Добавляем флаг AJAX

        // Отправляем AJAX запрос
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                // Обновляем список товаров
                if (container) {
                    container.innerHTML = data.products_html;
                }

                // Обновляем пагинацию
                const paginationContainer = document.getElementById('pagination-container');
                if (paginationContainer && data.pagination_html) {
                    paginationContainer.innerHTML = data.pagination_html;
                }

                // Обновляем количество товаров
                const productCount = document.getElementById('productCount');
                if (productCount && data.total !== undefined) {
                    const total = data.total;
                    productCount.textContent = total + ' товар' + getPluralEnding(total);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-center py-12 text-red-600">Произошла ошибка при загрузке товаров</div>';
            });
    }

    // Функция обновления URL без перезагрузки
    function updateUrl(manufacturer) {
        const url = new URL(window.location.href);
        if (manufacturer === 'all') {
            url.searchParams.delete('manufacturer');
        } else {
            url.searchParams.set('manufacturer', manufacturer);
        }

        // Обновляем URL в адресной строке без перезагрузки
        window.history.pushState({manufacturer: manufacturer}, '', url);
    }

    // Функция для склонения слова "товар"
    function getPluralEnding(number) {
        if (number % 10 === 1 && number % 100 !== 11) {
            return '';
        } else if (number % 10 >= 2 && number % 10 <= 4 && (number % 100 < 10 || number % 100 >= 20)) {
            return 'а';
        } else {
            return 'ов';
        }
    }

    // Обработка кнопки "назад/вперед" в браузере
    window.addEventListener('popstate', function (event) {
        if (event.state && event.state.manufacturer) {
            updateActiveButton(event.state.manufacturer);
            updateFilterInfo(event.state.manufacturer);
            loadProducts(event.state.manufacturer);
        }
    });

    // Кнопка "Еще" для мобильных
    const moreBtn = document.getElementById('moreManufacturersBtn');
    if (moreBtn) {
        moreBtn.addEventListener('click', function () {
            const hiddenOnMobile = document.querySelectorAll('.hidden.md\\:contents');
            hiddenOnMobile.forEach(element => {
                element.classList.remove('hidden');
                element.classList.add('contents');
            });
            moreBtn.style.display = 'none';
        });
    }

    // При загрузке страницы устанавливаем активную кнопку из URL
    const urlParams = new URLSearchParams(window.location.search);
    const initialManufacturer = urlParams.get('manufacturer') || 'all';
    if (initialManufacturer !== 'all') {
        updateActiveButton(initialManufacturer);
        updateFilterInfo(initialManufacturer);
    }
});

// Кнопка "Еще" для мобильных
const moreBtn = document.getElementById('moreManufacturersBtn');
if (moreBtn) {
    moreBtn.addEventListener('click', function () {
        const hiddenOnMobile = document.querySelectorAll('.hidden.md\\:block');
        hiddenOnMobile.forEach(element => {
            element.classList.remove('hidden');
        });
        moreBtn.style.display = 'none';
    });
}

// Фильтр

// Открытие/закрытие сайдбара
function toggleSidebar(show = null) {
    const sidebar = document.getElementById('filters-sidebar');
    const overlay = document.getElementById('filters-overlay');

    if (show === null) {
        show = sidebar.classList.contains('translate-x-full');
    }

    if (show) {
        sidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Блокируем прокрутку фона
    } else {
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = ''; // Восстанавливаем прокрутку
    }
}

// Открытие/закрытие отдельных фильтров
function toggleFilter(filterId) {
    const filterContent = document.getElementById(filterId);
    const filterIcon = document.getElementById(`${filterId}-icon`);

    if (filterContent) {
        filterContent.classList.toggle('hidden');

        if (filterIcon) {
            if (filterContent.classList.contains('hidden')) {
                filterIcon.style.transform = 'rotate(0deg)';
            } else {
                filterIcon.style.transform = 'rotate(180deg)';
            }
        }
    }
}

// Сброс всех фильтров
function resetAllFilters() {
    // Сбрасываем все чекбоксы
    const allCheckboxes = document.querySelectorAll('.filter-content input[type="checkbox"]');
    allCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });

    // Сбрасываем все числовые поля
    const allNumberInputs = document.querySelectorAll('.filter-content input[type="number"]');
    allNumberInputs.forEach(input => {
        input.value = '';
    });

    // Сбрасываем текстовые поля
    const allTextInputs = document.querySelectorAll('.filter-content input[type="text"]');
    allTextInputs.forEach(input => {
        input.value = '';
    });

    // Скрываем все открытые фильтры (опционально)
    const allFilterContents = document.querySelectorAll('.filter-content');
    const allFilterIcons = document.querySelectorAll('[id$="-icon"]');

    allFilterContents.forEach(content => {
        content.classList.add('hidden');
    });

    allFilterIcons.forEach(icon => {
        icon.style.transform = 'rotate(0deg)';
    });

    console.log('Все фильтры сброшены');
}

// Получение выбранных фильтров
function getSelectedFilters() {
    const filters = {};

    // Собираем данные из чекбоксов
    document.querySelectorAll('.filter-content input[type="checkbox"]:checked').forEach(checkbox => {
        const name = checkbox.name;
        const value = checkbox.value || checkbox.nextElementSibling?.textContent?.trim();

        if (name) {
            if (!filters[name]) {
                filters[name] = [];
            }
            filters[name].push(value);
        }
    });

    // Собираем данные из числовых полей
    document.querySelectorAll('.filter-content input[type="number"]').forEach(input => {
        if (input.value) {
            const name = input.name || input.previousElementSibling?.textContent?.trim().toLowerCase();
            if (name) {
                filters[name] = input.value;
            }
        }
    });

    // Собираем данные из текстовых полей
    document.querySelectorAll('.filter-content input[type="text"]').forEach(input => {
        if (input.value) {
            const name = input.name || input.placeholder?.split(' ')[0]?.toLowerCase();
            if (name) {
                filters[name] = input.value;
            }
        }
    });

    return filters;
}

// Показать результаты с примененными фильтрами
function showResults() {
    const selectedFilters = getSelectedFilters();
    // Закрываем сайдбар после применения фильтров
    toggleSidebar(false);

}

// Инициализация обработчиков событий
document.addEventListener('DOMContentLoaded', function () {
    // Кнопка открытия сайдбара (должна быть где-то на странице)
    const openSidebarBtn = document.querySelector('[data-toggle="filters-sidebar"]');
    if (openSidebarBtn) {
        openSidebarBtn.addEventListener('click', () => toggleSidebar(true));
    }

    // Кнопка закрытия сайдбара через оверлей
    const overlay = document.getElementById('filters-overlay');
    if (overlay) {
        overlay.addEventListener('click', () => toggleSidebar(false));
    }

// Кнопка "Сбросить все" - через data-атрибут
    const resetBtn = document.querySelector('[data-action="reset-filters"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function (e) {
            e.stopPropagation(); // Предотвращаем срабатывание toggleFilter
            resetAllFilters();
        });
    }

    // Кнопка "Показать результаты" - ИСПРАВЛЕННЫЙ СЕЛЕКТОР И ЛОГИКА
    const applyBtn = document.querySelector('.mt-6 button.bg-blue-600');
    if (applyBtn) {
        applyBtn.addEventListener('click', showResults);
    }

    // Добавляем обработчики для всех заголовков фильтров
    document.querySelectorAll('.filter-header').forEach(header => {
        const currentHeader = header;
        header.addEventListener('click', function (e) {
            // Не срабатывает при клике на кнопку "Сбросить все"
            if (e.target.closest('button.text-blue-600')) {
                return;
            }

            // Получаем ID фильтра из атрибута onclick или создаем из заголовка
            let filterId = currentHeader.getAttribute('onclick');
            if (filterId) {
                filterId = filterId.match(/toggleFilter\('([^']+)'\)/)?.[1];
            }

            if (!filterId) {
                // Если нет ID в onclick, создаем из текста заголовка
                const title = currentHeader.querySelector('h3')?.textContent?.trim();
                if (title) {
                    filterId = title.toLowerCase().replace(/\s+/g, '-') + '-filter';
                }
            }

            if (filterId) {
                toggleFilter(filterId);
            }
        });
    });

    // Обработка быстрого выбора диапазонов цен
    document.querySelectorAll('input[name="price-range"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                // Сбрасываем другие чекбоксы в этой группе
                document.querySelectorAll(`input[name="${this.name}"]`).forEach(otherCheckbox => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });

                // Устанавливаем значения в поля ввода
                const [min, max] = this.value.split('-');
                const minInput = document.querySelector('input[name="min-price"]');
                const maxInput = document.querySelector('input[name="max-price"]');

                if (minInput && min !== undefined) {
                    minInput.value = min;
                }

                if (maxInput && max !== undefined) {
                    if (max.endsWith('+')) {
                        maxInput.value = '';
                    } else {
                        maxInput.value = max;
                    }
                }
            }
        });
    });

    // Сброс чекбоксов цен при изменении числовых полей
    document.querySelectorAll('input[name="min-price"], input[name="max-price"]').forEach(input => {
        input.addEventListener('input', function () {
            document.querySelectorAll('input[name="price-range"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        });
    });

    // Обработка клавиши Escape для закрытия сайдбара
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('filters-sidebar');
            if (!sidebar.classList.contains('translate-x-full')) {
                toggleSidebar(false);
            }
        }
    });
});

const filterForm = document.querySelector('.filter-name');

filterForm.addEventListener('submit', function(e) {
    e.preventDefault();

    console.log('Форма найдена:', filterForm);
    console.log('Action формы:', filterForm.action);
    console.log('Method формы:', filterForm.method);

    // Собираем данные формы
    const formData = new FormData(filterForm);
    const params = new URLSearchParams();

    // Логируем все поля формы
    console.log('=== Поля формы: ===');
    formData.forEach((value, key) => {
        console.log(`${key}: ${value}`);
        params.append(key, value);
    });

    // Строим URL
    const url = new URL(filterForm.action, window.location.origin);
    url.search = params.toString();

    console.log('Полный URL:', url.toString());
    console.log('Отправляю AJAX запрос...');

    // Отправляем запрос с подробным логгированием
    fetch(url.toString(), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
        .then(response => {
            console.log('Статус ответа:', response.status);
            console.log('Заголовки ответа:', Object.fromEntries(response.headers.entries()));

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return response.json();
        })
        .then(data => {
            console.log('Успешный ответ:', data);

            // Обновляем контент если есть данные
            if (data.products_html) {
                const resultsContainer = document.getElementById('results-container');
                if (resultsContainer) {
                    resultsContainer.innerHTML = data.products_html;
                    console.log('Контент обновлен');
                }
            }

            // Обновляем пагинацию
            if (data.pagination_html) {
                const paginationContainer = document.querySelector('.pagination-container')
                    || document.querySelector('.pagination');
                if (paginationContainer) {
                    paginationContainer.innerHTML = data.pagination_html;
                }
            }

            // Обновляем URL в браузере
            window.history.pushState({}, '', url.toString());
            console.log('URL обновлен');

        })
        .catch(error => {
            console.error('Ошибка при выполнении запроса:', error);
            console.error('Полная ошибка:', error.stack);

            // Показываем сообщение об ошибке
            const resultsContainer = document.getElementById('results-container');
            if (resultsContainer) {
                resultsContainer.innerHTML = `
                <div class="alert alert-danger">
                    <h4>Ошибка загрузки данных</h4>
                    <p>${error.message}</p>
                    <button onclick="window.location.reload()">Перезагрузить страницу</button>
                </div>
            `;
            }
        });
});

// Функции для ручного управления из консоли (опционально)
// window.FiltersSidebar = {
//     open: () => toggleSidebar(true),
//     close: () => toggleSidebar(false),
//     toggle: () => toggleSidebar(),
//     reset: resetAllFilters,
//     apply: showResults,
//     getFilters: getSelectedFilters,
//     toggleFilter: toggleFilter
// };

