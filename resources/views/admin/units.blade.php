<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('admin.properties.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-bold">&larr; Back to Portfolio</a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-1">
                    {{ $property->name }} - Units
                </h2>
            </div>
            <button type="button" onclick="document.getElementById('addUnitModal').classList.remove('hidden')" class="bg-blue-800 text-white px-6 py-2 rounded-md font-bold shadow-lg hover:bg-blue-700 transition transform hover:scale-105">
                + Add Unit
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-medium rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    @if(isset($units) && $units->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 font-medium">This property has no units yet.</p>
                            <p class="text-sm text-gray-400 mt-1">Click "+ Add Unit" to start dividing up the building.</p>
                        </div>
                    @elseif(isset($units))
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Rent</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deposit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($units as $unit)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ $unit->unit_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $unit->unit_type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">KES {{ number_format($unit->rent_amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">KES {{ number_format($unit->fixed_deposit, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ ucfirst($unit->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div id="addUnitModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('addUnitModal').classList.add('hidden')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="relative z-[110] inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('admin.units.store', $property->id) }}">
                        @csrf
                        <div class="bg-blue-800 px-4 py-4 text-center">
                            <h3 class="text-xl font-bold text-white tracking-widest">ADD NEW UNIT</h3>
                        </div>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Unit Name / Number</label>
                                <input type="text" name="unit_number" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="e.g. QW12" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Unit Type</label>
                                <select name="unit_type" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                                    <option value="Bedsitter">Bedsitter</option>
                                    <option value="1 Bedroom">1 Bedroom</option>
                                    <option value="2 Bedroom">2 Bedroom</option>
                                    <option value="3 Bedroom">3 Bedroom</option>
                                    <option value="Studio">Studio</option>
                                    <option value="Commercial Shop">Commercial Shop</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Monthly Rent (KES)</label>
                                <input type="number" name="rent_amount" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="e.g. 12000" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Security Deposit (KES)</label>
                                <input type="number" name="fixed_deposit" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="e.g. 12000" required>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md shadow-sm px-4 py-2 bg-blue-800 text-white font-bold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                                Save Unit
                            </button>
                            <button type="button" onclick="document.getElementById('addUnitModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 font-bold hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>