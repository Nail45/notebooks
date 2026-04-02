<section class="space-y-6">
    <header class="mb-8">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div
                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Удаление аккаунта
                </h2>
                <p class="mt-2 text-base text-gray-600 leading-relaxed">
                    После удаления аккаунта все данные и ресурсы будут удалены безвозвратно.
                    Перед удалением рекомендуем сохранить важную информацию.
                </p>
            </div>
        </div>
    </header>

    <div class="p-6 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-start">
            <svg class="h-6 w-6 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.332 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-red-800">Внимание: необратимое действие</h3>
                <p class="mt-1 text-red-700">
                    Удаление аккаунта приведет к безвозвратной потере всех данных, включая историю, настройки и
                    персональную информацию.
                    Это действие нельзя отменить.
                </p>
                <div class="mt-4">
                    <x-danger-button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="px-6 py-3 text-base font-medium rounded-lg bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-sm hover:shadow-md"
                    >
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Удалить аккаунт
                    </x-danger-button>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-6">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.332 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Подтвердите удаление аккаунта
                    </h2>
                    <p class="mt-1 text-base text-gray-600">
                        Вы уверены, что хотите удалить свой аккаунт?
                    </p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                @csrf
                @method('delete')

                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-700 font-medium">
                        ⚠️ После удаления аккаунта все данные будут безвозвратно утеряны.
                    </p>
                    <p class="mt-2 text-sm text-red-600">
                        Все ваши файлы, настройки, история и другая информация будут удалены.
                        Это действие необратимо.
                    </p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <x-input-label for="password" value="Пароль для подтверждения"
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
                            id="password"
                            name="password"
                            type="password"
                            class="pl-10 mt-1 block w-full border-red-300 focus:border-red-500 focus:ring-red-500 rounded-lg transition duration-200 ease-in-out"
                            placeholder="Введите ваш пароль для подтверждения"
                        />
                    </div>
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-sm"/>
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <x-secondary-button
                            x-on:click="$dispatch('close')"
                            class="px-6 py-3 text-base font-medium rounded-lg border-gray-300 hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-200 ease-in-out"
                        >
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Отменить
                        </x-secondary-button>

                        <x-danger-button
                            class="px-6 py-3 text-base font-medium rounded-lg bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-sm hover:shadow-md ml-3">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Удалить аккаунт
                        </x-danger-button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</section>
