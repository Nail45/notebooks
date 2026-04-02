<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация | 21vek.by</title>
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
    </style>
</head>
<body class="bg-bg-gray min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white rounded-xl shadow-card overflow-hidden">
        <!-- Заголовок страницы -->
        <div class="text-center p-6 border-b border-border-light">
            <h1 class="text-2xl font-bold text-text-dark mb-2">Создание аккаунта</h1>
            <p class="text-text-gray text-sm">Заполните форму для регистрации</p>
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
                                Ошибка регистрации
                            </p>
                            @foreach($errors->all() as $error)
                                <p class="text-red-600 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Форма регистрации -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Имя -->
                <div>
                    <label for="name" class="block text-sm font-medium text-text-dark mb-2">
                        Имя
                    </label>
                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           autocomplete="name"
                           placeholder="Введите ваше имя"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition
                           @if($errors->has('name')) border-red-400 bg-red-50/50 @endif">
                    @if($errors->has('name'))
                        <div class="mt-1.5 flex items-center">
                            <svg class="h-4 w-4 text-red-500 mr-1" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs text-red-500">{{ $errors->first('name') }}</span>
                        </div>
                    @endif
                </div>

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
                           autocomplete="email"
                           placeholder="example@mail.com"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition
                           @if($errors->has('email')) border-red-400 bg-red-50/50 @endif">
                    @if($errors->has('email'))
                        <div class="mt-1.5 flex items-center">
                            <svg class="h-4 w-4 text-red-500 mr-1" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs text-red-500">{{ $errors->first('email') }}</span>
                        </div>
                    @endif
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
                           autocomplete="new-password"
                           placeholder="Не менее 8 символов"
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition
                           @if($errors->has('password')) border-red-400 bg-red-50/50 @endif">
                    @if($errors->has('password'))
                        <div class="mt-1.5 flex items-center">
                            <svg class="h-4 w-4 text-red-500 mr-1" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs text-red-500">{{ $errors->first('password') }}</span>
                        </div>
                    @endif
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
                           class="w-full px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition
                           @if($errors->has('password_confirmation')) border-red-400 bg-red-50/50 @endif">
                    @if($errors->has('password_confirmation'))
                        <div class="mt-1.5 flex items-center">
                            <svg class="h-4 w-4 text-red-500 mr-1" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs text-red-500">{{ $errors->first('password_confirmation') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Чекбоксы -->
                <div class="space-y-3">
                    <div class="flex items-start">
                        <input id="terms"
                               type="checkbox"
                               name="terms"
                               required
                               {{ old('terms') ? 'checked' : '' }}
                               class="h-4 w-4 text-brand border-border-light rounded focus:ring-brand/40 mt-1 flex-shrink-0 form-transition
                               @if($errors->has('terms')) border-red-400 @endif">
                        <label for="terms" class="ml-2 text-sm text-text-gray form-transition">
                            Я соглашаюсь с <a href="#" class="text-brand hover:text-brand-dark transition-colors form-transition">Условиями</a>
                            и <a href="#" class="text-brand hover:text-brand-dark transition-colors form-transition">Политикой конфиденциальности</a>
                        </label>
                    </div>

                    @if($errors->has('terms'))
                        <div class="ml-6 mt-1.5 flex items-center">
                            <svg class="h-4 w-4 text-red-500 mr-1" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs text-red-500">{{ $errors->first('terms') }}</span>
                        </div>
                    @endif

                    <div class="flex items-start">
                        <input id="newsletter"
                               type="checkbox"
                               name="newsletter"
                               {{ old('newsletter') ? 'checked' : '' }}
                               class="h-4 w-4 text-brand border-border-light rounded focus:ring-brand/40 mt-1 flex-shrink-0 form-transition">
                        <label for="newsletter" class="ml-2 text-sm text-text-gray form-transition">
                            Получать информацию о скидках
                        </label>
                    </div>
                </div>

                <!-- Кнопка регистрации -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full px-6 py-3 bg-brand text-white font-medium rounded-lg hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2 transition-all duration-300 shadow-btn hover:shadow-md form-transition">
                        Зарегистрироваться
                    </button>
                </div>

                <!-- Ссылка на вход -->
                <div class="text-center text-sm text-text-gray pt-4 border-t border-border-light">
                    <p>
                        Уже есть аккаунт?
                        <a href="{{ route('login') }}"
                           class="text-brand hover:text-brand-dark font-medium ml-1 transition-colors form-transition">
                            Войти
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
