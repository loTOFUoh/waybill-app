<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Справочник: Автопарк') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">

            <div class="w-full md:w-2/3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Текущий автопарк</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Марка</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Гос. Номер</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Топливо</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Норма</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действие</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vehicles as $vehicle)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $vehicle->model }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono bg-gray-100 rounded px-2">{{ $vehicle->plate_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $vehicle->fuel_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $vehicle->base_consumption }} л.</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить транспорт?');">
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
                    <h3 class="text-lg font-bold mb-4">Регистрация ТС</h3>

                    <form action="{{ route('vehicles.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="model" value="Марка ТС" />
                            <x-text-input id="model" name="model" type="text" class="mt-1 block w-full" required placeholder="КАМАЗ 5490" />
                        </div>

                        <div>
                            <x-input-label for="plate_number" value="Государственный номер" />
                            <x-text-input id="plate_number" name="plate_number" type="text" class="mt-1 block w-full uppercase" required placeholder="А123АА 123" />
                        </div>

                        <div>
                            <x-input-label for="fuel_type" value="Тип топлива" />
                            <select id="fuel_type" name="fuel_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                <option value="Дизель">Дизель</option>
                                <option value="Бензин АИ-92">Бензин АИ-92</option>
                                <option value="Бензин АИ-95">Бензин АИ-95</option>
                                <option value="Газ (ГБО)">Газ (ГБО)</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="base_consumption" value="Базовая норма (л/100км)" />
                            <x-text-input id="base_consumption" name="base_consumption" type="number" step="0.1" class="mt-1 block w-full" required />
                        </div>

                        <div class="pt-2">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Добавить транспорт') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
