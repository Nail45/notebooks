@if(isset($notebooks) && count($notebooks) > 0)
  <div class="sticky top-0 px-4 py-2 bg-gradient-to-r from-pink-50 to-white border-b border-pink-100
                text-xs font-medium text-pink-700 z-10">
        <span class="flex items-center">
            <svg class="w-4 h-4 mr-1.5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Найдено {{ count($notebooks) }} ноутбук{{ count($notebooks) != 1 ? 'ов' : '' }}
        </span>
  </div>

  {{-- Список результатов --}}
  <div class="divide-y divide-gray-100">
    @foreach($notebooks as $notebook)
      <a href="/notebook/{{ $notebook['slug'] }}"
         class="search-result-item flex items-start p-4 hover:bg-pink-50 transition-all duration-200
                      group {{ $loop->last ? '' : 'border-b border-gray-100' }}">

        {{-- Изображение --}}
        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200
                            rounded-lg overflow-hidden shadow-sm group-hover:shadow-md transition">
          <img src="{{ notebook_image($notebook['slug'] ?? $notebook['id']) }}"
               alt="{{ $notebook['manufacturer'] }}"
               class="w-full h-full object-cover"
               loading="lazy">
        </div>

        {{-- Информация о товаре --}}
        <div class="flex-1 ml-3 min-w-0">
          <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 flex-1">
              {{-- Название --}}
              <h4 class="text-sm font-semibold text-gray-900 group-hover:text-pink-600
                                       transition-colors line-clamp-2 mb-0.5">
                {{ $notebook['title'] ?? $notebook['manufacturer'] . ' Ноутбук' }}
              </h4>

              {{-- Линейка/Модель --}}
              @if(!empty($notebook['line']))
                <p class="text-xs text-gray-500 truncate">
                  {{ $notebook['line'] }}
                </p>
              @endif
            </div>

            {{-- Цена --}}
            <div class="flex-shrink-0 text-right">
                            <span class="text-sm font-bold text-gray-900 whitespace-nowrap">
                                {{ $notebook['price'] }} ₽
                            </span>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
          </div>
        </div>
      </a>
    @endforeach
  </div>

  <div class="p-3 bg-gradient-to-r from-pink-50 to-white border-t border-pink-100 text-center sticky bottom-0">
    <a href="{{route('search.index', [$query])}}"
       class="inline-flex items-center px-4 py-1.5 bg-pink-600 hover:bg-pink-700
                  text-white text-sm font-medium rounded-md transition-all duration-200
                  shadow-sm hover:shadow-md">
      <span>Все результаты по запросу "{{ $query }}"</span>
      <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </a>
  </div>
@else
  {{-- Нет результатов --}}
  <div class="p-8 text-center">
    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
      <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
    </div>
    <p class="text-gray-900 font-medium text-lg mb-1">Ничего не найдено</p>
    <p class="text-gray-500 text-sm mb-4">
      По запросу «<span class="font-semibold text-pink-600">{{ $query }}</span>»
    </p>
    <div class="text-xs text-gray-400">
      <p>Попробуйте изменить поисковый запрос</p>
      <p class="mt-1">Например: MacBook, Asus, Lenovo, i5, 16GB</p>
    </div>
  </div>
@endif
