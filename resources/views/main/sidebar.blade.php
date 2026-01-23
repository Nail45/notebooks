@php use Illuminate\Support\Str; @endphp
<div class="flex justify-between gap-5">
    <!-- Кнопка Фильтр -->
    <button
        data-testid="open-products-filters"
        class="
        group
        inline-flex items-center justify-start gap-3
        px-5 py-3
        mb-6
        bg-white border-2 border-gray-200
        rounded-xl
        text-gray-700
        hover:bg-blue-50 hover:border-blue-300 hover:shadow-md
        active:bg-blue-100 active:scale-[0.98]
        transition-all duration-250 ease-in-out
        focus:outline-none focus:ring-3 focus:ring-blue-500 focus:ring-opacity-30
        min-w-[180px]
    "
        type="button"
        aria-label="Открыть фильтры товаров"
    >
        <!-- Иконка -->
        <div class="w-6 h-6 group-hover:scale-110 transition-transform duration-250">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                data-testid="icon"
                class="w-full h-full text-blue-600 group-hover:text-blue-700 transition-colors duration-250"
            >
                <path
                    fill-rule="evenodd" clip-rule="evenodd"
                    d="M16.0428 8.07247L16.1608 8.5L16.0428 8.92753C15.8559 9.60467 15.2341 10.1 14.5002 10.1C13.7663 10.1 13.1445 9.60467 12.9576 8.92753L12.8396 8.5L12.9576 8.07247C13.1445 7.39533 13.7663 6.9 14.5002 6.9C15.2341 6.9 15.8559 7.39533 16.0428 8.07247ZM20.3002 8.5C20.3002 8.05817 19.942 7.7 19.5002 7.7H17.3923C17.0422 6.43153 15.88 5.5 14.5002 5.5C13.1204 5.5 11.9581 6.43153 11.608 7.7H4.5002C4.05837 7.7 3.7002 8.05817 3.7002 8.5C3.7002 8.94183 4.05837 9.3 4.5002 9.3H11.608C11.9581 10.5685 13.1204 11.5 14.5002 11.5C15.88 11.5 17.0422 10.5685 17.3923 9.3H19.5002C19.942 9.3 20.3002 8.94183 20.3002 8.5Z"
                    fill="currentColor"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M4.5002 14.7C4.05837 14.7 3.7002 15.0582 3.7002 15.5C3.7002 15.9418 4.05837 16.3 4.5002 16.3H6.60804C6.95814 17.5685 8.12038 18.5 9.5002 18.5C10.88 18.5 12.0423 17.5685 12.3923 16.3H19.5002C19.942 16.3 20.3002 15.9418 20.3002 15.5C20.3002 15.0582 19.942 14.7 19.5002 14.7H12.3923C12.0423 13.4315 10.88 12.5 9.5002 12.5C8.12038 12.5 6.95814 13.4315 6.60804 14.7H4.5002ZM7.95758 15.9275L7.83959 15.5L7.95758 15.0725C8.14447 14.3953 8.76629 13.9 9.5002 13.9C10.2341 13.9 10.8559 14.3953 11.0428 15.0725L11.1608 15.5L11.0428 15.9275C10.8559 16.6047 10.2341 17.1 9.5002 17.1C8.76629 17.1 8.14447 16.6047 7.95758 15.9275Z"
                      fill="currentColor"></path>
            </svg>
        </div>

        <!-- Текстовый блок -->
        <div class="flex flex-col items-start">
            <div class="text-base font-semibold leading-tight group-hover:text-blue-800 transition-colors duration-250">
                Фильтр
            </div>
            <div class="text-xs text-gray-500 leading-tight group-hover:text-blue-600 transition-colors duration-250">
                Параметры товаров
            </div>
        </div>
    </button>
    <!-- Блок сортировки -->
    <div class="flex items-center justify-between mb-6 gap-5">
        <div class="flex items-center gap-4">
            <div class="relative" id="sort-dropdown-container">
                <button type="button"
                        id="sort-dropdown-button"
                        class="flex items-center justify-between px-4 py-2 rounded-full font-medium bg-white text-gray-700 border border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-colors shadow-sm min-w-[200px]">
                    <span id="current-sort-text">{{ $sortOptions[$sort]['text'] ?? 'По популярности' }}</span>
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>

                <!-- Выпадающее меню сортировки -->
                <div id="sort-dropdown-menu"
                     class="absolute right-0 z-50 hidden w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <div class="py-1">
                        @foreach($sortOptions as $key => $option)
                            <button type="button"
                                    data-sort="{{ $key }}"
                                    class="sort-option-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ $sort == $key ? 'bg-blue-100 text-blue-700' : '' }} transition-colors">
                                {{ $option['text'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<aside id="filters-sidebar"
       class="fixed right-0 w-full md:w-72 lg:w-72 xl:w-96 bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6 h-fit transform transition-transform duration-300 ease-in-out translate-x-full">
    <!-- Заголовок фильтров -->
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 gap-4">
        <h2 class="text-lg font-bold text-gray-900">Фильтры</h2>
        <button data-action="reset-filters"
                class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
            Сбросить все
        </button>
    </div>

    <!-- Контейнер для фильтров с прокруткой -->
    <form name="filter" method="get" class="space-y-4 filter-name" id="filter-form" action="{{route('products.index')}}">
        <!-- Фильтр: Цена -->
        <div class="filter-group">
            <div class="filter-header" data-filter-target="price-filter" onclick="toggleFilter('price-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Цена</h3>
                    <svg id="price-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="price-filter" class="filter-content hidden mt-3">
                <div class="space-y-4">
                    <!-- Диапазон цен -->
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm text-gray-600 mb-1">От</label>
                            <input type="number"
                                   placeholder="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   min="0"
                                   name="min_price"
                                   value="{{ request('min_price') }}">

                        </div>
                        <div class="flex-1">
                            <label class="block text-sm text-gray-600 mb-1">До</label>
                            <input type="number"
                                   placeholder="100000"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   min="0"
                                   name="max_price"
                                   value="{{ request('max_price') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Фильтр: Вид -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('view-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Вид</h3>
                        <!-- Иконка подсказки -->
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Тип корпуса ноутбука">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="view-filter-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="view-filter" class="filter-content hidden mt-3 space-y-2 pl-1">
                <label class="filter-checkbox">
                    <input @if(request('worker')) checked @endif  type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500" name="worker"
                           value="рабочий (офисный)">
                    <span class="ml-2 text-gray-700">Рабочий (офисный)</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('gaming')) checked @endif type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500" name="gaming"
                           value="игровой (геймерский)">
                    <span class="ml-2 text-gray-700">Игровой (геймерский)</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('ultrabook')) checked @endif type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500" name="ultrabook"
                           value="ультрабук">
                    <span class="ml-2 text-gray-700">Ультрабук</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('universal')) checked @endif type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500" name="universal"
                           value="универсальный">
                    <span class="ml-2 text-gray-700">Универсальный</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('domestic')) checked @endif type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500" name="domestic"
                           value="домашний (мультимедийный)">
                    <span class="ml-2 text-gray-700">Домашний (мультимедийный)</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Дата выхода на рынок -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('release-date-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Дата выхода на рынок</h3>
                    <svg id="release-date-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="release-date-filter" class="filter-content hidden mt-3 space-y-2">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">От</label>
                        <input name="release_year_from" type="number" placeholder="2015"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">До</label>
                        <input name="release_year_to" type="number" max="{{date('Y')}}" placeholder="2024"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Фильтр: Диагональ экрана -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('screen-size-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Диагональ экрана</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Размер экрана в дюймах">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="screen-size-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="screen-size-filter" class="filter-content hidden mt-3 space-y-2">

                <div class="space-y-4">
                    <!-- Диапазон цен -->
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm text-gray-600 mb-1">От, "</label>
                            <input type="number"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   min="0"
                                   name="screen_diagonal_from"
                                   value="{{ request('screen_diagonal_from') }}">

                        </div>
                        <div class="flex-1">
                            <label class="block text-sm text-gray-600 mb-1">До, "</label>
                            <input type="number"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   min="0"
                                   name="screen_diagonal_to"
                                   value="{{ request('screen_diagonal_to') }}">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Фильтр: Разрешение экрана -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('resolution-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Разрешение экрана</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Количество пикселей по горизонтали и вертикали">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="resolution-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="resolution-filter" class="filter-content hidden mt-3 space-y-2">
                <label class="filter-checkbox">
                    <input @if(request('resolution_3456×2234')) checked @endif name="resolution_3456×2234"
                           value="3456×2234" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">3456×2234</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('resolution_3072×1920')) checked @endif name="resolution_3072×1920"
                           value="3072×1920" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">4K (3072×1920)</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('resolution_3024×1964')) checked @endif name="resolution_3024×1964"
                           value="3024×1964" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">4K (3024×1964)</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('resolution_2880×1920')) checked @endif name="resolution_2880×1920"
                           value="2880×1920" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">2880×1920</span>
                </label>

                <label class="filter-checkbox">
                    <input @if(request('resolution_2880×1864')) checked @endif name="resolution_2880×1864"
                           value="2880×1864" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">2880×1864</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('resolution_2880×1800')) checked @endif name="resolution_2880×1800"
                           value="2880×1800" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">2880×1800</span>
                </label>

                <label class="filter-checkbox">
                    <input @if(request('resolution_2880×1620')) checked @endif name="resolution_2880×1620"
                           value="2880×1620" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">2880×1620</span>
                </label>

                <label class="filter-checkbox">
                    <input @if(request('resolution_2560×1664')) checked @endif name="resolution_2560×1664"
                           value="2560×1664" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">2560×1664</span>
                </label>

                <label class="filter-checkbox">
                    <input @if(request('resolution_2560×1600')) checked @endif name="resolution_2560×1600"
                           value="2560×1600" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">2560×1600</span>
                </label>
                <label class="filter-checkbox">
                    <input @if(request('resolution_2160×1440')) checked @endif name="resolution_2160×1440"
                           value="2160×1440" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">2K (2160×1440)</span>
                </label>

                <label class="filter-checkbox">
                    <input @if(request('resolution_1920×1200')) checked @endif name="resolution_1920×1200"
                           value="1920×1200" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">Full HD+ (1920×1200)</span>
                </label>

                <label class="filter-checkbox">
                    <input @if(request('resolution_1920×1080')) checked @endif name="resolution_1920×1080"
                           value="1920×1080" type="checkbox"
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">Full HD (1920×1080)</span>
                </label>

            </div>
        </div>

        <!-- Фильтр: Популярные параметры -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('popular-params-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Популярные параметры</h3>
                    <svg id="popular-params-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="popular-params-filter" class="filter-content hidden mt-3 space-y-2">
                <label class="filter-checkbox">
                    <input name="keyboard_backlight" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('keyboard_backlight') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">С подсветкой клавиатуры</span>
                </label>
                <label class="filter-checkbox">
                    <input name="numeric_keypad" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('numeric_keypad') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Цифровое поле (Numpad)</span>
                </label>
                <label class="filter-checkbox">
                    <input name="touch_screen" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('touch_screen') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">С сенсорным экраном</span>
                </label>
                <label class="filter-checkbox">
                    <input name="hdmi_port" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('hdmi_port') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">HDMI</span>
                </label>
                <label class="filter-checkbox">
                    <input name="displayport" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('displayport') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">DisplayPort</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Объем оперативной памяти -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('ram-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Оперативная память</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Объем оперативной памяти в ГБ">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="ram-filter-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="ram-filter" class="filter-content hidden mt-3 space-y-2">
                <label class="filter-checkbox">
                    <input name="ram_4" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_4') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">4 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_8" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_8') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">8 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_12" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_12') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">12 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_16" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_16') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">16 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_18" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_18') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">18 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_24" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_24') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">24 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_32" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_32') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">32 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_36" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_36') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">36 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_48" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_48') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">48 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_64" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_64') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">64 ГБ</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Тип оперативной памяти -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('ram-type-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Тип оперативной памяти</h3>
                    <svg id="ram-type-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="ram-type-filter" class="filter-content hidden mt-3 space-y-2">
                <label class="filter-checkbox">
                    <input name="ram_type_ddr4" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_type_ddr4') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">DDR4</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_type_ddr5" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_type_ddr5') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">DDR5</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_type_lpddr4" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_type_lpddr4') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">LPDDR4</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_type_lpddr4x" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_type_lpddr4x') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">LPDDR4X</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_type_lpddr5" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_type_lpddr5') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">LPDDR5</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ram_type_lpddr5x" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ram_type_lpddr5x') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">LPDDR5X</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Серия процессора -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('processor-series-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Серия процессора</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Производитель и серия процессора">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="processor-series-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="processor-series-filter" class="filter-content hidden mt-3 space-y-2">
                <!-- Intel -->
                <div class="font-medium text-gray-700 mb-1">Intel</div>
                <label class="filter-checkbox">
                    <input name="processor_intel_core_i3" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_core_i3') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Core i3</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_core_i5" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_core_i5') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Core i5</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_core_i7" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_core_i7') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Core i7</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_celeron" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_celeron') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Celeron</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_core_5" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_core_5') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Core 5</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_processor" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_processor') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Processor</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_core_ultra_7" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_core_ultra_7') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Core Ultra 7</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_core_ultra_5" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_core_ultra_5') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Core Ultra 5</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_core_ultra_9" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_core_ultra_9') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Core Ultra 9</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_intel_pentium" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_intel_pentium') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Intel Pentium</span>
                </label>

                <!-- AMD -->
                <div class="font-medium text-gray-700 mb-1 mt-3">AMD</div>
                <label class="filter-checkbox">
                    <input name="processor_amd_ryzen_5" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_amd_ryzen_5') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">AMD Ryzen 5</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_amd_ryzen_7" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_amd_ryzen_7') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">AMD Ryzen 7</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_amd_ryzen_3" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_amd_ryzen_3') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">AMD Ryzen 3</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_amd_ryzen_9" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_amd_ryzen_9') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">AMD Ryzen 9</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_amd_athlon" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_amd_athlon') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">AMD Athlon</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_amd_ryzen_ai" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_amd_ryzen_ai') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">AMD Ryzen AI</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_ryzen_ai_5" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_ryzen_ai_5') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Ryzen AI 5</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_amd_ryzen_ai_7" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_amd_ryzen_ai_7') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">AMD Ryzen AI 7</span>
                </label>

                <!-- Apple -->
                <div class="font-medium text-gray-700 mb-1 mt-3">Apple</div>
                <label class="filter-checkbox">
                    <input name="processor_apple_m2" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_apple_m2') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Apple M2</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_apple_m4" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_apple_m4') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Apple M4</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_apple_m1" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_apple_m1') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Apple M1</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_apple_m3" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_apple_m3') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Apple M3</span>
                </label>
                <label class="filter-checkbox">
                    <input name="processor_apple_m3_max" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_apple_m3_max') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Apple M3 Max</span>
                </label>

                <!-- Другие -->
                <div class="font-medium text-gray-700 mb-1 mt-3">Другие</div>
                <label class="filter-checkbox">
                    <input name="processor_qualcomm" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('processor_qualcomm') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Qualcomm</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Модель процессора -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('processor-model-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Модель процессора</h3>
                    <svg id="processor-model-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="processor-model-filter" class="filter-content hidden mt-3 space-y-2">
                <div class="relative">
                    <input type="text"
                           id="processor-model-search"
                           placeholder="Поиск модели процессора..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onkeyup="filterProcessorModels(this)">
                    <div class="absolute right-2 top-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div id="processor-models-list" class="space-y-2 max-h-48 overflow-y-auto pr-2">
                    @php
                        $processorModels = [
                            // Intel
                            'Intel Core i3-1115G4', 'Intel Core i5-12450HX', 'Intel Celeron N4020',
                            'Intel Core i3-N305', 'Intel Core 5 210H', 'Intel Core i5-13420H',
                            'Intel Celeron N4500', 'Intel Celeron N5100', 'Intel Processor N100',
                            'Intel Core Ultra 7 155H', 'Intel Core i5-12450H', 'Intel Core 5 120U',
                            'Intel Celeron N100', 'Intel Processor N95', 'Intel Processor N150',
                            'Intel Core i7-12700H', 'Intel Core i3-1315U', 'Intel Core Ultra 5 125H',
                            'Intel Core i7-1355U', 'Intel Core i3-1215U', 'Intel Core i3-10110U',
                            'Intel Core i5-11320H', 'Intel Core i5-12500H', 'Intel Core i5-1155G7',
                            'Intel Core i5-1235U', 'Intel Core i5-1335U', 'Intel Core i3-1000NG4',
                            'Intel Core i7-13620H', 'Intel Core i7-14650HX', 'Intel Core Ultra 5 125U',
                            'Intel Pentium Silver N6000', 'Intel Core i7-1195G7', 'Intel Core i5-1334U',
                            'Intel Core i7-13650HX', 'Intel Core i3-1305U', 'Intel Core i3-1220P',
                            'Intel Core i7-14700HX', 'Intel Core i5-13450HX', 'Intel Core i5-14450HX',
                            'Intel Core Ultra 7 255H', 'Intel Core i5-1135G7', 'Intel Core i7-1255U',
                            'Intel Core i7-13700H', 'Intel Core Ultra 7 155U', 'Intel Core Ultra 5 225H',
                            'Intel Core i7-13700HX', 'Intel Core i7-1360P', 'Intel Pentium Silver N5030',
                            'Intel Core Ultra 7 255U', 'Intel Core Ultra 9 285H', 'Intel Core Ultra 9 275HX',
                            'Intel Core Ultra 5 225U', 'Intel Core i7-1185G7', 'Intel Core Ultra 5 226V',
                            'Intel Core 7 240H', 'Intel Core Ultra 5 135H', 'Intel Core Ultra 7 256V',
                            'Intel Core Ultra 7 255HX', 'Intel Core Ultra 7 165U', 'Intel Core Ultra 7 258V',
                            'Intel Core i7-1165G7', 'Intel Core i7-12650H', 'Intel Core i5-11400H',
                            'Intel Processor N97', 'Intel Core i5-1240P', 'Intel Core i7-11390H',
                            'Intel Core i5-10400H', 'Intel Core i9-11900H', 'Intel Core i7-10750H',
                            'Intel Celeron N5095', 'Intel Core i5-1035G1', 'Intel Core i3-N300',
                            'Intel Core 5 220Н', 'Intel Core Ultra 9 288V', 'Intel Core i5-13500H',
                            'Intel Core i5-1030NG7', 'Intel Core i9 13900HK', 'Intel Core 3 N350',
                            'Intel Core 7 150U', 'Intel Core i3-1025G1', 'Intel Processor N200',
                            'Intel Core i9-14900HX', 'Intel Core Ultra 9 185H',

                            // AMD
                            'AMD Ryzen 5 7235HS', 'AMD Ryzen 5 7430U', 'AMD Ryzen 5 8645HS',
                            'AMD Ryzen 7 7445HS', 'AMD Ryzen 7 7735HS', 'AMD Ryzen 5 7520U',
                            'AMD Ryzen 7 5700U', 'AMD Ryzen 5 7530U', 'AMD Ryzen 7 7435HS',
                            'AMD Ryzen 3 7320U', 'AMD Ryzen 5 7535HS', 'AMD Ryzen 9 8940HX',
                            'AMD Ryzen 7 5825U', 'AMD Ryzen 7 7730U', 'AMD Athlon Silver 7120U',
                            'AMD Ryzen 5 220', 'AMD Ryzen AI 5 340', 'AMD Ryzen 7 250',
                            'AMD Ryzen 9 8945HS', 'AMD Ryzen 7 260', 'AMD Ryzen 7 8845HS',
                            'AMD Ryzen 5 5600U', 'AMD Ryzen 5 8540U', 'AMD Ryzen 5 5500U',
                            'AMD Ryzen 5 6600H', 'AMD Ryzen 5 4600H', 'AMD Ryzen 3 7330U',
                            'AMD Ryzen 7 7745HX', 'AMD Ryzen AI MAX+ 395', 'AMD Ryzen AI 7 350',
                            'AMD Ryzen 5 240', 'AMD Ryzen 7 8745HX', 'AMD Ryzen 5 8640HS',
                            'AMD Ryzen 7 8840HX', 'AMD Ryzen AI 5 330', 'AMD Ryzen AI 5 Pro 340',
                            'AMD Athlon Gold 3150U', 'AMD Ryzen 7 3700U', 'AMD Athlon Silver 3050U',
                            'AMD Ryzen 7 7840U', 'AMD Ryzen 7 6800H', 'AMD Ryzen 7 4800H',
                            'AMD Ryzen AI 9 HX 370', 'AMD Ryzen 3 4300U', 'AMD Ryzen 7 7735U',
                            'AMD Ryzen 5 7533HS', 'AMD Ryzen 7 8840HS', 'AMD Ryzen 5 5625U',
                            'AMD Ryzen 5 7640HS', 'AMD Ryzen 7 7435H', 'AMD Ryzen 9 9955HX',

                            // Apple
                            'Apple M2', 'Apple M4', 'Apple M1', 'Apple M3', 'Apple M4 Pro',
                            'Apple M2 Pro', 'Apple M3 Pro', 'Apple M3 Max', 'Apple M4 Max',
                            'Apple M2 Max', 'Apple M5',

                            // Qualcomm/Snapdragon
                            'Snapdragon X Plus X1P-42-100', 'Snapdragon X Elite X1E-78-100',
                            'Snapdragon X X1-26-100'
                        ];

                        // Сортируем по алфавиту
                        sort($processorModels);
                    @endphp

                    @foreach($processorModels as $model)
                        @php
                            $key = 'processor_model_' . Str::slug($model, '_');
                        @endphp
                        <label class="filter-checkbox processor-model-item" data-model="{{ strtolower($model) }}">
                            <input name="{{ $key }}" type="checkbox"
                                   value="{{ $model }}"
                                   class="rounded text-blue-600 focus:ring-blue-500"
                                {{ request()->has($key) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">{{ $model }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <script>
                function filterProcessorModels(input) {
                    const searchTerm = input.value.toLowerCase();
                    const items = document.querySelectorAll('.processor-model-item');

                    items.forEach(item => {
                        const model = item.getAttribute('data-model');
                        if (model.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }
            </script>
        </div>

        <!-- Фильтр: Количество ядер процессора -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('cores-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Количество ядер</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Количество ядер процессора">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="cores-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            @php
                // Уникальные значения ядер из базы данных
                $coresValues = [2, 4, 5, 6, 8, 10, 11, 12, 14, 16, 20, 24];
                sort($coresValues);
            @endphp

            <div id="cores-filter" class="filter-content hidden mt-3 space-y-2">
                @foreach($coresValues as $cores)
                    <label class="filter-checkbox">
                        <input name="cores_{{ $cores }}" type="checkbox"
                               value="1"
                               class="rounded text-blue-600 focus:ring-blue-500"
                            {{ request()->has('cores_' . $cores) ? 'checked' : '' }}>
                        <span
                            class="ml-2 text-gray-700">{{ $cores }} {{ trans_choice('ядро|ядра|ядер', $cores) }}</span>
                    </label>
                @endforeach

                <!-- Для значений в скобках типа "16 (6+10)" -->
                <label class="filter-checkbox">
                    <input name="cores_16_complex" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('cores_16_complex') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">16 ядер (6+10)</span>
                </label>
                <label class="filter-checkbox">
                    <input name="cores_10_complex" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('cores_10_complex') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">10 ядер (8+2)</span>
                </label>
                <label class="filter-checkbox">
                    <input name="cores_12_complex" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('cores_12_complex') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">12 ядер (4+8)</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Тип видеокарты -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('gpu-type-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Тип видеокарты</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Встроенная или дискретная видеокарта">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="gpu-type-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="gpu-type-filter" class="filter-content hidden mt-3 space-y-2">
                <label class="filter-checkbox">
                    <input name="gpu_type_integrated" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('gpu_type_integrated') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Встроенная</span>
                </label>
                <label class="filter-checkbox">
                    <input name="gpu_type_discrete" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('gpu_type_discrete') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Дискретная</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Модель видеокарты -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('gpu-model-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Модель видеокарты</h3>
                    <svg id="gpu-model-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="gpu-model-filter" class="filter-content hidden mt-3 space-y-2">
                <div class="relative">
                    <input type="text"
                           id="gpu-model-search"
                           placeholder="Поиск модели видеокарты..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onkeyup="filterGpuModels(this)">
                    <div class="absolute right-2 top-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm mb-2">
                    <button type="button" onclick="selectAllGpuModels()"
                            class="text-blue-600 hover:text-blue-800 px-2 py-1 rounded hover:bg-blue-50">
                        Выбрать все
                    </button>
                    <button type="button" onclick="deselectAllGpuModels()"
                            class="text-gray-600 hover:text-gray-800 px-2 py-1 rounded hover:bg-gray-100">
                        Снять все
                    </button>
                </div>

                <div id="gpu-models-list" class="space-y-2 max-h-48 overflow-y-auto pr-2">
                    @php
                        $gpuModels = [
                            // Intel
                            'Intel Iris Xe Graphics G4', 'Intel UHD Graphics 600',
                            'Intel UHD Graphics (32EU)', 'Intel UHD Graphics (Jasper Lake 16 EU)',
                            'Intel UHD Graphics', 'Intel UHD Graphics 24',
                            'Intel Arc Graphics', 'Intel UHD Graphics 48 EUs',
                            'Intel Iris Xe Graphics 80 EUs', 'Intel Iris Xe Graphics 80 (EU)',
                            'Intel UHD Graphics 24 (EU)', 'Intel UHD Graphics (Xe 16EUs )',
                            'Intel UHD Graphics 24 (EUs)', 'Intel UHD Graphics 64 EUs',
                            'Intel Iris Xe Graphics', 'Intel Iris Xe Graphics G7 96EUs',
                            'Intel UHD Graphics Xe G4', 'Intel Iris Xe Graphics G7',
                            'Intel Iris Plus Graphics', 'Intel Iris Xe Graphics 96 EUs',
                            'Intel UHD Graphics 64', 'Intel UHD Graphics (32 EUs)',
                            'Intel UHD Graphics (16 EU)', 'Intel Iris Xe Graphics 96 (EU)',
                            'Intel Iris Xe Graphics G7 80 EU', 'Intel Iris Xe Graphics 80',
                            'Intel Iris Xe Graphics 96', 'Intel Arc Graphics (140T)',
                            'Intel Arc Graphics (130T)', 'Intel UHD Graphics 605',
                            'Intel Iris Xe Graphics (80EU)', 'Intel Iris Xe Graphics (96EU)',
                            'Intel UHD Graphics 64 (EU)', 'Intel Iris Xe Graphics G7 80 EUs',
                            'Intel Arc Graphics (130V)', 'Intel Iris Xe Graphics (64EU)',
                            'Intel Arc Graphics (140V)', 'Intel Arc Graphics (8 X-ядер)',
                            'Intel UHD Graphics Xe G4 (48EU)', 'Intel Iris Plus Graphics 645',
                            'Intel Arc A370M', 'Intel HD Graphics',
                            'Intel Arc Graphics (7 X-ядер)', 'Intel Arc Graphics 140V',
                            'Intel UHD Graphics (64EU)', 'Intel Iris Xe Graphics (G7 96EUs)',
                            'Intel UHD Graphics G1', 'Intel UHD Graphics (64EUs)',
                            'Intel UHD Graphics (48EU)', 'Intel UHD Graphics (24EU)',
                            'Intel Arc Graphics (4 X-ядер)', 'Intel Graphics',
                            'Intel UHD Graphics (32 EU)', 'Intel UHD Graphics 630',

                            // NVIDIA
                            'NVIDIA GeForce RTX 3050', 'NVIDIA GeForce RTX 2050',
                            'NVIDIA GeForce RTX 4050', 'NVIDIA GeForce RTX 5070',
                            'NVIDIA GeForce RTX 5060', 'NVIDIA GeForce RTX 5050',
                            'NVIDIA GeForce RTX 4070', 'NVIDIA GeForce RTX 4060',
                            'NVIDIA GeForce MX450', 'NVIDIA GeForce RTX 4080',
                            'NVIDIA GeForce RTX 5090', 'NVIDIA GeForce RTX 5080',
                            'NVIDIA GeForce RTX 5070 Ti', 'NVIDIA GeForce RTX 2000 Ada Gen',
                            'NVIDIA GeForce RTX 3060', 'NVIDIA GeForce RTX 3050 Ti',
                            'NVIDIA GeForce MX550', 'NVIDIA Quadro',
                            'NVIDIA GeForce GTX 1650',

                            // AMD
                            'AMD Radeon RX Vega 7', 'AMD Radeon 680M',
                            'AMD Radeon 610M', 'AMD Radeon Vega 7',
                            'AMD Radeon Graphics', 'AMD Radeon RX Vega 8',
                            'AMD Radeon RX Vega 8 (Ryzen 4000)', 'Radeon 740M',
                            'Radeon 860M', 'AMD Radeon 660M Graphics',
                            'AMD Radeon 840M', 'AMD Radeon Vega 6',
                            'AMD Radeon Vega 8', 'AMD Radeon 8060S',
                            'AMD Radeon 760M', 'AMD Radeon 840M',
                            'AMD Radeon 860M', 'AMD Radeon 820M',
                            'AMD Radeon Vega 10', 'AMD Radeon Vega Graphics',
                            'AMD Radeon 780M Graphics', 'AMD Radeon 890M',
                            'AMD Radeon Vega 5', 'AMD Radeon 740M',
                            'AMD Radeon RX 6550M',

                            // Apple
                            'Apple M2 GPU (8 ядер)', 'Apple M4 GPU',
                            'Apple M1 GPU', 'Apple M3 10-core GPU',
                            'Apple M4 Pro GPU (10 ядер)', 'Apple M2 8-core GPU',
                            'Apple M3 Pro 11-core GPU', 'Apple M4 Pro GPU',
                            'Apple M2 10-core GPU', 'Apple M3 8-core GPU',
                            'Apple M2 Pro 19-core GPU', 'Apple M4 Pro GPU (20 ядер)',
                            'Apple M3 Pro 18-core GPU', 'Apple M3 Max 30-core GPU',
                            'Apple M3 Max 40-core GPU', 'Apple M4 Max GPU (40 ядер)',
                            'Apple M2 Max 30-core GPU', 'Apple M3 Pro 14-core GPU',
                            'Apple M4 Max GPU (32 ядер)', 'Apple M5 GPU',

                            // Qualcomm
                            'Qualcomm Adreno', 'Qualcomm Adreno X1-45',
                            'Qualcomm Adreno (X1-85)',
                        ];

                        // Сортируем по алфавиту
                        sort($gpuModels);
                    @endphp

                    @foreach($gpuModels as $model)
                        @php
                            $key = 'gpu_model_' . Str::slug($model, '_');
                        @endphp
                        <label class="filter-checkbox gpu-model-item" data-model="{{ strtolower($model) }}">
                            <input name="{{ $key }}" type="checkbox"
                                   value="{{ $model }}"
                                   class="rounded text-blue-600 focus:ring-blue-500"
                                {{ request()->has($key) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">{{ $model }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <script>
                function filterGpuModels(input) {
                    const searchTerm = input.value.toLowerCase();
                    const items = document.querySelectorAll('.gpu-model-item');
                    let visibleCount = 0;

                    items.forEach(item => {
                        const modelText = item.querySelector('span').textContent.toLowerCase();
                        if (modelText.includes(searchTerm)) {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Показываем сообщение если ничего не найдено
                    const container = document.getElementById('gpu-models-list');
                    let noResults = container.querySelector('.no-results');

                    if (visibleCount === 0 && searchTerm !== '') {
                        if (!noResults) {
                            noResults = document.createElement('div');
                            noResults.className = 'no-results text-gray-500 text-sm p-2 text-center';
                            noResults.textContent = 'Модели не найдены';
                            container.appendChild(noResults);
                        }
                    } else if (noResults) {
                        noResults.remove();
                    }
                }

                function selectAllGpuModels() {
                    const items = document.querySelectorAll('.gpu-model-item');
                    items.forEach(item => {
                        const checkbox = item.querySelector('input[type="checkbox"]');
                        if (checkbox) {
                            checkbox.checked = true;
                            // Сохраняем в sessionStorage
                            sessionStorage.setItem('gpu_model_' + checkbox.value, 'checked');
                        }
                    });
                }

                function deselectAllGpuModels() {
                    const items = document.querySelectorAll('.gpu-model-item');
                    items.forEach(item => {
                        const checkbox = item.querySelector('input[type="checkbox"]');
                        if (checkbox) {
                            checkbox.checked = false;
                            // Удаляем из sessionStorage
                            sessionStorage.removeItem('gpu_model_' + checkbox.value);
                        }
                    });
                }

                // При загрузке восстанавливаем выбранные значения
                document.addEventListener('DOMContentLoaded', function () {
                    const checkboxes = document.querySelectorAll('#gpu-models-list input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        const savedState = sessionStorage.getItem('gpu_model_' + checkbox.value);
                        if (savedState === 'checked') {
                            checkbox.checked = true;
                        }

                        checkbox.addEventListener('change', function () {
                            if (this.checked) {
                                sessionStorage.setItem('gpu_model_' + this.value, 'checked');
                            } else {
                                sessionStorage.removeItem('gpu_model_' + this.value);
                            }
                        });
                    });
                });
            </script>
        </div>

        <!-- Фильтр: Конфигурация дисков -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('storage-config-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Конфигурация дисков</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Тип накопителей и их сочетание">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="storage-config-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="storage-config-filter" class="filter-content hidden mt-3 space-y-2">
                <label class="filter-checkbox">
                    <input name="storage_config_ssd" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('storage_config_ssd') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">SSD</span>
                </label>
                <label class="filter-checkbox">
                    <input name="storage_config_ssd_m2" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('storage_config_ssd_m2') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">SSD (M.2)</span>
                </label>
                <label class="filter-checkbox">
                    <input name="storage_config_ssd_m2_nvme" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('storage_config_ssd_m2_nvme') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">SSD M.2 NVMe</span>
                </label>
                <label class="filter-checkbox">
                    <input name="storage_config_hdd" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('storage_config_hdd') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">HDD</span>
                </label>
                <label class="filter-checkbox">
                    <input name="storage_config_emmc" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('storage_config_emmc') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">eMMC</span>
                </label>
                <label class="filter-checkbox">
                    <input name="storage_config_ufs" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('storage_config_ufs') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">UFS</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Емкость SSD -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('ssd-capacity-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Емкость SSD</h3>
                    <svg id="ssd-capacity-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="ssd-capacity-filter" class="filter-content hidden mt-3 space-y-2">
                <label class="filter-checkbox">
                    <input name="ssd_capacity_256" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ssd_capacity_256') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">256 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ssd_capacity_512" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ssd_capacity_512') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">512 ГБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ssd_capacity_1024" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ssd_capacity_1024') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">1 ТБ</span>
                </label>
                <label class="filter-checkbox">
                    <input name="ssd_capacity_2048" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('ssd_capacity_2048') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">2 ТБ</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: ОС -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('os-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Операционная система</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Операционная система — это программное обеспечение, управляющее аппаратными ресурсами компьютера и обеспечивающее функционирование прикладных программ.">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="os-filter-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="os-filter" class="filter-content hidden mt-3 space-y-2">
                <!-- Windows -->
                <div class="font-medium text-gray-700 mb-1">Windows</div>
                <label class="filter-checkbox">
                    <input name="os_windows_11_home" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_windows_11_home') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Windows 11 Home</span>
                </label>
                <label class="filter-checkbox">
                    <input name="os_windows_11_pro" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_windows_11_pro') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Windows 11 Pro</span>
                </label>
                <label class="filter-checkbox">
                    <input name="os_windows_11" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_windows_11') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Windows 11</span>
                </label>
                <label class="filter-checkbox">
                    <input name="os_windows_10_home" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_windows_10_home') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Windows 10 Home</span>
                </label>
                <label class="filter-checkbox">
                    <input name="os_windows_10_pro" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_windows_10_pro') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Windows 10 Pro</span>
                </label>
                <label class="filter-checkbox">
                    <input name="os_windows_10" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_windows_10') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Windows 10</span>
                </label>

                <!-- macOS -->
                <div class="font-medium text-gray-700 mb-1 mt-3">macOS</div>
                <label class="filter-checkbox">
                    <input name="os_macos" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_macos') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">macOS</span>
                </label>
                <label class="filter-checkbox">
                    <input name="os_macos_big_sur" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_macos_big_sur') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">macOS Big Sur</span>
                </label>

                <!-- Linux -->
                <div class="font-medium text-gray-700 mb-1 mt-3">Linux</div>
                <label class="filter-checkbox">
                    <input name="os_linux" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_linux') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Linux</span>
                </label>
                <label class="filter-checkbox">
                    <input name="os_ubuntu" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_ubuntu') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Ubuntu</span>
                </label>

                <!-- Без ОС -->
                <div class="font-medium text-gray-700 mb-1 mt-3">Без операционной системы</div>
                <label class="filter-checkbox">
                    <input name="os_dos" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_dos') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">DOS (без ОС)</span>
                </label>

                <!-- Другие -->
                <div class="font-medium text-gray-700 mb-1 mt-3">Другие</div>
                <label class="filter-checkbox">
                    <input name="os_chrome_os" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('os_chrome_os') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Google Chrome OS</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Технология экрана -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('screen-tech-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Технология экрана</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Технология экрана
                                     В настоящее время наиболее распространены типы матриц TN+film, IPS и MVA, а также их разновидности. TN+film — первые TFT панели, выпускаются и сейчас в качестве недорогих экранов, их преимущество — дешевизна производства. Главным недостатком являются небольшие углы просмотра, уменьшение яркости и контрастности, если смотреть сбоку.
                                    IPS — технология производства TFT экранов, была придумана как альтернатива TN дисплеям. Имеет широкие углы обзора, более глубокий чёрный цвет, хорошую цветопередачу. Недостаток — большое время отклика, что делает дислеи малопригодными для игр.
                                    S-IPS — усовершенствованная технология IPS. Благодаря усовершенствованию, удалось достичь уменьшения времени отклика до 5 миллисекунд, что сделало эти дисплеи пригодными для игр. S-IPS II — следующее поколение S-IPS панелей, с уменьшенной энергоёмкостью.
                                    MVA (P-MVA, SVA, WVA) — технология производства TFT дисплеев с улучшенной контрастностью и цветопередачей, но с бо́льшим временем отклика по сравнению с TN-матрицами.">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="screen-tech-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="screen-tech-filter" class="filter-content hidden mt-3 space-y-2">
                <!-- IPS матрицы -->
                <div class="font-medium text-gray-700 mb-1">IPS матрицы</div>
                <label class="filter-checkbox">
                    <input name="screen_tech_ips" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('screen_tech_ips') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">IPS (базовый)</span>
                </label>
                <label class="filter-checkbox">
                    <input name="screen_tech_ips_truetone" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('screen_tech_ips_truetone') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">IPS с True Tone</span>
                </label>
                <label class="filter-checkbox">
                    <input name="screen_tech_ips_liquid_retina" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('screen_tech_ips_liquid_retina') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Liquid Retina XDR</span>
                </label>

                <!-- Другие типы матриц -->
                <div class="font-medium text-gray-700 mb-1 mt-3">Другие типы матриц</div>
                <label class="filter-checkbox">
                    <input name="screen_tech_oled" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('screen_tech_oled') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">OLED</span>
                </label>
                <label class="filter-checkbox">
                    <input name="screen_tech_tn" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('screen_tech_tn') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">TN+Film</span>
                </label>
                <label class="filter-checkbox">
                    <input name="screen_tech_wva" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('screen_tech_wva') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">WVA</span>
                </label>
                <label class="filter-checkbox">
                    <input name="screen_tech_sva" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('screen_tech_sva') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">SVA</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Частота обновления экрана -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('refresh-rate-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Частота обновления экрана</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Частота обновления экрана
Высокие значения частоты обновления делают изображение на экране более плавным.">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="refresh-rate-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="refresh-rate-filter" class="filter-content hidden mt-3 space-y-2">
                <!-- Стандартные -->
                <div class="font-medium text-gray-700 mb-1">Стандартные</div>
                <label class="filter-checkbox">
                    <input name="refresh_rate_60" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('refresh_rate_60') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">60 Гц</span>
                </label>
                <label class="filter-checkbox">
                    <input name="refresh_rate_100" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('refresh_rate_100') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">100 Гц</span>
                </label>

                <!-- Высокие -->
                <div class="font-medium text-gray-700 mb-1 mt-3">Высокие</div>
                <label class="filter-checkbox">
                    <input name="refresh_rate_120" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('refresh_rate_120') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">120 Гц</span>
                </label>
                <label class="filter-checkbox">
                    <input name="refresh_rate_120_promotion" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('refresh_rate_120_promotion') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">120 Гц (Pro Motion)</span>
                </label>

                <!-- Игровые -->
                <div class="font-medium text-gray-700 mb-1 mt-3">Игровые</div>
                <label class="filter-checkbox">
                    <input name="refresh_rate_144" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('refresh_rate_144') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">144 Гц</span>
                </label>
                <label class="filter-checkbox">
                    <input name="refresh_rate_165" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('refresh_rate_165') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">165 Гц</span>
                </label>
                <label class="filter-checkbox">
                    <input name="refresh_rate_180" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('refresh_rate_180') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">180 Гц</span>
                </label>
                <label class="filter-checkbox">
                    <input name="refresh_rate_240" type="checkbox"
                           value="1"
                           class="rounded text-blue-600 focus:ring-blue-500"
                        {{ request()->has('refresh_rate_240') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">240 Гц</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Линейка -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('product-line-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Линейка</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Линейка
Линейка — это модели определенного производителя, объединенные в группу по назначению, конструктивным особенностям и техническим характеристикам.">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="product-line-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="product-line-filter" class="filter-content hidden mt-3 space-y-2">
                <!-- Поле поиска -->
                <div class="relative mb-3">
                    <input type="text"
                           id="product-line-search"
                           placeholder="Поиск серии ноутбука..."
                           value="{{ request('product_line_search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onkeyup="filterProductLines(this)">
                    <div class="absolute right-2 top-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Список линеек -->
                <div id="product-lines-list" class="space-y-2 max-h-64 overflow-y-auto pr-2">
                    @php
                        $productLines = [
                            // Lenovo
                            'LOQ (Lenovo)', 'V (Lenovo)', 'IdeaPad Slim (Lenovo)', 'IdeaPad 1 (Lenovo)',
                            'V15 (Lenovo)', 'ThinkBook (Lenovo)', 'IdeaPad 3 (Lenovo)', 'ThinkPad T (Lenovo)',
                            'ThinkPad E (Lenovo)', 'ThinkPad (Lenovo)', 'ThinkPad T16 (Lenovo)',
                            'ThinkPad X (Lenovo)', 'ThinkPad L (Lenovo)', 'IdeaPad Gaming 3 (Lenovo)',
                            'Chromebook (Lenovo)', 'Yoga (Lenovo)', 'IdeaPad L (Lenovo)', 'Legion 5 Pro (Lenovo)',
                            'Legion 5 (Lenovo)',

                            // Asus
                            'Vivobook Go 15 (Asus)', 'TUF Gaming (Asus)', 'VivoBook (Asus)', 'VivoBook 15 (Asus)',
                            'VivoBook S 16 (Asus)', 'ZenBook (Asus)', 'VivoBook S (Asus)', 'ROG Strix G16 (Asus)',
                            'ROG Zephyrus (Asus)', 'ExpertBook (Asus)', 'ROG Flow (Asus)', 'ROG Strix (Asus)',
                            'Vivobook S 14 (Asus)', 'V16 (Asus)', 'Vivobook 16 (Asus)', 'Zenbook Pro (Asus)',
                            'VivoBook 14 (Asus)', 'Vivobook 13 (Asus)', 'ExpertBook B5 Flip (Asus)',
                            'Zenbook S (Asus)', 'VivoBook 17 (Asus)', 'ProArt (Asus)', 'ROG Strix G18 G814 (Asus)',
                            'ExpertBook B5 (Asus)', 'TUF Gaming A15 (Asus)', 'TUF Gaming A17 (Asus)',

                            // Acer
                            'Aspire 3 (Acer)', 'Extensa (Acer)', 'Aspire Lite (Acer)', 'Aspire 5 (Acer)',
                            'Nitro V 15 (Acer)', 'Aspire Go (Acer)', 'Nitro (Acer)', 'Aspire 15 (Acer)',
                            'TravelMate (Acer)', 'Swift AI (Acer)', 'Predator Helios (Acer)', 'Swift Go (Acer)',
                            'Swift 3 (Acer)', 'Swift X (Acer)', 'Aspire 7 (Acer)', 'Nitro 5 (Acer)',
                            'Aspire 17 (Acer)', 'Gadget E10 ETBook (Acer)',

                            // HP
                            'Victus (HP)', 'Laptop 15 (HP)', 'Pavilion (HP)', 'EliteBook (HP)', 'ProBook (HP)',
                            '255 (HP)', '250 (HP)', '240 (HP)', 'ZBook (HP)', '250 G9 (HP)', 'Probook 440 G9 (HP)',

                            // Dell
                            'Vostro (Dell)', 'XPS (Dell)', 'Latitude (Dell)',

                            // Apple
                            'MacBook Air M2 2022 (Apple)', 'Macbook Air (Apple)', 'MacBook Air M1 2020 (Apple)',
                            'MacBook Pro (Apple)', 'MacBook Pro M1 2020 (Apple)',

                            // MSI
                            'Modern (MSI)', 'Thin A15 (MSI)', 'Crosshair (MSI)', 'Katana (MSI)', 'Sword (MSI)',
                            'Prestige (MSI)', 'Vector (MSI)', 'Venture (MSI)', 'Cyborg 15 (MSI)', 'Pulse (MSI)',
                            'Stealth 16 (MSI)', 'CreatorPro (MSI)', 'Cyborg (MSI)', 'Thin 15 (MSI)',

                            // Huawei/Honor
                            'MateBook D 16 (Huawei)', 'MateBook (Huawei)', 'MagicBook (Honor)',
                            'MagicBook Pro (Honor)', 'Art 14 (Honor)', 'MateBook 13 (Huawei)',
                            'MateBook D 15 (Huawei)', 'MateBook X Pro (Huawei)',

                            // Chuwi
                            'HeroBook Pro (Chuwi)', 'CoreBook X (Chuwi)', 'GemiBook XPro (Chuwi)',
                            'GemiBook Plus (Chuwi)', 'HeroBook Plus (Chuwi)', 'CoreBook Max (Chuwi)',
                            'CoreBook XPro (Chuwi)', 'FreeBook (Chuwi)',

                            // Horizont
                            'H-book 15 (Horizont)', 'H-book 14 (Horizont)', 'H-book 16 (Horizont)',

                            // KUU
                            'А5 (KUU)', 'Yepbook 2 (KUU)', 'А6 (KUU)', 'G3 Pro (KUU)', 'G5 (KUU)', 'XBook (KUU)', 'G3 (KUU)',

                            // Maibenben
                            'M657 (Maibenben)', 'M645 (Maibenben)', 'Medio (Maibenben)', 'M557 (Maibenben)',
                            'X-Treme Typhoon (Maibenben)', 'Perfectum (Maibenben)',

                            // Ninkear
                            'N16 Air (Ninkear)', 'N15 Pro (Ninkear)',

                            // Infinix
                            'Inbook Y3 Plus (Infinix)', 'Inbook (Infinix)', 'Inbook Y3 Max (Infinix)',
                            'Inbook Y2 Plus (Infinix)', 'Inbook Air (Infinix)',

                            // Gigabyte
                            'Gaming (Gigabyte)', 'G6 (Gigabyte)',

                            // Другие
                            'WorkBook (HIPER)', 'Dzen (HIPER)', 'Acebook (Blackview)', 'L16 SE (Vision)',
                            'EVE (DIGMA)', 'L16 Origin (Vision)',
                        ];

                        // Сортируем по алфавиту
                        sort($productLines);
                    @endphp

                    @foreach($productLines as $line)
                        @php
                            $key = 'line_' . Str::slug($line, '_');
                        @endphp
                        <label class="filter-checkbox product-line-item" data-line="{{ strtolower($line) }}">
                            <input name="{{ $key }}" type="checkbox"
                                   value="{{ $line }}"
                                   class="rounded text-blue-600 focus:ring-blue-500"
                                {{ request()->has($key) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">{{ $line }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <script>
                function filterProductLines(input) {
                    const searchTerm = input.value.toLowerCase();
                    const items = document.querySelectorAll('.product-line-item');
                    let visibleCount = 0;

                    items.forEach(item => {
                        const lineText = item.querySelector('span').textContent.toLowerCase();
                        if (lineText.includes(searchTerm)) {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Показываем сообщение если ничего не найдено
                    const container = document.getElementById('product-lines-list');
                    let noResults = container.querySelector('.no-results');

                    if (visibleCount === 0 && searchTerm !== '') {
                        if (!noResults) {
                            noResults = document.createElement('div');
                            noResults.className = 'no-results text-gray-500 text-sm p-2 text-center';
                            noResults.textContent = 'Серии не найдены';
                            container.appendChild(noResults);
                        }
                    } else if (noResults) {
                        noResults.remove();
                    }
                }
            </script>
        </div>

        <!-- Фильтр: Цвет корпуса -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('case-color-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Цвет корпуса</h3>
                    <svg id="case-color-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="case-material-filter" class="filter-content hidden mt-3 space-y-2">
                <!-- Основные материалы -->
                <div class="space-y-2">
                    @php
                        $materials = [
                            'plastic' => ['label' => 'Пластик', 'color' => 'bg-gray-100'],
                            'aluminum' => ['label' => 'Алюминий', 'color' => 'bg-blue-50'],
                            'metal' => ['label' => 'Металл', 'color' => 'bg-gray-50'],
                            'magnesium' => ['label' => 'Магниевый сплав', 'color' => 'bg-purple-50'],
                            'carbon' => ['label' => 'Карбон', 'color' => 'bg-gray-800 text-white'],
                        ];
                    @endphp

                    @foreach($materials as $key => $materialData)
                        <label
                            class="filter-checkbox flex items-center p-2 rounded hover:bg-gray-50 {{ $materialData['color'] }}">
                            <input type="checkbox"
                                   class="rounded text-blue-600 focus:ring-blue-500"
                                   name="case_material_{{ $key }}"
                                   value="1"
                                {{ request()->has("case_material_{$key}") ? 'checked' : '' }}>
                            <span class="ml-3 text-gray-700 font-medium">{{ $materialData['label'] }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Комбинированные материалы -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="font-medium text-gray-700 mb-2">Комбинированные материалы</div>
                    <div class="space-y-2">
                        @php
                            $combinedMaterials = [
                                'plastic_metal' => ['label' => 'Пластик + Металл', 'color' => 'bg-gradient-to-r from-gray-100 to-gray-50'],
                                'plastic_aluminum' => ['label' => 'Пластик + Алюминий', 'color' => 'bg-gradient-to-r from-gray-100 to-blue-50'],
                                'metal_plastic' => ['label' => 'Металл + Пластик', 'color' => 'bg-gradient-to-r from-gray-50 to-gray-100'],
                                'magnesium_metal' => ['label' => 'Магниевый сплав + Металл', 'color' => 'bg-gradient-to-r from-purple-50 to-gray-50'],
                                'metal_magnesium' => ['label' => 'Металл + Магниевый сплав', 'color' => 'bg-gradient-to-r from-gray-50 to-purple-50'],
                                'plastic_carbon' => ['label' => 'Пластик (углепластик)', 'color' => 'bg-gradient-to-r from-gray-100 to-gray-800 text-gray-800'],
                            ];
                        @endphp

                        @foreach($combinedMaterials as $key => $materialData)
                            <label
                                class="filter-checkbox flex items-center p-2 rounded hover:opacity-90 {{ $materialData['color'] }}">
                                <input type="checkbox"
                                       class="rounded text-blue-600 focus:ring-blue-500"
                                       name="case_material_{{ $key }}"
                                       value="1"
                                    {{ request()->has("case_material_{$key}") ? 'checked' : '' }}>
                                <span class="ml-3 text-gray-700 font-medium">{{ $materialData['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Специальные материалы -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="font-medium text-gray-700 mb-2">Специальные материалы</div>
                    <div class="space-y-2">
                        @php
                            $specialMaterials = [
                                'carbon_fiber' => ['label' => 'Углепластик', 'color' => 'bg-gray-900 text-white'],
                                'pc_cf' => ['label' => 'PC + 20% CF', 'color' => 'bg-gradient-to-r from-gray-300 to-gray-700 text-gray-800'],
                            ];
                        @endphp

                        @foreach($specialMaterials as $key => $materialData)
                            <label
                                class="filter-checkbox flex items-center p-2 rounded hover:opacity-90 {{ $materialData['color'] }}">
                                <input type="checkbox"
                                       class="rounded text-white focus:ring-white"
                                       name="case_material_{{ $key }}"
                                       value="1"
                                    {{ request()->has("case_material_{$key}") ? 'checked' : '' }}>
                                <span class="ml-3 font-medium">{{ $materialData['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Фильтр: Материал корпуса -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('case-material-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Материал корпуса</h3>
                    <svg id="case-material-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="case-material-filter" class="filter-content hidden mt-3 space-y-2">
                <label class="filter-checkbox">
                    <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500" name="case-material"
                           value="plastic">
                    <span class="ml-2 text-gray-700">Пластик</span>
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500" name="case-material"
                           value="aluminum">
                    <span class="ml-2 text-gray-700">Алюминий</span>
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500" name="case-material"
                           value="magnesium">
                    <span class="ml-2 text-gray-700">Магниевый сплав</span>
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500" name="case-material"
                           value="carbon">
                    <span class="ml-2 text-gray-700">Карбон</span>
                </label>
            </div>
        </div>

        <!-- Фильтр: Объем видеопамяти -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('vram-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Объем видеопамяти</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Объем видеопамяти
Локальная видеопамять — это внутренняя оперативная память видеокарты, отвечающая за вывод изображения на экран монитора. Встроенные видеокарты не имеют локальной видеопамяти и используют оперативную память компьютера.">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="vram-filter-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="vram-filter" class="filter-content hidden mt-3 space-y-1">
                @php
                    $vramOptions = [2, 4, 6, 8, 12, 16, 24];
                @endphp

                @foreach($vramOptions as $vram)
                    <label class="filter-checkbox flex items-center">
                        <input type="checkbox"
                               class="rounded text-blue-600 focus:ring-blue-500"
                               name="vram[]"
                               value="{{ $vram }}"
                            {{ in_array($vram, request('vram', [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">{{ $vram }} ГБ</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Фильтр: Тактовая частота -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('cpu-freq-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Тактовая частота</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Тактовая частота
Характеристика, которая имеет ключевое воздействие на производительность процессора. Чем больше - тем лучше. В некоторых процессорах предусмотрена Турбо-частота. Эта технология позволяет “разогнать” процессор сверх нормы, если не превышаются лимиты на температуру, мощность и то">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="cpu-freq-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="cpu-freq-filter" class="filter-content hidden mt-3 space-y-3">
                <label class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                            <span class="text-gray-600">🐢</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Низкая</div>
                            <div class="text-sm text-gray-600">До 2 ГГц</div>
                        </div>
                    </div>
                    <input type="radio"
                           name="cpu_freq_range"
                           value="low"
                           class="w-5 h-5 text-blue-600"
                        {{ request('cpu_freq_range') == 'low' ? 'checked' : '' }}>
                </label>

                <label class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mr-3">
                            <span class="text-blue-600">🏃</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Средняя</div>
                            <div class="text-sm text-gray-600">2-3 ГГц</div>
                        </div>
                    </div>
                    <input type="radio"
                           name="cpu_freq_range"
                           value="medium"
                           class="w-5 h-5 text-blue-600"
                        {{ request('cpu_freq_range') == 'medium' ? 'checked' : '' }}>
                </label>

                <label class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center mr-3">
                            <span class="text-green-600">⚡</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Высокая</div>
                            <div class="text-sm text-gray-600">3-4 ГГц</div>
                        </div>
                    </div>
                    <input type="radio"
                           name="cpu_freq_range"
                           value="high"
                           class="w-5 h-5 text-blue-600"
                        {{ request('cpu_freq_range') == 'high' ? 'checked' : '' }}>
                </label>

                <label class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center mr-3">
                            <span class="text-purple-600">🚀</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Максимальная</div>
                            <div class="text-sm text-gray-600">4 ГГц и выше</div>
                        </div>
                    </div>
                    <input type="radio"
                           name="cpu_freq_range"
                           value="max"
                           class="w-5 h-5 text-blue-600"
                        {{ request('cpu_freq_range') == 'max' ? 'checked' : '' }}>
                </label>
            </div>
        </div>

        <!-- Фильтр: Трансформер -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('transformer-filter')">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-2">
                        <h3 class="font-semibold text-gray-900">Трансформер</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                title="Трансформер
Трансформеры отличаются от классических ноутбуков способностью 'трансформироваться' в планшетный ПК. Для них характерно наличие сенсорного экрана, поворотного механизма с возможностью вращения дисплея до 360° и даже отсоединямой клавиатуры.">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <svg id="transformer-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="transformer-filter" class="filter-content hidden mt-3">
                <div class="flex space-x-2">
                    <label class="flex-1">
                        <input type="radio"
                               name="transformer"
                               value="yes"
                               class="sr-only peer"
                            {{ request('transformer') == 'yes' ? 'checked' : '' }}>
                        <div class="w-full py-3 px-4 text-center border-2 rounded-lg cursor-pointer
                        peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700
                        hover:border-blue-300 hover:bg-blue-50">
                            <span class="font-medium">Да</span>
                        </div>
                    </label>

                    <label class="flex-1">
                        <input type="radio"
                               name="transformer"
                               value="no"
                               class="sr-only peer"
                            {{ request('transformer') == 'no' ? 'checked' : '' }}>
                        <div class="w-full py-3 px-4 text-center border-2 rounded-lg cursor-pointer
                        peer-checked:border-gray-500 peer-checked:bg-gray-100 peer-checked:text-gray-700
                        hover:border-gray-300 hover:bg-gray-50">
                            <span class="font-medium">Нет</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Фильтр: Запас энергии -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('battery-capacity-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Запас энергии</h3>
                    <svg id="battery-capacity-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="battery-capacity-filter" class="filter-content hidden mt-3">
                <!-- Популярные значения -->
                <div class="mb-4">
                    <div class="text-sm font-medium text-gray-700 mb-2">Популярные емкости</div>
                    <div class="grid grid-cols-3 gap-2">
                        @php
                            $popularCapacities = [50, 60, 70, 80, 90, 100];
                        @endphp

                        @foreach($popularCapacities as $capacity)
                            <label class="relative">
                                <input type="checkbox"
                                       name="battery_capacities[]"
                                       value="{{ $capacity }}"
                                       class="sr-only peer"
                                    {{ is_array(request('battery_capacities')) && in_array($capacity, request('battery_capacities')) ? 'checked' : '' }}>
                                <div class="w-full p-3 border rounded-lg text-center cursor-pointer
                           peer-checked:border-blue-500 peer-checked:bg-blue-50
                           hover:border-blue-300 hover:bg-blue-50">
                                    <div class="font-medium">{{ $capacity }} Вт·ч</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Диапазоны -->
                <div class="space-y-2">
                    @php
                        $ranges = [
                            ['value' => 'under_50', 'label' => 'До 50 Вт·ч', 'min' => 0, 'max' => 50],
                            ['value' => '50_70', 'label' => '50-70 Вт·ч', 'min' => 50, 'max' => 70],
                            ['value' => '70_90', 'label' => '70-90 Вт·ч', 'min' => 70, 'max' => 90],
                            ['value' => 'over_90', 'label' => '90+ Вт·ч', 'min' => 90, 'max' => 200],
                        ];
                    @endphp

                    @foreach($ranges as $range)
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox"
                                   name="battery_range[]"
                                   value="{{ $range['value'] }}"
                                   class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                {{ is_array(request('battery_range')) && in_array($range['value'], request('battery_range')) ? 'checked' : '' }}>
                            <div class="ml-3 flex-1">
                                <span class="font-medium text-gray-800">{{ $range['label'] }}</span>
                                <div class="text-sm text-gray-600 mt-1">
                                    @if($range['min'] == 0)
                                        Менее {{ $range['max'] }} Вт·ч
                                    @elseif($range['max'] == 200)
                                        Более {{ $range['min'] }} Вт·ч
                                    @else
                                        {{ $range['min'] }}-{{ $range['max'] }} Вт·ч
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Фильтр: Ethernet (LAN) -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('ethernet-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Ethernet (LAN)</h3>
                    <svg id="ethernet-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="ethernet-filter" class="filter-content hidden mt-3">
                <div class="flex space-x-2">
                    <label class="flex-1">
                        <input type="radio"
                               name="ethernet"
                               value="yes"
                               class="sr-only peer"
                            {{ request('ethernet') == 'yes' ? 'checked' : '' }}>
                        <div class="w-full py-3 px-4 text-center border-2 rounded-lg cursor-pointer
                        peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700
                        hover:border-blue-300 hover:bg-blue-50">
                            <span class="font-medium">Есть</span>
                            <div class="text-xs text-gray-600 mt-1">LAN порт в наличии</div>
                        </div>
                    </label>

                    <label class="flex-1">
                        <input type="radio"
                               name="ethernet"
                               value="no"
                               class="sr-only peer"
                            {{ request('ethernet') == 'no' ? 'checked' : '' }}>
                        <div class="w-full py-3 px-4 text-center border-2 rounded-lg cursor-pointer
                        peer-checked:border-gray-500 peer-checked:bg-gray-100 peer-checked:text-gray-700
                        hover:border-gray-300 hover:bg-gray-50">
                            <span class="font-medium">Нет</span>
                            <div class="text-xs text-gray-600 mt-1">Без LAN порта</div>
                        </div>
                    </label>
                </div>

                <!-- Дополнительная информация -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">
                        <div class="font-medium mb-1">Что такое Ethernet (LAN)?</div>
                        <p>Проводное сетевое подключение. Включает все варианты: 100 Mbit, 1 Gbit, 2.5 Gbit и др.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Фильтр: Всего USB-портов -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('usb-ports-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Всего USB-портов</h3>
                    <svg id="usb-ports-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="usb-ports-filter" class="filter-content hidden mt-3">
                <div class="space-y-2">
                    @php
                        $portOptions = [
                            ['value' => 2, 'label' => '2 порта', 'desc' => 'Минимальное количество'],
                            ['value' => 3, 'label' => '3 порта', 'desc' => 'Стандартное количество'],
                            ['value' => 4, 'label' => '4 порта', 'desc' => 'Комфортное количество'],
                            ['value' => '5_6', 'label' => '5-6 портов', 'desc' => 'Максимальное количество'],
                        ];
                    @endphp

                    @foreach($portOptions as $option)
                        <label class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer
                     {{ (is_array(request('usb_ports')) && in_array($option['value'], request('usb_ports'))) || request('usb_ports') == $option['value'] ? 'border-blue-300 bg-blue-50' : '' }}">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       name="usb_ports[]"
                                       value="{{ $option['value'] }}"
                                       class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                    {{ is_array(request('usb_ports')) && in_array($option['value'], request('usb_ports')) ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">{{ $option['label'] }}</span>
                                    <div class="text-sm text-gray-600">{{ $option['desc'] }}</div>
                                </div>
                            </div>
                            <div class="text-gray-400">
                                @if($option['value'] == 2)
                                    🔌🔌
                                @elseif($option['value'] == 3)
                                    🔌🔌🔌
                                @elseif($option['value'] == 4)
                                    🔌🔌🔌🔌
                                @else
                                    🔌🔌🔌🔌🔌
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Фильтр: Thunderbolt -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('thunderbolt-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Thunderbolt</h3>
                    <svg id="thunderbolt-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="thunderbolt-filter" class="filter-content hidden mt-3">
                <div class="flex space-x-2">
                    <label class="flex-1">
                        <input type="radio"
                               name="thunderbolt"
                               value="yes"
                               class="sr-only peer"
                            {{ request('thunderbolt') == 'yes' ? 'checked' : '' }}>
                        <div class="w-full py-3 px-4 text-center border-2 rounded-lg cursor-pointer
                        peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700
                        hover:border-blue-300 hover:bg-blue-50">
                            <span class="font-medium">Есть</span>
                            <div class="text-xs text-gray-600 mt-1">Thunderbolt порт</div>
                            <div class="text-xs text-blue-500 mt-1">USB Type-C</div>
                        </div>
                    </label>

                    <label class="flex-1">
                        <input type="radio"
                               name="thunderbolt"
                               value="no"
                               class="sr-only peer"
                            {{ request('thunderbolt') == 'no' ? 'checked' : '' }}>
                        <div class="w-full py-3 px-4 text-center border-2 rounded-lg cursor-pointer
                        peer-checked:border-gray-500 peer-checked:bg-gray-100 peer-checked:text-gray-700
                        hover:border-gray-300 hover:bg-gray-50">
                            <span class="font-medium">Нет</span>
                            <div class="text-xs text-gray-600 mt-1">Без Thunderbolt</div>
                        </div>
                    </label>
                </div>

                <!-- Дополнительная информация -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">
                        <div class="font-medium mb-1">Что такое Thunderbolt?</div>
                        <p>Скоростной интерфейс для передачи данных, видео и питания через USB Type-C порт.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Фильтр: Кириллица на клавиатуре -->
        <div class="filter-group">
            <div class="filter-header" onclick="toggleFilter('cyrillic-keyboard-filter')">
                <div class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-gray-900">Кириллица на клавиатуре</h3>
                    <svg id="cyrillic-keyboard-filter-icon"
                         class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div id="cyrillic-keyboard-filter" class="filter-content hidden mt-3">
                <div class="flex space-x-2">
                    <label class="flex-1">
                        <input type="radio"
                               name="cyrillic_keyboard"
                               value="yes"
                               class="sr-only peer"
                            {{ request('cyrillic_keyboard') == 'yes' ? 'checked' : '' }}>
                        <div class="w-full py-3 px-4 text-center border-2 rounded-lg cursor-pointer
                        peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700
                        hover:border-blue-300 hover:bg-blue-50">
                            <span class="font-medium">Есть</span>
                            <div class="text-xs text-gray-600 mt-1">Кириллица на клавишах</div>
                        </div>
                    </label>

                    <label class="flex-1">
                        <input type="radio"
                               name="cyrillic_keyboard"
                               value="no"
                               class="sr-only peer"
                            {{ request('cyrillic_keyboard') == 'no' ? 'checked' : '' }}>
                        <div class="w-full py-3 px-4 text-center border-2 rounded-lg cursor-pointer
                        peer-checked:border-gray-500 peer-checked:bg-gray-100 peer-checked:text-gray-700
                        hover:border-gray-300 hover:bg-gray-50">
                            <span class="font-medium">Нет</span>
                            <div class="text-xs text-gray-600 mt-1">Только латиница</div>
                        </div>
                    </label>
                </div>

                <!-- Дополнительная информация -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">
                        <div class="font-medium mb-1">Что означает "Кириллица на клавиатуре"?</div>
                        <p class="mb-2">Русские буквы нанесены на клавиши. Включает все варианты:</p>
                        <ul class="list-disc pl-4 space-y-1 text-xs">
                            <li>Стандартная кириллица</li>
                            <li>Заводская наклейка</li>
                            <li>Сенсорная площадка с кириллицей</li>
                            <li>Не заводская наклейка</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Кнопка применения фильтров -->
        <div class="mt-6 pt-4 border-t border-gray-100">
            <button
                id="filter-submit"
                type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Показать результаты
            </button>
        </div>
    </form>

</aside>
<!-- Overlay для затемнения фона -->
<div id="filters-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>


