<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Купить ноутбук {{$notebook['title']}}</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
@include('header.header')

<div class="container mx-auto px-4 py-8">
  {{-- Хлебные крошки --}}
  <nav class="flex mb-8 text-sm text-gray-500">
    <a href="/" class="hover:text-pink-600 transition">Главная</a>
    <span class="mx-2">/</span>
    <a href="/search/{{ $notebook->manufacturer }}" class="hover:text-pink-600 transition">
      {{ $notebook->manufacturer }}
    </a>
    <span class="mx-2">/</span>
    <span class="text-gray-900">{{ $notebook->title }}</span>
  </nav>
  <!-- Уведомления -->
  @if(session('success'))
    <div
      class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
      <span>{{ session('success') }}</span>
      <button onclick="this.parentElement.style.display='none'" class="ml-4 text-green-700 hover:text-green-900">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  @endif

  @if(session('error'))
    <div
      class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
      <span>{{ session('error') }}</span>
      <button onclick="this.parentElement.style.display='none'" class="ml-4 text-red-700 hover:text-red-900">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  @endif
  {{-- Основной контент --}}
  <div class="relative">
    {{-- Верхний блок с изображением и основной информацией --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
      {{-- Левая колонка - Слайдер (фиксированный) --}}
      @php
        $imageFolder = public_path('storage/images/' . $notebook->slug);
        $images = [];

        if (is_dir($imageFolder)) {
            $files = scandir($imageFolder);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;

                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $images[] = asset('storage/images/' . $notebook->slug . '/' . $file);
                }
            }
        }

        if (empty($images)) {
            $images[] = asset('storage/images/default.jpg');
        }
      @endphp
      <div class="lg:sticky lg:top-24 lg:self-start"
           x-data="productGallery()"
           data-images='@json($images)'>

        <div class="space-y-4">
          {{-- Основной слайдер --}}
          <div class="relative aspect-square bg-gradient-to-br from-gray-50 to-white rounded-2xl
                    border border-gray-100 overflow-hidden group"
               x-ref="mainSlider">

            {{-- Слайды --}}
            <div class="relative h-full">
              <template x-for="(image, index) in images" :key="index">
                <div x-show="currentSlide === index"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 flex items-center justify-center p-8">
                  <img :src="image"
                       :alt="'Фото ' + (index + 1)"
                       class="max-h-full max-w-full object-contain transform group-hover:scale-110
                                    transition-transform duration-500"
                       loading="lazy">
                </div>
              </template>
            </div>

            {{-- Кнопка "Назад" --}}
            <button @click="prevSlide"
                    x-show="images.length > 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10
                                       bg-white/90 backdrop-blur-sm rounded-full shadow-lg
                                       flex items-center justify-center text-gray-700
                                       hover:bg-white hover:text-pink-600 transition-all
                                       opacity-0 group-hover:opacity-100">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
              </svg>
            </button>

            {{-- Кнопка "Вперед" --}}
            <button @click="nextSlide"
                    x-show="images.length > 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10
                                       bg-white/90 backdrop-blur-sm rounded-full shadow-lg
                                       flex items-center justify-center text-gray-700
                                       hover:bg-white hover:text-pink-600 transition-all
                                       opacity-0 group-hover:opacity-100">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5l7 7-7 7"/>
              </svg>
            </button>

            {{-- Индикатор текущего слайда --}}
            <div x-show="images.length > 1"
                 class="absolute bottom-4 left-1/2 -translate-x-1/2
                                    bg-black/50 backdrop-blur-sm text-white text-xs
                                    px-3 py-1.5 rounded-full">
              <span x-text="currentSlide + 1"></span> / <span x-text="images.length"></span>
            </div>
          </div>

          {{-- Миниатюры (превью) --}}
          <div x-show="images.length > 1"
               class="flex space-x-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300"
               x-ref="thumbnails">
            <template x-for="(image, index) in images" :key="index">
              <button @click="currentSlide = index"
                      :class="{ 'border-pink-500 ring-2 ring-pink-200': currentSlide === index,
                                              'border-gray-200 hover:border-pink-300': currentSlide !== index }"
                      class="flex-shrink-0 w-20 h-20 rounded-lg border-2 overflow-hidden
                                           transition-all duration-200">
                <img :src="image"
                     :alt="'Превью ' + (index + 1)"
                     class="w-full h-full object-cover">
              </button>
            </template>
          </div>
        </div>
      </div>

      {{-- Правая колонка - Название, цена, кнопка --}}
      <div class="space-y-6">
        {{-- Производитель --}}
        <span class="inline-block text-xs font-semibold text-pink-600 uppercase tracking-wider
                             bg-pink-50 px-3 py-1 rounded-full">
                    {{ $notebook->manufacturer }}
                </span>

        {{-- Название товара --}}
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">
          {{ $notebook->title }}
        </h1>

        {{-- Рейтинг --}}
        <div class="flex items-center space-x-3">
          <div class="flex items-center">
            @if($notebook->rating && $notebook->rating > 0)
              <div class="relative">
                <svg class="w-6 h-6 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <div class="absolute top-0 left-0 overflow-hidden"
                     style="width: {{ ($notebook->rating / 5) * 100 }}%">
                  <svg class="w-6 h-6" fill="url(#gradient)" viewBox="0 0 20 20">
                    <defs>
                      <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#fbbf24;stop-opacity:1"/>
                        <stop offset="100%" style="stop-color:#f59e0b;stop-opacity:1"/>
                      </linearGradient>
                    </defs>
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                </div>
              </div>
              <span class="ml-2 text-sm font-bold text-gray-900">
                                {{ number_format($notebook->rating, 1) }}
                            </span>
            @else
              <div class="flex items-center text-gray-400">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span class="text-sm">Нет оценок</span>
              </div>
            @endif
          </div>
          <span
            class="text-xs text-gray-500">({{  $notebook->feedbacks ? $notebook->feedbacks->count() : 0 }} отзывов)</span>
        </div>

        {{-- Цена и кнопка добавления в корзину --}}
        <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-6 space-y-4">
          <div class="flex items-baseline justify-between">
            <div>
              @if($notebook->discount > 0)
                <div class="flex items-center space-x-3">
                  <div class="text-3xl lg:text-4xl font-bold text-gray-900">
                    {{ number_format($notebook->price - ($notebook->price * $notebook->discount / 100), 0, '.', ' ') }}
                    ₽
                  </div>
                  <div class="text-lg text-gray-500 line-through">
                    {{ number_format($notebook->price, 0, '.', ' ') }} ₽
                  </div>
                  <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        -{{ $notebook->discount }}%
                                    </span>
                </div>
              @else
                <div class="text-3xl lg:text-4xl font-bold text-gray-900">
                  {{ number_format($notebook->price, 0, '.', ' ') }} ₽
                </div>
              @endif
            </div>

            {{-- Наличие --}}
            <span class="text-green-600 text-sm font-medium flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                В наличии
                            </span>
          </div>

          <!-- Кнопка добавления в корзину -->
          <button type="button"
                  id="cart-btn-{{ $notebook->id }}"
                  class="px-4 py-3 w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white text-xs font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center justify-center group">
            <svg class="h-3.5 w-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            В корзину
          </button>

          <!-- Уведомление о добавлении в корзину -->
          <div id="cart-notification"
               class="hidden fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slideIn">
            Товар добавлен в корзину
          </div>
        </div>
      </div>
    </div>

    {{-- КОМПАКТНЫЕ ХАРАКТЕРИСТИКИ --}}
    <div class="mt-12 lg:mt-16">
      <div class="bg-white rounded-xl border border-gray-100 p-6 lg:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Характеристики</h2>

        {{-- Компактное отображение характеристик в виде двух колонок с группировкой по категориям --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">

          {{-- Основные характеристики --}}
          @php
            $mainSpecs = [
              'Производитель' => $notebook->manufacturer,
              'Линейка' => $notebook->line,
              'Модель' => $notebook->model,
              'Год выпуска' => $notebook->release_date,
              'Операционная система' => $notebook->operating_system,
              'Тип' => $notebook->view_title,
              'Сертификация' => $notebook->certification,
            ];
          @endphp

          @foreach($mainSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Экран --}}
          @php
            $screenSpecs = [
              'Диагональ' => $notebook->screen_diagonal ? $notebook->screen_diagonal . '"' : null,
              'Разрешение' => $notebook->screen_resolution,
              'Технология' => $notebook->screen_technology,
              'Частота обновления' => $notebook->refresh_rate,
              'Сенсорный экран' => $notebook->touch_screen,
            ];
          @endphp

          @foreach($screenSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Процессор --}}
          @php
            $cpuSpecs = [
              'Процессор' => $notebook->processor_model,
              'Количество ядер' => $notebook->cores_count,
              'Тактовая частота' => $notebook->clock_speed,
              'Турбо-частота' => $notebook->turbo_frequency,
            ];
          @endphp

          @foreach($cpuSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Оперативная память --}}
          @php
            $ramSpecs = [
              'Оперативная память' => $notebook->ram_capacity ? $notebook->ram_capacity : null,
              'Тип памяти' => $notebook->ram_type,
              'Макс. объем' => $notebook->max_ram_capacity ? $notebook->max_ram_capacity : null,
            ];
          @endphp

          @foreach($ramSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Видеокарта --}}
          @php
            $gpuSpecs = [
              'Видеокарта' => $notebook->gpu_model,
              'Видеопамять' => $notebook->gpu_memory,
            ];
          @endphp

          @foreach($gpuSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Накопители --}}
          @php
            $storageSpecs = [
              'SSD' => $notebook->ssd_capacity,
              'HDD' => $notebook->hdd_capacity,
              'Картридер' => $notebook->sd_card_slot ? 'Есть' : null,
            ];
          @endphp

          @foreach($storageSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Порты --}}
          @php
            $portSpecs = [
              'USB-A' => $notebook->usb_a_ports,
              'USB-C' => $notebook->usb_c_ports,
              'HDMI' => $notebook->hdmi_port,
              'DisplayPort' => $notebook->displayport,
            ];
          @endphp

          @foreach($portSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Коммуникации --}}
          @php
            $commsSpecs = [
              'Wi-Fi' => $notebook->wifi,
              'Bluetooth' => $notebook->bluetooth,
              'Ethernet' => $notebook->ethernet_lan,
            ];
          @endphp

          @foreach($commsSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Клавиатура --}}
          @php
            $keyboardSpecs = [
              'Подсветка' => $notebook->keyboard_backlight,
              'Цифровой блок' => $notebook->numeric_keypad,
            ];
          @endphp

          @foreach($keyboardSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach

          {{-- Аккумулятор и вес --}}
          @php
            $miscSpecs = [
              'Емкость аккумулятора' => $notebook->energy_reserve,
              'Вес' => $notebook->weight ? $notebook->weight : null,
            ];
          @endphp

          @foreach($miscSpecs as $label => $value)
            @if($value)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">{{ $label }}</span>
                <span class="text-gray-900 font-medium text-right">{{ $value }}</span>
              </div>
            @endif
          @endforeach
        </div>

        {{-- Ссылка на полные характеристики (если нужно показать все) --}}
        <div class="mt-4 text-center">
          <button id="show-all-specs"
                  class="text-pink-600 text-sm font-medium hover:text-pink-700 transition">
            Показать все характеристики
          </button>
        </div>

        {{-- Полные характеристики (скрыты по умолчанию) --}}
        <div id="full-specs" class="hidden mt-6 pt-4 border-t border-gray-200">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-2">
            @if($notebook->manufacturer)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Производитель</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->manufacturer }}</span>
              </div>
            @endif

            @if($notebook->line)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Линейка</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->line }}</span>
              </div>
            @endif

            @if($notebook->model)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Модель</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->model }}</span>
              </div>
            @endif

            @if($notebook->release_date)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Год выпуска</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->release_date }}</span>
              </div>
            @endif

            @if($notebook->operating_system)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Операционная система</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->operating_system }}</span>
              </div>
            @endif

            @if($notebook->view_title)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Тип</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->view_title }}</span>
              </div>
            @endif

            @if($notebook->certification)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Сертификация</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->certification }}</span>
              </div>
            @endif

            @if($notebook->platform_code_name)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Кодовое имя платформы</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->platform_code_name }}</span>
              </div>
            @endif

            @if($notebook->packaging)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Комплектация</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->packaging }}</span>
              </div>
            @endif

            @if($notebook->screen_diagonal)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Диагональ</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->screen_diagonal }}"</span>
              </div>
            @endif

            @if($notebook->screen_aspect_ratio)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Соотношение сторон</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->screen_aspect_ratio }}</span>
              </div>
            @endif

            @if($notebook->screen_resolution)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Разрешение</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->screen_resolution }}</span>
              </div>
            @endif

            @if($notebook->screen_technology)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Технология</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->screen_technology }}</span>
              </div>
            @endif

            @if($notebook->refresh_rate)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Частота обновления</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->refresh_rate }}</span>
              </div>
            @endif

            @if($notebook->screen_surface)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Поверхность экрана</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->screen_surface }}</span>
              </div>
            @endif

            @if($notebook->brightness)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Яркость</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->brightness }}</span>
              </div>
            @endif

            @if($notebook->touch_screen)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Сенсорный экран</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->touch_screen }}</span>
              </div>
            @endif

            @if($notebook->processor_series)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Серия процессора</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->processor_series }}</span>
              </div>
            @endif

            @if($notebook->processor_model)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Модель процессора</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->processor_model }}</span>
              </div>
            @endif

            @if($notebook->cores_count)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Количество ядер</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->cores_count }}</span>
              </div>
            @endif

            @if($notebook->threads_count)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Количество потоков</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->threads_count }}</span>
              </div>
            @endif

            @if($notebook->clock_speed)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Тактовая частота</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->clock_speed }}</span>
              </div>
            @endif

            @if($notebook->turbo_frequency)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Частота в турбо-режиме</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->turbo_frequency }}</span>
              </div>
            @endif

            @if($notebook->cache_size)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Объем кэша</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->cache_size }}</span>
              </div>
            @endif

            @if($notebook->processor_tdp)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">TDP процессора</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->processor_tdp }}</span>
              </div>
            @endif

            @if($notebook->ram_type)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Тип памяти</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->ram_type }}</span>
              </div>
            @endif

            @if($notebook->ram_capacity)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Объем памяти</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->ram_capacity }}</span>
              </div>
            @endif

            @if($notebook->ram_frequency)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Частота памяти</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->ram_frequency }}</span>
              </div>
            @endif

            @if($notebook->max_ram_capacity)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Макс. объем памяти</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->max_ram_capacity }}</span>
              </div>
            @endif

            @if($notebook->memory_slots)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Количество слотов</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->memory_slots }}</span>
              </div>
            @endif

            @if($notebook->gpu_type)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Тип видеокарты</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->gpu_type }}</span>
              </div>
            @endif

            @if($notebook->gpu_model)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Модель видеокарты</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->gpu_model }}</span>
              </div>
            @endif

            @if($notebook->gpu_memory)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Видеопамять</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->gpu_memory }}</span>
              </div>
            @endif

            @if($notebook->storage_config)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Конфигурация накопителей</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->storage_config }}</span>
              </div>
            @endif

            @if($notebook->ssd_capacity)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">SSD накопитель</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->ssd_capacity }}</span>
              </div>
            @endif

            @if($notebook->ssd_interface)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Интерфейс SSD</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->ssd_interface }}</span>
              </div>
            @endif

            @if($notebook->hdd_capacity)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">HDD накопитель</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->hdd_capacity }}</span>
              </div>
            @endif

            @if($notebook->sd_card_slot)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Картридер</span>
                <span class="text-gray-900 font-medium text-right">Есть</span>
              </div>
            @endif

            @if($notebook->optical_drive)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Оптический привод</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->optical_drive }}</span>
              </div>
            @endif

            @if($notebook->usb_a_ports)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">USB-A</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->usb_a_ports }}</span>
              </div>
            @endif

            @if($notebook->usb_c_ports)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">USB-C</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->usb_c_ports }}</span>
              </div>
            @endif

            @if($notebook->total_usb_ports)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Всего USB</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->total_usb_ports }}</span>
              </div>
            @endif

            @if($notebook->hdmi_port)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">HDMI</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->hdmi_port }}</span>
              </div>
            @endif

            @if($notebook->displayport)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">DisplayPort</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->displayport }}</span>
              </div>
            @endif

            @if($notebook->vga_port)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">VGA</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->vga_port }}</span>
              </div>
            @endif

            @if($notebook->audio_jack)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Аудио разъем</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->audio_jack }}</span>
              </div>
            @endif

            @if($notebook->thunderbolt)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Thunderbolt</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->thunderbolt }}</span>
              </div>
            @endif

            @if($notebook->ethernet_lan)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Ethernet LAN</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->ethernet_lan }}</span>
              </div>
            @endif

            @if($notebook->wifi)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Wi-Fi</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->wifi }}</span>
              </div>
            @endif

            @if($notebook->bluetooth)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Bluetooth</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->bluetooth }}</span>
              </div>
            @endif

            @if($notebook->keyboard_backlight)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Подсветка клавиатуры</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->keyboard_backlight }}</span>
              </div>
            @endif

            @if($notebook->island_keyboard)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Островная клавиатура</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->island_keyboard }}</span>
              </div>
            @endif

            @if($notebook->numeric_keypad)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Цифровой блок</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->numeric_keypad }}</span>
              </div>
            @endif

            @if($notebook->cyrillic_on_keyboard)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Кириллица на клавиатуре</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->cyrillic_on_keyboard }}</span>
              </div>
            @endif

            @if($notebook->speakers_count)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Количество динамиков</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->speakers_count }}</span>
              </div>
            @endif

            @if($notebook->microphone)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Микрофон</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->microphone }}</span>
              </div>
            @endif

            @if($notebook->built_in_camera)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Встроенная камера</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->built_in_camera }}</span>
              </div>
            @endif

            @if($notebook->camera_pixels)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Разрешение камеры</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->camera_pixels }}</span>
              </div>
            @endif

            @if($notebook->energy_reserve)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Емкость аккумулятора</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->energy_reserve }}</span>
              </div>
            @endif

            @if($notebook->width)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Ширина</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->width }} мм</span>
              </div>
            @endif

            @if($notebook->depth)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Глубина</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->depth }}</span>
              </div>
            @endif

            @if($notebook->thickness)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Толщина</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->thickness }} мм</span>
              </div>
            @endif

            @if($notebook->weight)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Вес</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->weight }}</span>
              </div>
            @endif

            @if($notebook->case_material)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Материал корпуса</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->case_material }}</span>
              </div>
            @endif

            @if($notebook->case_color)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Цвет корпуса</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->case_color }}</span>
              </div>
            @endif

            @if($notebook->case_surface)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Поверхность корпуса</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->case_surface }}</span>
              </div>
            @endif

            @if($notebook->lid_material)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Материал крышки</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->lid_material }}</span>
              </div>
            @endif

            @if($notebook->lid_color)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Цвет крышки</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->lid_color }}</span>
              </div>
            @endif

            @if($notebook->lid_surface)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Поверхность крышки</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->lid_surface }}</span>
              </div>
            @endif

            @if($notebook->transformer)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Трансформер</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->transformer }}</span>
              </div>
            @endif

            @if($notebook->trackpad)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Тачпад</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->trackpad }}</span>
              </div>
            @endif

            @if($notebook->joystick_touchstick)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Джойстик</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->joystick_touchstick }}</span>
              </div>
            @endif

            @if($notebook->fingerprint_scanner)
              <div class="flex justify-between py-1 border-b border-gray-50 text-sm">
                <span class="text-gray-600">Сканер отпечатков</span>
                <span class="text-gray-900 font-medium text-right">{{ $notebook->fingerprint_scanner }}</span>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- ОТЗЫВЫ С ПАГИНАЦИЕЙ --}}
    <div class="mt-12 lg:mt-16">
      <div class="bg-white rounded-xl border border-gray-100 p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
          <h2 class="text-xl font-bold text-gray-900">Отзывы</h2>
          <span class="text-sm text-gray-500">{{ $feedbacks->count() }} отзывов</span>
        </div>

        {{-- Список отзывов с пагинацией --}}
        <div id="feedbacks-list" class="space-y-6">
          @forelse($feedbacks as $feedback)
            <div class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
              <div class="flex items-start justify-between mb-3">
                <div>
                  <span class="font-semibold text-gray-900">{{ $feedback->author ?? $feedback->user['name'] }}</span>
                  <span
                    class="text-sm text-gray-500 ml-2">{{ \Carbon\Carbon::parse($feedback->date)->format('d.m.Y') }}</span>
                </div>
                @if($feedback->rating)
                  <div class="flex items-center">
                    <div class="flex items-center">
                      @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                             fill="currentColor" viewBox="0 0 20 20">
                          <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                      @endfor
                    </div>
                  </div>
                @endif
              </div>

              @if($feedback->advantage)
                <div class="mb-2">
                  <span class="text-sm font-medium text-green-600">Достоинства:</span>
                  <p class="text-sm text-gray-700 mt-1">{{ $feedback->advantage }}</p>
                </div>
              @endif

              @if($feedback->disadvantages)
                <div class="mb-2">
                  <span class="text-sm font-medium text-red-600">Недостатки:</span>
                  <p class="text-sm text-gray-700 mt-1">{{ $feedback->disadvantages }}</p>
                </div>
              @endif

              @if($feedback->summary)
                <div class="mt-2 pt-2 border-t border-gray-100">
                  <span class="text-sm font-medium text-gray-700">Комментарий:</span>
                  <p class="text-gray-700 mt-1">{{ $feedback->summary }}</p>
                </div>
              @endif
            </div>
          @empty
            <p class="text-center text-gray-500 py-8">Пока нет отзывов. Будьте первым!</p>
          @endforelse
        </div>

        {{-- Пагинация --}}
        @if($feedbacks->hasPages())
          <div class="mt-8">
            {{ $feedbacks->links() }}
          </div>
        @endif
      </div>

      {{-- Форма добавления отзыва --}}
      @auth
        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
          <h3 class="text-lg font-semibold text-gray-900 mb-3">Оставить отзыв</h3>
          <form x-data="{ rating: {{ old('rating', 0) }} }"
                id="feedback-form" class="space-y-4" method="POST"
                action="{{route('notebook.addFeedbacks')}}">
            @csrf
            <input type="hidden" name="notebook_id" value="{{ $notebook->id }}">

            <!-- Убираем x-data из этого div -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Оценка <span class="text-red-500">*</span>
              </label>

              <div class="flex items-center space-x-1">
                <template x-for="star in 5" :key="star">
                  <button type="button" @click="rating = star" class="focus:outline-none">
                    <svg class="w-6 h-6"
                         :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'"
                         fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                  </button>
                </template>
                <input type="hidden" name="rating" x-model="rating" required>
                <span class="ml-2 text-sm" :class="rating ? 'text-gray-600' : 'text-red-500'"
                      x-text="rating ? rating + ' из 5' : 'Обязательно для выбора'"></span>
              </div>
              @error('rating')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="advantage"
                     class="block text-sm font-medium text-gray-700 mb-1">Достоинства</label>
              <textarea id="advantage" name="advantage" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500"></textarea>
            </div>

            <div>
              <label for="disadvantages"
                     class="block text-sm font-medium text-gray-700 mb-1">Недостатки</label>
              <textarea id="disadvantages" name="disadvantages" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500"></textarea>
            </div>

            <div>
              <label for="summary"
                     class="block text-sm font-medium text-gray-700 mb-1">Комментарий</label>
              <textarea id="summary" name="summary" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500"></textarea>
            </div>

            <button type="submit"
                    :disabled="!rating"
                    class="px-4 py-2 bg-pink-600 text-white text-sm font-medium rounded-lg transition-colors"
                    :class="!rating ? 'opacity-50 cursor-not-allowed bg-pink-400' : 'hover:bg-pink-700'">
              Отправить отзыв
            </button>
          </form>
        </div>
      @else
        <div class="mb-8 p-4 bg-gray-50 rounded-lg text-center">
          <p class="text-gray-600">Чтобы оставить отзыв, <a href="{{ route('login') }}"
                                                            class="text-pink-600 hover:text-pink-700 font-medium">войдите</a>
            или <a href="{{ route('register') }}" class="text-pink-600 hover:text-pink-700 font-medium">зарегистрируйтесь</a>
          </p>
        </div>
      @endauth
    </div>
  </div>
</div>

@include('footer.footer')

</body>
</html>
