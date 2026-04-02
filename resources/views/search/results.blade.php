<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Онлайн-гипермаркет 21vek.by</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
@include('header.header')
<!-- Контейнер для товаров -->
<div class="min-h-screen bg-gray-50 py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @include('main.sidebar')
    <div id="products-container">
      @include('main.products-list', ['notebooks' => $notebooks])
    </div>
  </div>
</div>
<div id="cart-notification" class="fixed top-4 right-4 z-50 hidden">
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg animate-slideIn">
    <div class="flex items-center">
      <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
      </svg>
      <span id="notification-message"></span>
    </div>
  </div>
</div>

@include('footer.footer')

</body>
</html>



