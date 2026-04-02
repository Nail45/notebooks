// Глобальные переменные состояния
let currentManufacturer = 'all';
let currentSort = 'default';
let isLoading = false;

// === ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ===
function showLoadingIndicator() {
  let indicator = document.getElementById('loading-indicator');
  if (!indicator) {
    indicator = document.createElement('div');
    indicator.id = 'loading-indicator';
    indicator.className = 'fixed top-0 left-0 w-full h-1 bg-blue-500 z-50';
    indicator.innerHTML = '<div class="h-full bg-blue-600 animate-pulse"></div>';
    document.body.appendChild(indicator);
  }
  indicator.style.display = 'block';
}

function hideLoadingIndicator() {
  const indicator = document.getElementById('loading-indicator');
  if (indicator) indicator.style.display = 'none';
}

function updateActiveManufacturerButton(manufacturer) {
  document.querySelectorAll('.manufacturer-filter-btn').forEach(btn => {
    const isActive = btn.getAttribute('data-manufacturer') === manufacturer;
    btn.classList.toggle('bg-blue-600', isActive);
    btn.classList.toggle('text-white', isActive);
    btn.classList.toggle('border-blue-600', isActive);
    btn.classList.toggle('hover:bg-blue-700', isActive);
    btn.classList.toggle('bg-white', !isActive);
    btn.classList.toggle('text-gray-700', !isActive);
    btn.classList.toggle('border-gray-200', !isActive);
    btn.classList.toggle('hover:bg-blue-50', !isActive);
    btn.classList.toggle('hover:text-blue-600', !isActive);
    btn.classList.toggle('hover:border-blue-200', !isActive);
  });
}

function updateActiveSort(sort) {
  const currentSortText = document.getElementById('current-sort-text');
  if (!currentSortText) return;

  const activeSortBtn = document.querySelector(`.sort-option-btn[data-sort="${sort}"]`);
  if (activeSortBtn) {
    currentSortText.textContent = activeSortBtn.textContent.trim();
    document.querySelectorAll('.sort-option-btn').forEach(btn => {
      btn.classList.toggle('bg-blue-100', btn === activeSortBtn);
      btn.classList.toggle('text-blue-700', btn === activeSortBtn);
    });
  }
}

function closeSortDropdown() {
  const sortDropdownMenu = document.getElementById('sort-dropdown-menu');
  const sortDropdownButton = document.getElementById('sort-dropdown-button');

  if (sortDropdownMenu) sortDropdownMenu.classList.add('hidden');
  if (sortDropdownButton) sortDropdownButton.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
}

// === ОСНОВНАЯ ФУНКЦИЯ ЗАГРУЗКИ ТОВАРОВ ===
function loadProducts(page = 1, customParams = null) {
  if (isLoading) return;
  isLoading = true;

  showLoadingIndicator();

  const url = new URL(window.location.origin + window.location.pathname);
  const currentParams = new URLSearchParams(window.location.search);

  // Копируем ВСЕ существующие параметры, кроме page
  for (const [key, value] of currentParams.entries()) {
    if (key !== 'page') {
      url.searchParams.set(key, value);
    }
  }

  // Если переданы кастомные параметры, добавляем/обновляем их
  if (customParams && typeof customParams === 'object') {
    Object.entries(customParams).forEach(([key, value]) => {
      if (value !== null && value !== '' && value !== undefined) {
        if (Array.isArray(value)) {
          // Удаляем старые значения для этого ключа
          url.searchParams.delete(key);
          // Добавляем новые значения
          value.forEach(val => {
            if (val !== null && val !== '' && val !== undefined) {
              url.searchParams.append(key, val);
            }
          });
        } else {
          url.searchParams.set(key, value);
        }
      } else {
        // Если значение пустое, удаляем параметр
        url.searchParams.delete(key);
      }
    });
  }

  // Обновляем глобальные переменные из URL
  if (url.searchParams.has('manufacturer')) {
    currentManufacturer = url.searchParams.get('manufacturer');
  }
  if (url.searchParams.has('sort')) {
    currentSort = url.searchParams.get('sort');
  }

  // Добавляем номер страницы
  if (page > 1) {
    url.searchParams.set('page', page);
  } else {
    url.searchParams.delete('page');
  }

  console.log('Загрузка товаров по URL:', url.toString());

  fetch(url.toString(), {
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  })
    .then(response => {
      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
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
      const paginationContainer = document.getElementById('pagination-container');

      if (container && data.products_html) {
        container.innerHTML = data.products_html;

        if (typeof window.initCartButtons === 'function') {
          window.initCartButtons();
        }
        // Инициализируем обработчики для загруженного контента
        initDynamicContentHandlers();
      }

      if (paginationContainer && data.pagination_html) {
        paginationContainer.innerHTML = data.pagination_html;

        if (typeof initCartButtons === 'function') {
          initCartButtons();
        }
        // ВАЖНО: Повторно инициализируем обработчики для новой пагинации
        setTimeout(() => initPaginationHandlers(), 0);
      } else if (paginationContainer) {

        if (typeof initCartButtons === 'function') {
          initCartButtons();
        }
        // Если нет пагинации, очищаем контейнер
        paginationContainer.innerHTML = '';
      }

      // Обновляем состояние из данных сервера
      if (data.active_manufacturer) {
        currentManufacturer = data.active_manufacturer;
        updateActiveManufacturerButton(currentManufacturer);
      }

      if (data.active_sort) {
        currentSort = data.active_sort;
        updateActiveSort(currentSort);
      }

      // Обновляем историю браузера
      window.history.pushState({
        manufacturer: data.active_manufacturer,
        sort: data.active_sort,
        page: data.current_page,
        filters: data.active_filters || {}
      }, '', url.toString());

      // Показываем активные фильтры
      const filterContainer = document.getElementById('active-filters-container');
      if (data.filter_count > 0) {
        if (typeof showActiveFilters === 'function') {
          showActiveFilters(data.active_filters, data.filter_count);
        }
      } else if (filterContainer) {
        filterContainer.style.display = 'none';
      }

      // Инициализируем обработчики для загруженного контента
      initDynamicContentHandlers();
    })
    .catch(error => {
      console.error('Ошибка загрузки товаров:', error);
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
    })
    .finally(() => {
      isLoading = false;
      hideLoadingIndicator();
    });
}

