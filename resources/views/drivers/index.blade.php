<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Справочник: Персонал (Водители)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">

            <div class="w-full md:w-2/3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Список водителей</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ФИО</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Водительское удостоверение</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действие</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($drivers as $driver)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $driver->full_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $driver->license_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($driver->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Активен</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Отстранен</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('drivers.destroy', $driver) }}" method="POST" onsubmit="return confirm('Удалить профиль водителя?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/3 bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Добавить сотрудника</h3>

                    <form action="{{ route('drivers.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="full_name" value="ФИО Водителя" />
                            <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-input-label for="license_number" value="Серия и номер ВУ" />
                            <x-text-input id="license_number" name="license_number" type="text" class="mt-1 block w-full" required />
                        </div>

                        <div class="block mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" checked>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Сотрудник активен (допущен к рейсам)') }}</span>
                            </label>
                        </div>

                        <div class="pt-2">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Зарегистрировать') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
