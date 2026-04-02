<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Оформление заказа | Интернет-магазин</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen py-8 px-4 sm:px-6 lg:px-8">

<div class="max-w-7xl mx-auto">
  <!-- Заголовок -->
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Оформление заказа</h1>
    <p class="mt-2 text-gray-600">Заполните форму, чтобы завершить покупку</p>
  </div>

  <div class="lg:grid lg:grid-cols-12 lg:gap-8">
    <!-- Форма заказа (левая колонка) -->
    <div class="lg:col-span-7">
      <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Контактная информация</h2>

        <form action="#" method="POST">
          <!-- Имя -->
          <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Ваше имя <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <input type="text"
                     id="name"
                     name="name"
                     required
                     minlength="2"
                     placeholder="Иван Иванов"
                     class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
            </div>
            <p class="mt-1 text-xs text-gray-500">Введите ваше полное имя</p>
          </div>

          <!-- Телефон -->
          <div class="mb-6">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
              Номер телефона <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <input type="tel"
                     id="phone"
                     name="phone"
                     required
                     placeholder="+7 (999) 123-45-67"
                     class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
            </div>
            <p class="mt-1 text-xs text-gray-500">В формате +7 (XXX) XXX-XX-XX</p>
          </div>
        </form>
      </div>
    </div>

    <!-- Информация о заказе (правая колонка) -->
    <div class="lg:col-span-5 mt-8 lg:mt-0">
      <div class="bg-white rounded-2xl shadow-lg p-6 lg:sticky lg:top-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Ваш заказ</h3>

        @foreach($notebooks as $notebook)
          <!-- Список товаров -->
          <div class="space-y-4 mb-6 max-h-80 overflow-y-auto">
            <!-- Товар 1 -->
            <div class="flex gap-3 pb-3 border-b border-gray-100">
              <div
                class="flex-shrink-0 w-16 h-16 bg-gray-50 rounded-lg overflow-hidden flex items-center justify-center">
                <img loading="lazy"
                     src="{{ notebook_image($notebook->slug) }}"
                     alt="{{ $notebook->title }}"
                     class="max-w-full max-h-full object-contain p-2">
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-900 truncate">{{$notebook->title}}</h4>
                <div class="flex justify-between items-center mt-1">
                  <span class="text-xs text-gray-500">{{$notebook->count}} шт. × {{$notebook->price}} ₽</span>
                  <span class="text-sm font-semibold text-purple-600"> {{$notebook->total_price}} ₽</span>
                </div>
              </div>
            </div>

          </div>

        @endforeach

        <!-- Итого -->
        <div class="space-y-3 pt-4 border-t border-gray-200">
          <div class="flex justify-between">
            <span class="text-gray-600">Товары ({{ $totalItemsCount }} шт.)</span>
            <span class="font-medium">{{ number_format($totalAllPrice, 0, ',', ' ') }} ₽</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Доставка</span>
            <span class="font-medium text-green-600">Бесплатно</span>
          </div>
          <div class="flex justify-between pt-3 border-t border-gray-200">
            <span class="text-lg font-semibold text-gray-900">Итого к оплате</span>
            <span class="text-2xl font-bold text-purple-600">{{ number_format($totalAllPrice, 0, ',', ' ') }} ₽</span>
          </div>
        </div>

        <!-- Кнопка заказа -->
        <button type="submit"
                class="w-full mt-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-lg hover:shadow-xl">
          Заказать
        </button>

        <a href="{{route('basket.index')}}"
           class="block w-full mt-3 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 text-center">
          Вернуться в корзину
        </a>

        <!-- Гарантии -->
        <div class="pt-6 mt-6 border-t border-gray-200">
          <div class="space-y-3">
            <div class="flex items-start">
              <svg class="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              <span class="text-xs text-gray-600">Безопасная оплата</span>
            </div>
            <div class="flex items-start">
              <svg class="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              <span class="text-xs text-gray-600">Гарантия возврата</span>
            </div>
            <div class="flex items-start">
              <svg class="h-4 w-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              <span class="text-xs text-gray-600">Официальная гарантия</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<dialog id="successDialog"
        class="rounded-2xl shadow-2xl backdrop:bg-black/50 w-full max-w-md p-0 open:flex open:flex-col">
  <div class="p-6 text-center">
    <!-- Иконка успеха -->
    <div class="flex justify-center mb-4">
      <div class="h-20 w-20 rounded-full bg-green-100 flex items-center justify-center">
        <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-900 mb-3">Заказ успешно оформлен!</h2>
    <p class="text-gray-600 mb-4">
      Спасибо за ваш заказ!<br>
      <span class="text-sm text-yellow-600 block mt-2">
                ⚠️ Товар
		успешно заказан, но он к вам не приедет,
		так как сайт учебный
            </span>
    </p>

    <div class="bg-purple-50 rounded-xl p-4 mb-6">
      <p class="text-purple-800 text-sm mb-3">
        Хотите сделать реальный заказ?
      </p>
      <a href="https://21vek.by"
         target="_blank"
         rel="noopener noreferrer"
         class="inline-flex items-center justify-center w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300">
        Перейти на 21vek.by
        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
      </a>
    </div>

    <div class="flex gap-3">
      <form method="dialog" class="flex-1">
        <button
          class="w-full px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
          Закрыть
        </button>
      </form>
      <a href="{{route('products.index')}}"
         class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 text-center">
        В каталог
      </a>
    </div>
  </div>
</dialog>

<!-- Добавьте этот HTML в конец body -->
<div id="errorToast" class="fixed top-5 right-5 z-50 hidden">
  <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-bounce-slow">
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span id="errorMessage">Пожалуйста, заполните все обязательные поля</span>
  </div>
</div>




</body>
</html>