// === ФУНКЦИИ ПАГИНАЦИИ ===
function initPaginationHandlers() {
  const paginationContainer = document.getElementById('pagination-container');
  if (!paginationContainer) return;

  // Обработчик для пагинации (делегирование событий)
  paginationContainer.addEventListener('click', handlePaginationClick);
}

function handlePaginationClick(e) {
  e.preventDefault();

  const link = e.target.closest('a');
  if (!link || isLoading) return;

  const href = link.getAttribute('href');
  if (!href || href === '#' || href === 'javascript:void(0)') return;

  // Извлекаем номер страницы из URL
  const url = new URL(href, window.location.origin);
  const page = url.searchParams.get('page') || 1;

  console.log('Переход на страницу:', page, 'URL:', href);
  loadProducts(parseInt(page));
}

// === ФУНКЦИИ САЙДБАРА ФИЛЬТРОВ ===
function openFiltersSidebar() {
  const sidebar = document.getElementById('filters-sidebar');
  const overlay = document.getElementById('filters-overlay');

  if (sidebar && overlay) {
    sidebar.classList.remove('translate-x-full');
    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  } else {
    console.error('Не найден сайдбар или оверлей');
  }
}

function closeFiltersSidebar() {
  const sidebar = document.getElementById('filters-sidebar');
  const overlay = document.getElementById('filters-overlay');

  if (sidebar && overlay) {
    sidebar.classList.add('translate-x-full');
    overlay.classList.add('hidden');
    document.body.style.overflow = '';
  }
}

function toggleFilter(filterId) {
  const filterContent = document.getElementById(filterId);
  const filterIcon = document.getElementById(`${filterId}-icon`);

  if (filterContent) {
    filterContent.classList.toggle('hidden');
    if (filterIcon) {
      filterIcon.style.transform = filterContent.classList.contains('hidden')
        ? 'rotate(0deg)'
        : 'rotate(180deg)';
    }
  }
}

function resetAllFilters() {
  // Сбрасываем все поля ввода
  const resetSelectors = [
    '.filter-content input[type="checkbox"]',
    '.filter-content input[type="number"]',
    '.filter-content input[type="text"]'
  ];

  resetSelectors.forEach(selector => {
    document.querySelectorAll(selector).forEach(input => {
      if (input.type === 'checkbox') input.checked = false;
      else input.value = '';
    });
  });

  // Скрываем все открытые фильтры
  document.querySelectorAll('.filter-content').forEach(content => {
    content.classList.add('hidden');
  });

  document.querySelectorAll('[id$="-icon"]').forEach(icon => {
    icon.style.transform = 'rotate(0deg)';
  });

  console.log('Все фильтры сброшены');
}

