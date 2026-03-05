<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Путевые листы') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('waybills.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                            + Выписать путевой лист
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Номер</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Водитель / ТС</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Маршрут</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действие</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($waybills as $waybill)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $waybill->number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $waybill->driver->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $waybill->vehicle->plate_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Str::limit($waybill->route, 20) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($waybill->status === 'issued')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    В рейсе
                                                </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Закрыт
                                                </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        @if($waybill->status === 'issued')
                                            <a href="{{ route('waybills.edit', $waybill->id) }}" class="text-indigo-600 hover:text-indigo-900">Завершить рейс</a>
                                        @else
                                            <a href="{{ route('waybills.pdf', $waybill->id) }}" class="text-blue-600 hover:text-blue-900 underline">Скачать PDF</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
