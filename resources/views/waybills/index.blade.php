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
                        <a href="{{ route('waybills.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Новый лист
                        </a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Номер</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Водитель</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ТС</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пройдено (км)</th>
                            @if(Auth::user()->role === 'admin')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Диспетчер</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действие</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($waybills as $waybill)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $waybill->number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $waybill->driver->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $waybill->vehicle->plate_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $waybill->end_km - $waybill->start_km }}</td>
                                @if(Auth::user()->role === 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $waybill->user->name }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('waybills.pdf', $waybill->id) }}" class="text-indigo-600 hover:text-indigo-900">Скачать PDF</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