function getSelectedFilters() {
  const filters = {};

  // Чекбоксы
  document.querySelectorAll('.filter-content input[type="checkbox"]:checked').forEach(checkbox => {
    const name = checkbox.name;
    const value = checkbox.value || checkbox.nextElementSibling?.textContent?.trim();
    if (name) {
      if (!filters[name]) filters[name] = [];
      filters[name].push(value);
    }
  });

  // Числовые поля
  document.querySelectorAll('.filter-content input[type="number"]').forEach(input => {
    if (input.value) {
      const name = input.name || input.previousElementSibling?.textContent?.trim().toLowerCase();
      if (name) filters[name] = input.value;
    }
  });

  // Текстовые поля
  document.querySelectorAll('.filter-content input[type="text"]').forEach(input => {
    if (input.value) {
      const name = input.name || input.placeholder?.split(' ')[0]?.toLowerCase();
      if (name) filters[name] = input.value;
    }
  });

  return filters;
}

function showResults() {
  console.log('Применение фильтров...');

  // Собираем фильтры из сайдбара
  const selectedFilters = getSelectedFilters();

  // Сохраняем текущие manufacturer и sort
  const currentParams = new URLSearchParams(window.location.search);
  if (currentParams.has('manufacturer')) {
    selectedFilters['manufacturer'] = currentParams.get('manufacturer');
  }
  if (currentParams.has('sort')) {
    selectedFilters['sort'] = currentParams.get('sort');
  }

  console.log('Применяемые фильтры:', selectedFilters);

  // Закрываем сайдбар
  closeFiltersSidebar();

  // Загружаем товары с примененными фильтрами
  loadProducts(1, selectedFilters);
}

// === ИНИЦИАЛИЗАЦИЯ ОБРАБОТЧИКОВ ===
function initDynamicContentHandlers() {
  // Обработчики фильтров производителей
  document.querySelectorAll('.manufacturer-filter-btn').forEach(button => {
    button.addEventListener('click', function () {
      if (isLoading) return;

      // Обновляем текущего производителя
      currentManufacturer = this.getAttribute('data-manufacturer');

      // Создаем параметры для загрузки
      const params = {
        manufacturer: currentManufacturer,
        sort: currentSort
      };

      // Загружаем с обновленным производителем
      loadProducts(1, params);
    });
  });
}

// === ИНИЦИАЛИЗАЦИЯ СТРАНИЦЫ ===
function initializePage() {
  const urlParams = new URLSearchParams(window.location.search);
  currentManufacturer = urlParams.get('manufacturer') || 'all';
  currentSort = urlParams.get('sort') || 'default';

  // Инициализация сортировки
  const sortDropdownButton = document.getElementById('sort-dropdown-button');
  const sortDropdownMenu = document.getElementById('sort-dropdown-menu');

  initPaginationHandlers();

  if (sortDropdownButton && sortDropdownMenu) {
    sortDropdownButton.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const isHidden = sortDropdownMenu.classList.toggle('hidden');
      this.classList.toggle('bg-blue-50', !isHidden);
      this.classList.toggle('text-blue-600', !isHidden);
      this.classList.toggle('border-blue-200', !isHidden);
    });

    document.addEventListener('click', function (e) {
      if (!sortDropdownMenu.contains(e.target) && !sortDropdownButton.contains(e.target)) {
        closeSortDropdown();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeSortDropdown();
    });

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

        // Создаем параметры для загрузки
        const params = {
          manufacturer: currentManufacturer,
          sort: currentSort
        };

        closeSortDropdown();
        loadProducts(1, params);
      });
    });
  }

  // Инициализация обработчиков фильтров производителей
  initDynamicContentHandlers();

  // Инициализация пагинации
  initPaginationHandlers();

  // Кнопка "Еще" для мобильных
  const moreBtn = document.getElementById('moreManufacturersBtn');
  if (moreBtn) {
    moreBtn.addEventListener('click', function () {
      document.querySelectorAll('.hidden.md\\:contents').forEach(element => {
        element.classList.remove('hidden');
      });
      this.style.display = 'none';
    });
  }

  // Загрузка начальных данных
  const currentPage = urlParams.get('page') || 1;
  if (window.location.search) {
    loadProducts(parseInt(currentPage));
  }
}

