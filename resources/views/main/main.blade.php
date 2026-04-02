<main class="min-h-screen bg-gray-50 py-8">
  <h1 class="text-center mb-12">
    <div class="relative inline-block">
        <span class="text-5xl md:text-6xl font-bold text-gray-900 tracking-tighter">
            НОУТБУКИ
        </span>
      <div class="absolute -bottom-2 left-0 right-0 h-1 bg-gradient-to-r
                   from-transparent via-blue-500 to-transparent"></div>
    </div>
    <div class="mt-6">
        <span class="text-lg font-light text-gray-600 tracking-widest uppercase
                    border-l-4 border-blue-500 pl-4 py-1">
            учебный интернет-магазин
        </span>
    </div>
  </h1>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Фильтр производителей -->

    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-900 mb-3">
            Производители:
          </h2>

          <!-- Меню производителей -->
          <div class="flex flex-wrap gap-2" id="manufacturer-filters">
            @php
              $manufacturers = [
'Horizont', 'Lenovo', 'Chuwi', 'Asus', 'HP', 'Huawei',
'Honor', 'Acer', 'Apple', 'Dell', 'MSI',
'KUU', 'Ninkear', 'Infinix', 'HIPER', 'Maibenben',
'Gigabyte', 'Blackview', 'Vision', 'DIGMA'
];;
              $active = request('manufacturer', 'all');
            @endphp

              <!-- Производители -->
            @foreach($manufacturers as $index => $brand)
              @php
                $isActive = $active == $brand;
                $isHiddenOnMobile = $index >= 6;
              @endphp

              <div class="{{ $isHiddenOnMobile ? 'hidden md:block' : '' }}">
                <button type="button"
                        data-manufacturer="{{ $brand }}"
                  @class([
                      'manufacturer-filter-btn px-4 py-2 rounded-full font-medium border transition-colors shadow-sm',
                      'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' => $isActive,
                      'bg-white text-gray-700 border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' => !$isActive
                  ])>
                  {{ $brand }}
                </button>
              </div>
            @endforeach

            <!-- Кнопка "Все" -->
            <button type="button"
                    data-manufacturer="all"
              @class([
                  'manufacturer-filter-btn px-4 py-2 rounded-full font-medium border transition-colors shadow-sm',
                  'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' => $active == 'all',
                  'bg-white text-gray-700 border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' => $active != 'all'
              ])>
              Все
            </button>

            <!-- Кнопка "Еще" для мобильных -->
            <div class="md:hidden">
              <button type="button"
                      id="moreManufacturersBtn"
                      class="px-4 py-2 rounded-full bg-gray-100 text-gray-600 font-medium hover:bg-gray-200 transition-colors">
                Ещё 10+
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('main.sidebar')
    <!-- Контейнер для товаров -->
    <div id="products-container">
      @include('main.products-list', ['notebooks' => $notebooks])
    </div>

    <!-- Контейнер для пагинации -->
    <div id="pagination-container" class="mt-8">
      @if($totalPages > 1)
        {{ $paginator->links('vendor.pagination.tailwind') }}
      @endif
    </div>
  </div>
</main>

<!-- Модальное окно с производителями для мобильных -->
<div id="manufacturersModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
  <div class="fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl p-6 max-h-[80vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xl font-bold text-gray-900">Все производители</h3>
      <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <div class="grid grid-cols-2 gap-3">
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Horizont">
        Horizont
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Lenovo">
        Lenovo
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Chuwi">
        Chuwi
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Asus">
        Asus
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="HP">
        HP
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Huawei">
        Huawei
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Honor">
        Honor
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Acer">
        Acer
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="KUU">
        KUU
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="MSI">
        MSI
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Ninkear">
        Ninkear
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Apple">
        Apple
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Infinix">
        Infinix
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="HIPER">
        HIPER
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Maibenben">
        Maibenben
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Dell">
        Dell
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-white text-gray-700
                         font-medium border border-gray-200" data-manufacturer="Gigabyte">
        Gigabyte
      </button>
      <button class="filter-btn-mobile px-4 py-3 rounded-lg bg-blue-50 text-blue-600
                         font-medium border border-blue-100" data-manufacturer="all">
        Все
      </button>
    </div>
  </div>
</div>

