<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Закрытие путевого листа № {{ $waybill->number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6 bg-blue-50 p-4 rounded border border-blue-200">
                        <p><strong>Маршрут:</strong> {{ $waybill->route }}</p>
                        <p><strong>Выезд:</strong> {{ $waybill->departure_time->format('d.m.Y H:i') }} | <strong>Одометр при выезде:</strong> {{ $waybill->start_km }} км</p>
                        <p><strong>Топливо при выезде:</strong> {{ $waybill->fuel_start }} л.</p>
                    </div>

                    <form action="{{ route('waybills.update', $waybill) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="return_time" value="Фактическое время возвращения" />
                                <x-text-input id="return_time" name="return_time" type="datetime-local" class="mt-1 block w-full" required />
                            </div>

                            <div>
                                <x-input-label for="end_km" value="Одометр при возвращении (км)" />
                                <x-text-input id="end_km" name="end_km" type="number" min="{{ $waybill->start_km }}" class="mt-1 block w-full" required />
                            </div>

                            <div class="p-4 bg-gray-50 rounded border border-gray-200 col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="fuel_added" value="Заправлено в пути (л) - если была заправка" />
                                    <x-text-input id="fuel_added" name="fuel_added" type="number" step="0.1" min="0" value="0" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="fuel_end" value="Фактический остаток топлива по возвращении (л)" />
                                    <x-text-input id="fuel_end" name="fuel_end" type="number" step="0.1" min="0" class="mt-1 block w-full border-green-500 focus:border-green-500 focus:ring-green-500" required />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="mechanic_name" value="ФИО Механика (осмотр ТС)" />
                                <x-text-input id="mechanic_name" name="mechanic_name" type="text" class="mt-1 block w-full" required />
                            </div>

                            <div>
                                <x-input-label for="medic_name" value="ФИО Медика (медосмотр)" />
                                <x-text-input id="medic_name" name="medic_name" type="text" class="mt-1 block w-full" required />
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded shadow transition">
                                Завершить рейс и сохранить данные
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