// === ГЛОБАЛЬНЫЕ ФУНКЦИИ ===
window.resetFilters = function () {
  // 1. Сбрасываем UI фильтров в сайдбаре
  resetAllFilters();

  // 2. Сбрасываем глобальные переменные
  currentManufacturer = 'all';
  currentSort = 'default';

  // 3. Закрываем сайдбар
  closeFiltersSidebar();

  // 4. Создаем БАЗОВЫЙ URL без параметров
  const baseUrl = window.location.origin + window.location.pathname;

  // 5. Обновляем историю браузера на чистый URL
  window.history.pushState({
    manufacturer: 'all',
    sort: 'default',
    page: 1,
    filters: {}
  }, '', baseUrl);

  // 6. Обновляем UI
  updateActiveManufacturerButton('all');
  updateActiveSort('default');

  // 7. Загружаем товары с чистого URL
  fetch(baseUrl, {
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  })
    .then(response => {
      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
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
      const paginationContainer = document.getElementById('pagination-container');

      if (container && data.products_html) {
        container.innerHTML = data.products_html;


        if (typeof initCartButtons === 'function') {
          initCartButtons();
        }
      }

      if (paginationContainer && data.pagination_html) {
        paginationContainer.innerHTML = data.pagination_html;


        if (typeof initCartButtons === 'function') {
          initCartButtons();
        }
        setTimeout(() => initPaginationHandlers(), 0);
      } else if (paginationContainer) {
        paginationContainer.innerHTML = '';


        if (typeof initCartButtons === 'function') {
          initCartButtons();
        }
      }

      // Скрываем активные фильтры
      const filterContainer = document.getElementById('active-filters-container');
      if (filterContainer) {
        filterContainer.style.display = 'none';
      }

      // Инициализируем обработчики для загруженного контента
      initDynamicContentHandlers();
    })
    .catch(error => {
      console.error('Ошибка загрузки товаров:', error);
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


        if (typeof initCartButtons === 'function') {
          initCartButtons();
        }
      }
    })
    .finally(() => {
      isLoading = false;
      hideLoadingIndicator();
    });
};

// === ОБРАБОТЧИКИ СОБЫТИЙ ===
document.addEventListener('DOMContentLoaded', function () {
  initializePage();

  // Делегирование событий для динамических элементов
  document.addEventListener('click', function (e) {
    // Кнопка открытия фильтров
    const filterButton = e.target.closest('[data-testid="open-products-filters"]');
    if (filterButton) {
      e.preventDefault();
      openFiltersSidebar();
      return;
    }
  });

  // Обработка кнопки "назад/вперед"
  window.addEventListener('popstate', function (event) {
    console.log('Popstate event:', event.state);

    const urlParams = new URLSearchParams(window.location.search);
    currentManufacturer = urlParams.get('manufacturer') || 'all';
    currentSort = urlParams.get('sort') || 'default';
    const newPage = urlParams.get('page') || 1;

    updateActiveManufacturerButton(currentManufacturer);
    updateActiveSort(currentSort);
    loadProducts(parseInt(newPage));
  });
  // Инициализация фильтров в сайдбаре
  const overlay = document.getElementById('filters-overlay');
  if (overlay) overlay.addEventListener('click', () => closeFiltersSidebar());

  const resetBtn = document.querySelector('[data-action="reset-filters"]');
  if (resetBtn) {
    resetBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      resetAllFilters();
    });
  }

  const applyBtn = document.querySelector('.mt-6 button.bg-blue-600');
  if (applyBtn) applyBtn.addEventListener('click', showResults);

  // Заголовки фильтров
  document.querySelectorAll('.filter-header').forEach(header => {
    header.addEventListener('click', function (e) {
      if (e.target.closest('button.text-blue-600')) return;

      let filterId = header.getAttribute('onclick');
      if (filterId) {
        filterId = filterId.match(/toggleFilter\('([^']+)'\)/)?.[1];
      }

      if (!filterId) {
        const title = header.querySelector('h3')?.textContent?.trim();
        if (title) filterId = `${title.toLowerCase().replace(/\s+/g, '-')}-filter`;
      }

      if (filterId) toggleFilter(filterId);
    });
  });

  // Обработка диапазонов цен
  document.querySelectorAll('input[name="price-range"]').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
      if (this.checked) {
        document.querySelectorAll(`input[name="${this.name}"]`).forEach(other => {
          if (other !== this) other.checked = false;
        });

        const [min, max] = this.value.split('-');
        const minInput = document.querySelector('input[name="min-price"]');
        const maxInput = document.querySelector('input[name="max-price"]');

        if (minInput && min !== undefined) minInput.value = min;
        if (maxInput && max !== undefined) maxInput.value = max.endsWith('+') ? '' : max;
      }
    });
  });

  document.querySelectorAll('input[name="min-price"], input[name="max-price"]').forEach(input => {
    input.addEventListener('input', function () {
      document.querySelectorAll('input[name="price-range"]').forEach(checkbox => {
        checkbox.checked = false;
      });
    });
  });

  // Обработка Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const sidebar = document.getElementById('filters-sidebar');
      if (sidebar && !sidebar.classList.contains('translate-x-full')) {
        closeFiltersSidebar();
      }
    }
  });
});
