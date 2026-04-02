// public/js/search.js

class NotebookSearch {
  constructor() {
    // DOM элементы
    this.searchInput = document.getElementById('search-input');
    this.searchButton = document.getElementById('search-button');
    this.searchResults = document.getElementById('search-results');
    this.searchLoader = document.getElementById('search-loader');
    this.clearButton = document.getElementById('clear-search');

    // Состояние
    this.searchTimeout = null;
    this.minChars = 2;
    this.isLoading = false;
    this.currentQuery = '';

    // Инициализация
    this.init();
  }

  init() {
    if (!this.searchInput) {
      return;
    }

    if (!this.searchResults) {
      return;
    }

    // Вешаем обработчики событий
    this.attachEventListeners();

    // Проверяем, есть ли запрос в URL
    this.checkUrlQuery();
  }

  attachEventListeners() {
    // Ввод текста - поиск с задержкой (debounce)
    this.searchInput.addEventListener('input', (e) => {
      const query = e.target.value.trim();
      this.toggleClearButton(query.length > 0);

      if (query.length === 0) {
        this.hideResults();
        return;
      }

      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.performSearch(query);
      }, 300);
    });

    // Обработка клавиш
    this.searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        const query = this.searchInput.value.trim();
        if (query.length >= this.minChars) {
          this.redirectToSearchPage(query);
        }
      }

      if (e.key === 'Escape') {
        this.hideResults();
      }

      if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        this.navigateResults(e.key === 'ArrowDown' ? 'down' : 'up');
      }
    });

    // Клик по кнопке поиска
    if (this.searchButton) {
      this.searchButton.addEventListener('click', (e) => {
        e.preventDefault();
        const query = this.searchInput.value.trim();
        if (query.length >= this.minChars) {
          this.redirectToSearchPage(query);
        }
      });
    }

    // Клик по кнопке очистки
    if (this.clearButton) {
      this.clearButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.clearSearch();
      });
    }

    // Закрытие при клике вне
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.search-container')) {
        this.hideResults();
      }
    });

    // Фокус на инпуте
    this.searchInput.addEventListener('focus', () => {
      const query = this.searchInput.value.trim();
      if (query.length >= this.minChars && this.searchResults.children.length > 0) {
        this.showResults();
      }
    });
  }

  async performSearch(query) {
    if (query.length < this.minChars) {
      this.hideResults();
      return;
    }

    if (query === this.currentQuery && this.searchResults.children.length > 0) {
      this.showResults();
      return;
    }

    this.currentQuery = query;
    this.showLoader();
    this.isLoading = true;

    try {
      const response = await fetch(`/search/notebooks?q=${encodeURIComponent(query)}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();

      if (data.success) {
        this.displayResults(data.html, query);
      } else {
        this.showError(data.message || 'Ошибка поиска');
      }
    } catch (error) {
      this.showError('Ошибка при выполнении поиска');
    } finally {
      this.hideLoader();
      this.isLoading = false;
    }
  }

  displayResults(html, query) {
    if (!this.searchResults) {
      return;
    }

    // ✅ ОЧИЩАЕМ КОНТЕЙНЕР ПЕРЕД ВСТАВКОЙ!
    this.searchResults.innerHTML = '';

    if (html && html.trim().length > 0) {
      // ✅ ВСТАВЛЯЕМ HTML
      this.searchResults.innerHTML = html;

      // ✅ ПОКАЗЫВАЕМ РЕЗУЛЬТАТЫ
      this.showResults();

      // ✅ ПОДСВЕЧИВАЕМ ПОИСКОВЫЙ ЗАПРОС
      setTimeout(() => {
        this.highlightSearchTerm(query);
      }, 50);
    } else {
      this.searchResults.innerHTML = `
        <div class="p-8 text-center">
          <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <p class="text-gray-600 font-medium">Ничего не найдено</p>
          <p class="text-sm text-gray-500 mt-1">По запросу «${query}»</p>
        </div>
      `;
      this.showResults();
    }
  }

  showResults() {
    if (this.searchResults) {
      this.searchResults.classList.remove('hidden');

      // Проверяем видимость
      const style = window.getComputedStyle(this.searchResults);
    }
  }

  hideResults() {
    if (this.searchResults) {
      this.searchResults.classList.add('hidden');
      this.searchResults.innerHTML = ''; // ОЧИЩАЕМ ПРИ СКРЫТИИ
    }
    this.currentQuery = '';
    this.removeHighlight();
  }

  showLoader() {
    if (this.searchLoader) {
      this.searchLoader.classList.remove('hidden');
    }
    if (this.clearButton) {
      this.clearButton.classList.add('hidden');
    }
  }

  hideLoader() {
    if (this.searchLoader) {
      this.searchLoader.classList.add('hidden');
    }
    this.toggleClearButton(this.searchInput.value.trim().length > 0);
  }

  showError(message) {
    if (this.searchResults) {
      this.searchResults.innerHTML = `
        <div class="p-8 text-center">
          <svg class="w-12 h-12 mx-auto text-red-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-red-600 font-medium">${message}</p>
          <p class="text-xs text-gray-400 mt-2">Попробуйте позже</p>
        </div>
      `;
      this.showResults();
    }
  }

  toggleClearButton(show) {
    if (this.clearButton && this.searchLoader) {
      if (show && !this.searchLoader.classList.contains('hidden')) {
        return;
      }
      this.clearButton.classList.toggle('hidden', !show);
    }
  }

  clearSearch() {
    this.searchInput.value = '';
    this.searchInput.focus();
    this.toggleClearButton(false);
    this.hideResults();
    this.currentQuery = '';

    const url = new URL(window.location);
    url.searchParams.delete('q');
    window.history.replaceState({}, '', url);
  }

  redirectToSearchPage(query) {
    window.location.href = `/search/${encodeURIComponent(query)}`;
  }

  navigateResults(direction) {
    const items = document.querySelectorAll('.search-result-item');
    if (items.length === 0) return;

    let currentIndex = -1;
    items.forEach((item, index) => {
      if (item.classList.contains('bg-pink-50')) {
        currentIndex = index;
      }
    });

    items.forEach(item => {
      item.classList.remove('bg-pink-50');
    });

    if (direction === 'down') {
      currentIndex = (currentIndex + 1) % items.length;
    } else {
      currentIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
    }

    items[currentIndex].classList.add('bg-pink-50');
    items[currentIndex].scrollIntoView({block: 'nearest', behavior: 'smooth'});
  }

  checkUrlQuery() {
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('q');

    if (query && this.searchInput) {
      this.searchInput.value = query;
      this.toggleClearButton(true);
    }
  }

  highlightSearchTerm(term) {
    if (!term || term.length < 2) return;

    const regex = new RegExp(`(${term})`, 'gi');

    document.querySelectorAll('.search-result-item h4').forEach(el => {
      // Сохраняем оригинальный текст
      if (!el.dataset.originalText) {
        el.dataset.originalText = el.textContent;
      }

      const text = el.dataset.originalText;
      el.innerHTML = text.replace(regex, '<span class="bg-pink-200 text-pink-800 px-0.5 rounded">$1</span>');
    });
  }

  removeHighlight() {
    document.querySelectorAll('.search-result-item h4[data-original-text]').forEach(el => {
      el.innerHTML = el.dataset.originalText;
    });
  }
}

// Единая инициализация
document.addEventListener('DOMContentLoaded', () => {
  window.notebookSearch = new NotebookSearch();
});

// Экспорт для глобального доступа
if (typeof module !== 'undefined' && module.exports) {
  module.exports = NotebookSearch;
}
