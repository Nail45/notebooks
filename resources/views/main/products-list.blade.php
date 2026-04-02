<meta name="csrf-token" content="{{ csrf_token() }}">
@if($notebooks->count() > 0)
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-10 w-full "
       id="products-container">
    @foreach($notebooks as $notebook)
      <div class="product-card bg-white rounded-xl shadow-sm border border-gray-100
                   hover:shadow-lg transition-all duration-300 hover:-translate-y-1">

        <a href="{{route('notebook.show', ['notebook'=>$notebook->slug])}}" class="block p-4">
          <!-- Рейтинг -->
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-1">
              <div class="flex items-center">
                @if($notebook->rating && $notebook->rating > 0)
                  <!-- Градиентная звезда -->
                  <div class="relative">
                    <!-- Фон -->
                    <svg class="w-6 h-6 text-gray-200" fill="currentColor"
                         viewBox="0 0 20 20">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>

                    <!-- Заполненная часть -->
                    <div class="absolute top-0 left-0 overflow-hidden"
                         style="width: {{ ($notebook->rating / 5) * 100 }}%">
                      <svg class="w-6 h-6" fill="url(#gradient{{ $notebook->id }})"
                           viewBox="0 0 20 20">
                        <defs>
                          <linearGradient id="gradient{{ $notebook->id }}" x1="0%"
                                          y1="0%" x2="100%" y2="0%">
                            <stop offset="0%"
                                  style="stop-color:#fbbf24;stop-opacity:1"/>
                            <stop offset="100%"
                                  style="stop-color:#f59e0b;stop-opacity:1"/>
                          </linearGradient>
                        </defs>
                        <path
                          d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                      </svg>
                    </div>
                  </div>

                  <!-- Рейтинг текст -->
                  <span class="ml-2 text-sm font-bold text-gray-900">
                                                    {{ number_format($notebook->rating, 1) }}
                                                </span>
                @else
                  <!-- Нет рейтинга -->
                  <div class="flex items-center text-gray-400">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span class="text-sm">Нет оценок</span>
                  </div>
                @endif
              </div>

              <!-- Количество отзывов -->
              <span class="text-xs text-gray-500">
                             ({{ $notebook->feedbacks ? $notebook->feedbacks->count() : 0 }})
                        </span>
            </div>
          </div>

          <!-- Изображение -->
          <div class="aspect-square mb-4 flex items-center justify-center">
            <img loading="lazy" src="{{ notebook_image($notebook->slug ?? $notebook['id']) }}"
                 alt="{{ $notebook->title }}"
                 class="max-h-48 object-contain"
            >
          </div>

          <!-- Производитель -->
          <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">
            {{ $notebook->manufacturer }}
          </div>

          <!-- Название -->
          <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2
                           hover:text-blue-600 transition-colors">
            {{ $notebook->title }}
          </h3>

          <!-- Цена -->
          <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div>
              @if($notebook->discount > 0)
                <div class="flex items-center space-x-2">
                  <div class="text-xl font-bold text-gray-900">
                    {{ number_format($notebook->price - ($notebook->price * $notebook->discount / 100), 2) }}
                    ₽
                  </div>
                  <div class="text-sm text-gray-500 line-through">
                    {{ number_format($notebook->price, 2) }} ₽
                  </div>
                </div>
              @else
                <div class="text-xl font-bold text-gray-900">
                  {{ number_format($notebook->price, 2) }} ₽
                </div>
              @endif

              <!-- Рассрочка -->
              @if($notebook->installment_available)
                <div class="text-xs text-green-600 font-medium mt-1">
                  <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ number_format(($notebook->price - ($notebook->price * $notebook->discount / 100)) / 12, 2) }}
                  ₽/мес
                </div>
              @endif
            </div>

            <!-- Кнопка добавления в корзину -->
            <button type="button"
                    id="cart-btn-{{ $notebook->id }}"
                    class="px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-xs font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center justify-center group">
              <svg class="h-3.5 w-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              В корзину
            </button>

          </div>
        </a>
      </div>
    @endforeach
  </div>

  <!-- Индикатор, что товары загружены через AJAX -->
  <div class="hidden" id="ajax-indicator" data-loaded="true"></div>
@else
  <div class="text-center py-12">
    <div class="text-gray-500 mb-4">Товары не найдены</div>
    <button type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            onclick="resetFilters()">
      Сбросить фильтры
    </button>
  </div>
@endif
