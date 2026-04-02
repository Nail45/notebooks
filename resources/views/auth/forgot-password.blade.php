<x-guest-layout>
    @section('title', 'Восстановление пароля')
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-card overflow-hidden">
            <!-- Заголовок страницы -->
            <div class="text-center p-6 border-b border-border-light bg-gradient-to-r from-brand/5 to-purple-50/50">
                <div class="flex items-center justify-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-text-dark mb-2">Восстановление пароля</h1>
                <p class="text-text-gray text-sm">
                    Введите ваш email, и мы отправим вам ссылку для сброса пароля
                </p>
            </div>

            <div class="p-6 md:p-8">
                <!-- Уведомления -->
                @if(session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg animate-fadeIn">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <p class="text-green-800 font-medium text-sm">
                                    {{ session('status') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.332 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <p class="text-red-800 font-medium text-sm mb-1">
                                    Ошибка
                                </p>
                                @foreach($errors->all() as $error)
                                    <p class="text-red-600 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Форма восстановления пароля -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="email" class="block text-sm font-medium text-text-dark">
                                Email
                            </label>
                            <span class="text-xs text-gray-500">Обязательно</span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   placeholder="example@mail.com"
                                   class="w-full pl-10 px-4 py-3 border border-border-light rounded-lg focus:ring-2 focus:ring-brand/40 focus:border-brand transition-colors placeholder:text-gray-400 form-transition
                                   @if($errors->has('email')) border-red-400 bg-red-50/50 @endif">
                        </div>
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

                    <!-- Кнопка отправки -->
                    <div class="pt-4">
                        <button type="submit"
                                class="w-full px-6 py-3 bg-gradient-to-r from-brand to-brand-dark text-white font-medium rounded-lg hover:from-brand-light hover:to-brand focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2 transition-all duration-300 shadow-btn hover:shadow-md form-transition flex items-center justify-center">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Отправить ссылку для сброса
                        </button>
                    </div>

                    <!-- Ссылка на возврат -->
                    <div class="text-center pt-6 border-t border-border-light">
                        <a href="{{ route('login') }}"
                           class="text-sm text-brand hover:text-brand-dark font-medium transition-colors form-transition inline-flex items-center">
                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Вернуться к входу
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-transition {
            transition: all 0.2s ease-in-out;
        }

        .tab-transition {
            transition: all 0.3s ease;
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-guest-layout>
