<!-- Шаблон корзины -->
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">

  @if($cartProducts->isEmpty())
    <!-- Пустая корзина -->
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
      <div class="max-w-md mx-auto">
        <div class="flex justify-center mb-6">
          <div class="h-24 w-24 rounded-full bg-purple-100 flex items-center justify-center">
            <svg class="h-12 w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-3">Ваша корзина пуста</h2>
        <p class="text-gray-600 mb-6">Добавьте товары, чтобы продолжить покупки</p>
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-lg hover:shadow-xl">
          <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          Перейти в каталог
        </a>
      </div>
    </div>
  @else
    <div class="max-w-7xl mx-auto">
      <!-- Заголовок -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Корзина покупок</h1>
        <p class="mt-2 text-gray-600">Товары, добавленные в вашу корзину</p>
      </div>

      <!-- Уведомления -->
      @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
          {{ session('error') }}
        </div>
      @endif

      <!-- Корзина с товарами -->
      <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Список товаров -->
        <div class="lg:col-span-8">
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">

            <!-- Список товаров -->
            <div class="divide-y divide-gray-200" id="cart-items-container">
              @forelse($cartProducts as $product)
                @php
                  $isObject = is_object($product);
                  $notebook = $isObject ? $product->notebook : $product['notebook'];
                  $productId = $isObject ? $product->notebook_id : $product['notebook_id'];
                  $count = $isObject ? $product->count : $product['count'];
                  $title = $notebook->title ?? $notebook['title'];
                  $price = $notebook->price ?? $notebook['price'];
                  $rating = $notebook->rating ?? $notebook['rating'] ?? null;
                  $slug = $notebook->slug ?? $notebook['id'] ?? $productId;
                  $itemId = auth()->check() ? $product->id : 'session_' . $productId;
                  $totalPrice = $price * $count;
                @endphp

                <div class="cart-item p-6 hover:bg-gray-50 transition-colors duration-200"
                     data-product-id="{{ $productId }}"
                     data-item-id="{{ $itemId }}"
                     data-price="{{ $price }}">

                  <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Изображение товара (кликабельно) -->
                    <a href="{{ route('notebook.show', ['notebook' => $slug]) }}"
                       class="flex-shrink-0 w-32 h-32 bg-gray-50 rounded-lg overflow-hidden flex items-center justify-center hover:opacity-80 transition-opacity">
                      <img loading="lazy"
                           src="{{ notebook_image($slug) }}"
                           alt="{{ $title }}"
                           class="max-w-full max-h-full object-contain p-2">
                    </a>

                    <!-- Информация о товаре -->
                    <div class="flex-1 flex flex-col">
                      <div class="flex flex-col sm:flex-row sm:items-start justify-between">
                        <!-- Левая часть с названием и рейтингом (кликабельно) -->
                        <div class="flex-1 mb-4 sm:mb-0 sm:pr-4">
                          <a href="{{ route('notebook.show', ['notebook' => $slug]) }}"
                             class="hover:text-purple-600 transition-colors">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                              {{ $title }}
                            </h3>
                          </a>

                          <!-- Рейтинг -->
                          <div class="flex items-center mb-4">
                            @if($rating)
                              <div class="flex items-center text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                  <svg class="h-4 w-4 {{ $i <= round($rating) ? 'text-amber-400' : 'text-gray-300' }}"
                                       fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                  </svg>
                                @endfor
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                                        {{ number_format($rating, 1) }}
                                                    </span>
                              </div>
                            @else
                              <span class="text-sm text-gray-400">Нет оценок</span>
                            @endif
                            <span class="mx-2 text-gray-300">•</span>
                            <span class="text-sm text-green-600 font-medium flex items-center">
                                                <span
                                                  class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                                В наличии
                                            </span>
                          </div>

                          <!-- Цена за единицу (мобильная версия) -->
                          <div class="sm:hidden mb-3">
                            <span class="text-sm text-gray-500">Цена за шт:</span>
                            <span class="ml-2 text-base font-semibold text-gray-900">
                                                {{ number_format($price, 0, ',', ' ') }} ₽
                                            </span>
                          </div>
                        </div>

                        <!-- Правая часть с ценой (десктоп) -->
                        <div class="hidden sm:block text-right flex-shrink-0 min-w-[120px]">
                          <div class="text-sm text-gray-500 mb-1">Цена за шт:</div>
                          <div class="text-lg font-semibold text-gray-900">
                            {{ number_format($price, 0, ',', ' ') }} ₽
                          </div>
                        </div>
                      </div>

                      <!-- Нижняя часть с управлением (НЕ кликабельная) -->
                      <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-2 pt-2 border-t border-gray-100">

                        <!-- Счетчик товаров -->
                        <div class="flex items-center space-x-2">
                          <span class="text-sm text-gray-500 mr-2">Количество:</span>

                          <!-- Кнопка уменьшения -->
                          <button type="button"
                                  class="decrease-quantity w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-lg transition-colors border border-gray-300 bg-white disabled:opacity-50 disabled:cursor-not-allowed"
                                  data-product-id="{{ $productId }}"
                                  data-item-id="{{ $itemId }}"
                            {{ $count <= 1 ? 'disabled' : '' }}>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M20 12H4"/>
                            </svg>
                          </button>

                          <!-- Текущее количество -->
                          <span class="w-10 text-center font-medium text-gray-900 product-quantity">
                                            {{ $count }}
                                        </span>

                          <!-- Кнопка увеличения -->
                          <button type="button"
                                  class="increase-quantity w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-lg transition-colors border border-gray-300 bg-white"
                                  data-product-id="{{ $productId }}"
                                  data-item-id="{{ $itemId }}"
                            {{ $count >= 10 ? 'disabled' : '' }}>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                          </button>

                          <!-- Кнопка удаления -->
                          <button type="button"
                                  class="remove-from-cart ml-2 p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                  data-product-id="{{ $productId }}"
                                  data-item-id="{{ $itemId }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                          </button>
                        </div>

                        <!-- Общая стоимость -->
                        <div class="flex items-center justify-between sm:justify-end mt-3 sm:mt-0">
                          <span class="text-sm text-gray-500 sm:hidden">Итого:</span>
                          <span class="text-lg font-bold text-purple-600 product-total-price whitespace-nowrap">
                                            {{ number_format($totalPrice, 0, ',', ' ') }} ₽
                                        </span>
                          @if($count > 1)
                            <span class="text-xs text-gray-400 ml-2 sm:hidden">
                                                ({{ $count }} шт.)
                                            </span>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <!-- Пустая корзина -->
                <div class="empty-cart p-12 text-center">
                  <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor"
                       viewBox="0 0 24 24">
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
              @endforelse
            </div>
          </div>

          @if(count($cartProducts) > 0)
            <!-- Промокод -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Есть промокод?</h3>
              <div class="flex gap-3">
                <input type="text"
                       id="promo-code"
                       placeholder="Введите промокод"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                <button
                  class="apply-promo px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 whitespace-nowrap">
                  Применить
                </button>
              </div>
              <div id="promo-message" class="mt-2 text-sm hidden"></div>
            </div>
          @endif
        </div>

        <!-- Итоговая информация -->
        @if(count($cartProducts) > 0)
          <div class="lg:col-span-4 mt-8 lg:mt-0">
            <div class="bg-white rounded-2xl shadow-lg p-6 lg:sticky lg:top-6">
              <h3 class="text-xl font-bold text-gray-900 mb-6">Итог заказа</h3>

              <div class="space-y-4 mb-6">
                <div class="flex justify-between">
                                    <span class="text-gray-600">Товары (<span
                                        id="summary-total-items">{{ $totalItemsCount }}</span> шт.)</span>
                  <span class="font-medium" id="summary-items-price">{{ number_format($totalItemsPrice, 0, ',', ' ') }} ₽</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Доставка</span>
                  <span class="font-medium text-green-600">Бесплатно</span>
                </div>
                <div class="flex justify-between" id="discount-row" style="display: none;">
                  <span class="text-gray-600">Скидка</span>
                  <span class="font-medium text-red-600" id="discount-amount">-0 ₽</span>
                </div>
              </div>

              <div class="border-t border-gray-200 pt-4 mb-6">
                <div class="flex justify-between items-center">
                  <span class="text-lg font-semibold text-gray-900">Итого</span>
                  <span class="text-2xl font-bold text-purple-600" id="summary-total-price">
                            {{ number_format($totalItemsPrice, 0, ',', ' ') }} ₽
                        </span>
                </div>
                <p class="text-sm text-gray-500 mt-2">Включая НДС</p>
              </div>

              @if(auth()->check())
                <!-- Форма оформления заказа для авторизованных пользователей -->
                <a href="{{route('basket.order')}}"
                   class="block w-full py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-lg hover:shadow-xl mb-4 text-center">
                  Перейти к оформлению
                </a>
              @else
                <!-- Приглашение к авторизации для гостей -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                  <div class="flex items-start">
                    <svg class="h-5 w-5 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                      <h3 class="text-base font-semibold text-yellow-800 mb-1">Требуется
                        авторизация</h3>
                      <p class="text-yellow-700 text-sm mb-3">Для оформления заказа войдите или
                        зарегистрируйтесь</p>
                      <div class="flex flex-col gap-2">
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 text-center">
                          Войти
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 border border-purple-600 text-purple-600 text-sm font-medium rounded-lg hover:bg-purple-50 transition-colors duration-200 text-center">
                          Зарегистрироваться
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              @endif

              <a href="{{ route('products.index') }}"
                 class="block w-full py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 text-center mb-6">
                Продолжить покупки
              </a>

              <!-- Гарантии -->
              <div class="pt-6 border-t border-gray-200">
                <div class="space-y-3">
                  <div class="flex items-start">
                    <svg class="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-xs text-gray-600">Безопасная оплата</span>
                  </div>
                  <div class="flex items-start">
                    <svg class="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-xs text-gray-600">Гарантия возврата</span>
                  </div>
                  <div class="flex items-start">
                    <svg class="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-xs text-gray-600">Официальная гарантия</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif
      </div>

    </div>
  @endif
</div>

<script>
  window.cartConfig = {
    csrfToken: '{{ csrf_token() }}',
    isAuth: {{ auth()->check() ? 'true' : 'false' }},
    baseUrl: '/basket'
  };
</script>

