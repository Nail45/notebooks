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
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tab-transition {
            transition: all 0.3s ease;
        }

        .form-container {
            min-height: 520px;
            position: relative;
            overflow: hidden;
        }

        .form-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            opacity: 0;
            transform: translateX(30px);
            pointer-events: none;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: opacity, transform;
        }

        .form-content.active {
            opacity: 1;
            transform: translateX(0);
            pointer-events: all;
            position: relative;
        }

        .form-content.inactive {
            opacity: 0;
            transform: translateX(-30px);
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-bg-gray min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white rounded-xl shadow-card overflow-hidden">
        <!-- Табы переключения -->
        <div class="flex border-b border-border-light bg-gradient-to-r from-brand/5 to-purple-50/50">
            <button id="login-tab"
                    class="tab-btn flex-1 py-4 font-medium text-center text-text-dark border-brand tab-transition relative">
                Вход
                <div
                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand transform scale-x-100 transition-transform duration-300"></div>
            </button>
            <button id="register-tab"
                    class="tab-btn flex-1 py-4 font-medium text-center text-text-gray border-b-2 border-transparent tab-transition relative">
                Регистрация
                <div
                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand transform scale-x-0 transition-transform duration-300"></div>
            </button>
        </div>

        <div class="form-container p-6 md:p-8">
            <!-- Форма входа -->
            <form id="login-form" method="POST" action="{{ route('login') }}" class="form-content active space-y-6">
                @csrf
                <!-- Email -->
                <div>
                    <label for="login-email" class="block text-sm font-medium text-text-dark mb-2">
                        Email
                    </label>
                    <input id="login-email"
                           type="email"
                           name="email"
                           required
                           autofocus
                           autocomplete="email"
                           placeholder="example@mail.com"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition">
                </div>

                <!-- Пароль -->
                <div>
                    <label for="login-password" class="block text-sm font-medium text-text-dark mb-2">
                        Пароль
                    </label>
                    <input id="login-password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Введите пароль"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition">
                </div>

                <!-- Запомнить меня -->
                <div class="flex items-center">
                    <input id="remember_me"
                           type="checkbox"
                           name="remember"
                           class="h-4 w-4 text-brand border-border-light rounded focus:ring-brand/40 form-transition">
                    <label for="remember_me" class="ml-2 text-sm text-text-gray form-transition">
                        Запомнить меня
                    </label>
                </div>

                <!-- Кнопки -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                    <div class="text-sm text-text-gray text-center sm:text-left">
                        Нет аккаунта?
                        <a href="#" id="register-link"
                           class="text-brand hover:text-brand-dark font-medium ml-1 transition-colors form-transition">
                            Зарегистрироваться
                        </a>

                        <span class="mx-2 hidden sm:inline">•</span>
                        <a href="#"
                           class="text-brand hover:text-brand-dark transition-colors block sm:inline mt-1 sm:mt-0 form-transition">
                            Забыли пароль?
                        </a>
                    </div>

                    <button type="submit"
                            class="px-6 py-3 bg-brand text-white font-medium rounded-lg hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2 transition-all duration-300 shadow-btn hover:shadow-md w-full sm:w-auto form-transition">
                        Войти
                    </button>
                </div>
            </form>

            <!-- Форма регистрации -->
            <form id="register-form" method="POST" action="{{ route('register') }}" class="form-content space-y-6">
                @csrf
                <!-- Имя -->
                <div>
                    <label for="name" class="block text-sm font-medium text-text-dark mb-2">
                        Имя
                    </label>
                    <input id="name"
                           type="text"
                           name="name"
                           required
                           autocomplete="name"
                           placeholder="Введите ваше имя"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition">
                </div>

                <!-- Email -->
                <div>
                    <label for="register-email" class="block text-sm font-medium text-text-dark mb-2">
                        Email
                    </label>
                    <input id="register-email"
                           type="email"
                           name="email"
                           required
                           autocomplete="email"
                           placeholder="example@mail.com"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition">
                </div>

                <!-- Пароль -->
                <div>
                    <label for="register-password" class="block text-sm font-medium text-text-dark mb-2">
                        Пароль
                    </label>
                    <input id="register-password"
                           type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="Не менее 8 символов"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition">
                </div>

                <!-- Подтверждение пароля -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-text-dark mb-2">
                        Подтверждение пароля
                    </label>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="Повторите пароль"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition">
                </div>

                <!-- Чекбоксы -->
                <div class="space-y-3">
                    <div class="flex items-start">
                        <input id="terms"
                               type="checkbox"
                               name="terms"
                               required
                               class="h-4 w-4 text-brand border-border-light rounded focus:ring-brand/40 mt-1 flex-shrink-0 form-transition">
                        <label for="terms" class="ml-2 text-sm text-text-gray form-transition">
                            Я соглашаюсь с <a href="#"
                                              class="text-brand hover:text-brand-dark transition-colors form-transition">Условиями</a>
                            и <a href="#" class="text-brand hover:text-brand-dark transition-colors form-transition">Политикой
                                конфиденциальности</a>
                        </label>
                    </div>
                    <div class="flex items-start">
                        <input id="newsletter"
                               type="checkbox"
                               name="newsletter"
                               class="h-4 w-4 text-brand border-border-light rounded focus:ring-brand/40 mt-1 flex-shrink-0 form-transition">
                        <label for="newsletter" class="ml-2 text-sm text-text-gray form-transition">
                            Получать информацию о скидках
                        </label>
                    </div>
                </div>

                <!-- Кнопки -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                    <div class="text-sm text-text-gray text-center sm:text-left">
                        Уже есть аккаунт?
                        <a href="#" id="login-link"
                           class="text-brand hover:text-brand-dark font-medium ml-1 transition-colors form-transition">
                            Войти
                        </a>
                    </div>

                    <button type="submit"
                            class="px-6 py-3 bg-brand text-white font-medium rounded-lg hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2 transition-all duration-300 shadow-btn hover:shadow-md w-full sm:w-auto form-transition">
                        Зарегистрироваться
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const loginLink = document.getElementById('login-link');
    const registerLink = document.getElementById('register-link');
    let isAnimating = false;

    function switchToLogin() {
        if (isAnimating) return;
        isAnimating = true;

        // Обновление табов
        loginTab.classList.add('text-text-dark');
        loginTab.classList.remove('text-text-gray');
        loginTab.querySelector('div').classList.add('scale-x-100');
        loginTab.querySelector('div').classList.remove('scale-x-0');

        registerTab.classList.add('text-text-gray');
        registerTab.classList.remove('text-text-dark');
        registerTab.querySelector('div').classList.add('scale-x-0');
        registerTab.querySelector('div').classList.remove('scale-x-100');

        // Анимация форм
        registerForm.classList.remove('active');
        registerForm.classList.add('inactive');

        setTimeout(() => {
            loginForm.classList.remove('inactive');
            loginForm.classList.add('active');
            isAnimating = false;
        }, 50);
    }

    function switchToRegister() {
        if (isAnimating) return;
        isAnimating = true;

        // Обновление табов
        registerTab.classList.add('text-text-dark');
        registerTab.classList.remove('text-text-gray');
        registerTab.querySelector('div').classList.add('scale-x-100');
        registerTab.querySelector('div').classList.remove('scale-x-0');

        loginTab.classList.add('text-text-gray');
        loginTab.classList.remove('text-text-dark');
        loginTab.querySelector('div').classList.add('scale-x-0');
        loginTab.querySelector('div').classList.remove('scale-x-100');

        // Анимация форм
        loginForm.classList.remove('active');
        loginForm.classList.add('inactive');

        setTimeout(() => {
            registerForm.classList.remove('inactive');
            registerForm.classList.add('active');
            isAnimating = false;
        }, 50);
    }

    // Обработчики для табов
    loginTab.addEventListener('click', switchToLogin);
    registerTab.addEventListener('click', switchToRegister);

    // Обработчики для ссылок в формах
    loginLink.addEventListener('click', (e) => {
        e.preventDefault();
        switchToLogin();
    });

    registerLink.addEventListener('click', (e) => {
        e.preventDefault();
        switchToRegister();
    });

    // Инициализация формы входа
    switchToLogin();
</script>
</body>
</html>


{{--<form method="POST" action="{{ route('login') }}" class="space-y-6">--}}
{{--    @csrf--}}

{{--    <!-- Email -->--}}
{{--    <div>--}}
{{--        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">--}}
{{--            Email--}}
{{--        </label>--}}
{{--        <input id="email"--}}
{{--               type="email"--}}
{{--               name="email"--}}
{{--               value="{{ old('email') }}"--}}
{{--               required--}}
{{--               autofocus--}}
{{--               autocomplete="email"--}}
{{--               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">--}}

{{--        @error('email')--}}
{{--        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>--}}
{{--        @enderror--}}
{{--    </div>--}}

{{--    <!-- Пароль -->--}}
{{--    <div>--}}
{{--        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">--}}
{{--            Пароль--}}
{{--        </label>--}}
{{--        <input id="password"--}}
{{--               type="password"--}}
{{--               name="password"--}}
{{--               required--}}
{{--               autocomplete="current-password"--}}
{{--               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">--}}

{{--        @error('password')--}}
{{--        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>--}}
{{--        @enderror--}}
{{--    </div>--}}

{{--    <!-- Запомнить меня -->--}}
{{--    <div class="flex items-center">--}}
{{--        <input id="remember_me"--}}
{{--               type="checkbox"--}}
{{--               name="remember"--}}
{{--               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">--}}
{{--        <label for="remember_me" class="ml-2 text-sm text-gray-700">--}}
{{--            Запомнить меня--}}
{{--        </label>--}}
{{--    </div>--}}

{{--    <!-- Кнопки -->--}}
{{--    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">--}}
{{--        <div class="text-sm text-gray-600">--}}
{{--            Нет аккаунта?--}}
{{--            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">--}}
{{--                Зарегистрироваться--}}
{{--            </a>--}}

{{--            @if (Route::has('password.request'))--}}
{{--                <span class="mx-2">•</span>--}}
{{--                <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-800">--}}
{{--                    Забыли пароль?--}}
{{--                </a>--}}
{{--            @endif--}}
{{--        </div>--}}

{{--        <button type="submit"--}}
{{--                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">--}}
{{--            Войти--}}
{{--        </button>--}}
{{--    </div>--}}
{{--</form>--}}
