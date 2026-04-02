<section>
    <header class="mb-8">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div
                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Обновление пароля
                </h2>
                <p class="mt-2 text-base text-gray-600 leading-relaxed">
                    Используйте длинный, надежный пароль для защиты вашей учетной записи.
                </p>
            </div>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
        @csrf
        @method('put')

        <div class="space-y-6">
            <!-- Текущий пароль -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <x-input-label for="update_password_current_password" value="Текущий пароль"
                                   class="text-sm font-semibold text-gray-700"/>
                    <span class="text-xs text-gray-500">Обязательно</span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <x-text-input
                        id="update_password_current_password"
                        name="current_password"
                        type="password"
                        class="pl-10 mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg transition duration-200 ease-in-out"
                        autocomplete="current-password"
                        placeholder="Введите текущий пароль"
                    />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm"/>
            </div>

            <!-- Новый пароль -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <x-input-label for="update_password_password" value="Новый пароль"
                                   class="text-sm font-semibold text-gray-700"/>
                    <span class="text-xs text-gray-500">Обязательно</span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <x-text-input
                        id="update_password_password"
                        name="password"
                        type="password"
                        class="pl-10 mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg transition duration-200 ease-in-out"
                        autocomplete="new-password"
                        placeholder="Введите новый пароль"
                    />
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Пароль должен содержать минимум 8 символов, включая заглавные и строчные буквы, цифры.
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm"/>
            </div>

            <!-- Подтверждение пароля -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <x-input-label for="update_password_password_confirmation" value="Подтверждение пароля"
                                   class="text-sm font-semibold text-gray-700"/>
                    <span class="text-xs text-gray-500">Обязательно</span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <x-text-input
                        id="update_password_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="pl-10 mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg transition duration-200 ease-in-out"
                        autocomplete="new-password"
                        placeholder="Повторите новый пароль"
                    />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm"/>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <x-primary-button
                        class="px-6 py-3 text-base font-medium rounded-lg bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-sm hover:shadow-md">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Сохранить пароль
                    </x-primary-button>

                    @if (session('status') === 'password-updated')
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            x-init="setTimeout(() => show = false, 3000)"
                            class="flex items-center space-x-2 px-4 py-2.5 bg-green-50 border border-green-200 text-green-700 rounded-lg"
                        >
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm font-medium">Пароль успешно обновлен!</span>
                        </div>
                    @endif
                </div>

                <button
                    type="reset"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
                >
                    Сбросить
                </button>
            </div>
        </div>
    </form>
</section>
