<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация | 21vek.by</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand': '#672f9e',
                        'brand-light': '#7e48b5',
                        'brand-dark': '#562885',
                        'bg-gray': '#f8f9fa',
                        'text-dark': '#2d3748',
                        'text-gray': '#718096',
                        'border-light': '#e2e8f0',
                        'error': '#e53e3e'
                    },
                    boxShadow: {
                        'card': '0 4px 12px rgba(103, 47, 158, 0.08)',
                        'btn': '0 2px 8px rgba(103, 47, 158, 0.3)'
                    }
                }
            }
        }
    </script>
    <style>
        .form-transition {
            transition: all 0.2s ease-in-out;
        }

        .tab-transition {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-bg-gray min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white rounded-xl shadow-card overflow-hidden">
        <!-- Заголовок страницы -->
        <div class="text-center p-6 border-b border-border-light">
            <h1 class="text-2xl font-bold text-text-dark mb-2">Вход в аккаунт</h1>
            <p class="text-text-gray text-sm">Введите свои данные для входа</p>
        </div>

        <div class="p-6 md:p-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.332 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <p class="text-red-800 font-medium text-sm mb-1">
                                Ошибка авторизации
                            </p>
                            <p class="text-red-600 text-sm">
                                Неверный email или пароль
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Форма входа -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-text-dark mb-2">
                        Email
                    </label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="email"
                           placeholder="example@mail.com"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition">
                </div>

                <!-- Пароль -->
                <div>
                    <label for="password" class="block text-sm font-medium text-text-dark mb-2">
                        Пароль
                    </label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Введите пароль"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition">
                </div>

                <!-- Запомнить меня -->
                <div class="flex items-center">
                    <input id="remember"
                           type="checkbox"
                           name="remember"
                           {{ old('remember') ? 'checked' : '' }}
                           class="h-4 w-4 text-brand border-border-light rounded focus:ring-brand/40 form-transition">
                    <label for="remember" class="ml-2 text-sm text-text-gray form-transition">
                        Запомнить меня
                    </label>
                </div>

                <!-- Кнопка входа -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full px-6 py-3 bg-brand text-white font-medium rounded-lg hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2 transition-all duration-300 shadow-btn hover:shadow-md form-transition">
                        Войти
                    </button>
                </div>

                <!-- Ссылки -->
                <div class="text-center text-sm text-text-gray pt-4 border-t border-border-light">
                    <p class="mb-2">
                        Нет аккаунта?
                        <a href="{{ route('register') }}"
                           class="text-brand hover:text-brand-dark font-medium ml-1 transition-colors form-transition">
                            Зарегистрироваться
                        </a>
                    </p>
                    <p>
                        <a href="{{ route('password.request') }}"
                           class="text-brand hover:text-brand-dark transition-colors form-transition">
                            Забыли пароль?
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
