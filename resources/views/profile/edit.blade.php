<x-app-layout>
    @section('title', 'Личный кабинет')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto space-y-8">
            <!-- Заголовок страницы -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Настройки профиля</h1>
                <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
                    Управляйте информацией вашего аккаунта, безопасностью и настройками
                </p>
            </div>

            <!-- Карточки с плавной анимацией -->
            <div class="space-y-8">
                <!-- Карточка 1: Основная информация -->
                <div
                        class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center mb-6">
                            <div
                                    class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-blue-100 text-blue-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold text-gray-900">Основная информация</h2>
                                <p class="text-gray-500 text-sm">Обновите данные вашего профиля и контакты</p>
                            </div>
                        </div>
                        <div class="max-w-2xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Карточка 2: Безопасность -->
                <div
                        class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center mb-6">
                            <div
                                    class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-green-100 text-green-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold text-gray-900">Безопасность</h2>
                                <p class="text-gray-500 text-sm">Измените пароль для защиты аккаунта</p>
                            </div>
                        </div>
                        <div class="max-w-2xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- Карточка 3: Опасная зона -->
                <div
                        class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border border-red-100">
                    <div class="p-6 sm:p-8">
                        <div class="max-w-2xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Информационная панель -->
            <div class="mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                <div class="flex flex-col sm:flex-row items-center">
                    <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-6">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-lg font-semibold text-gray-900">Нужна помощь?</h3>
                        <p class="text-gray-600 mt-1">
                            Если у вас возникли проблемы с настройкой профиля, свяжитесь с нашей службой поддержки.
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-auto">
                        <button
                                class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                            Связаться с поддержкой
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('footer.footer')
</x-app-layout>
