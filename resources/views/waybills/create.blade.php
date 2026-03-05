<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Выдача нового путевого листа (Этап 1)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('waybills.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="driver_id" value="Водитель" />
                                    <select name="driver_id" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->full_name }} (ВУ: {{ $driver->license_number }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="vehicle_id" value="Транспортное средство" />
                                    <select name="vehicle_id" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->model }} ({{ $vehicle->plate_number }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="departure_time" value="Дата и время выезда" />
                                    <x-text-input id="departure_time" name="departure_time" type="datetime-local" class="mt-1 block w-full" required />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="route" value="Маршрут следования" />
                                    <x-text-input id="route" name="route" type="text" placeholder="г. Москва - г. Тверь" class="mt-1 block w-full" required />
                                </div>

                                <div>
                                    <x-input-label for="cargo_info" value="Наименование груза (необязательно)" />
                                    <x-text-input id="cargo_info" name="cargo_info" type="text" placeholder="Стройматериалы" class="mt-1 block w-full" />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="start_km" value="Одометр при выезде (км)" />
                                        <x-text-input id="start_km" name="start_km" type="number" class="mt-1 block w-full" required />
                                    </div>
                                    <div>
                                        <x-input-label for="fuel_start" value="Топливо в баке (л)" />
                                        <x-text-input id="fuel_start" name="fuel_start" type="number" step="0.1" class="mt-1 block w-full" required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 flex justify-end">
                            <x-primary-button>
                                {{ __('Выписать путевой лист') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
