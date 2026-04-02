<section>
    <header class="mb-8">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div
                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Информация профиля
                </h2>
                <p class="mt-2 text-base text-gray-600 leading-relaxed">
                    Обновите информацию вашего профиля и адрес электронной почты.
                </p>
            </div>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div class="space-y-6">
            <!-- Поле имени -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <x-input-label for="name" value="Имя" class="text-sm font-semibold text-gray-700"/>
                    <span class="text-xs text-gray-500">Обязательно</span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <x-text-input
                        id="name"
                        name="name"
                        type="text"
                        class="pl-10 mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg transition duration-200 ease-in-out"
                        :value="old('name', $user->name)"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Введите ваше полное имя"
                    />
                </div>
                <x-input-error class="mt-2 text-sm" :messages="$errors->get('name')"/>
            </div>

            <!-- Поле email -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <x-input-label for="email" value="Электронная почта" class="text-sm font-semibold text-gray-700"/>
                    <span class="text-xs text-gray-500">Обязательно</span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <x-text-input
                        id="email"
                        name="email"
                        type="email"
                        class="pl-10 mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg transition duration-200 ease-in-out"
                        :value="old('email', $user->email)"
                        required
                        autocomplete="username"
                        placeholder="ваш.email@example.com"
                    />
                </div>
                <x-input-error class="mt-2 text-sm" :messages="$errors->get('email')"/>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-amber-500 mt-0.5 mr-3 flex-shrink-0" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.332 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800">
                                    Ваш адрес электронной почты не подтвержден.
                                </p>
                                <p class="mt-1 text-sm text-amber-700">
                                    Пожалуйста, подтвердите ваш email для доступа ко всем функциям.
                                </p>
                                <button
                                    form="send-verification"
                                    class="mt-2 inline-flex items-center px-3 py-1.5 border border-amber-300 text-sm font-medium rounded-md text-amber-700 bg-amber-100 hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-amber-500 transition duration-200 ease-in-out"
                                >
                                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Отправить письмо подтверждения
                                </button>
                            </div>
                        </div>

                        @if (session('status') === 'verification-link-sent')
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <p class="text-sm font-medium text-green-800">
                                        Новое письмо с подтверждением отправлено на ваш email.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <x-primary-button
                        class="px-6 py-3 text-base font-medium rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-sm hover:shadow-md">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Сохранить изменения
                    </x-primary-button>

                    @if (session('status') === 'profile-updated')
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
                            <span class="text-sm font-medium">Профиль успешно обновлен!</span>
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
