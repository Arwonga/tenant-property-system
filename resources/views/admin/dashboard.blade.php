<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-wider uppercase">
            {{ __('Admin Command Center') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
                
                <div class="bg-white rounded-xl shadow-sm border-b-4 border-green-500 p-6 transform transition hover:scale-105">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Total Revenue</p>
                    <h3 class="text-3xl font-black text-green-600">KES {{ number_format($totalRevenue, 2) }}</h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-red-600 p-6 transform transition hover:scale-105">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Total Arrears</p>
                    <h3 class="text-3xl font-black text-red-600">KES {{ number_format($totalArrears, 2) }}</h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-blue-600 p-6 transform transition hover:scale-105">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Properties</p>
                    <h3 class="text-3xl font-black text-blue-900">{{ $totalProperties }}</h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-indigo-500 p-6 transform transition hover:scale-105">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Occupied</p>
                    <h3 class="text-3xl font-black text-indigo-600">{{ $occupiedUnits }} <span class="text-sm text-gray-400">/ {{ $totalUnits }}</span></h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-orange-400 p-6 transform transition hover:scale-105">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Vacant</p>
                    <h3 class="text-3xl font-black text-orange-500">{{ $vacantUnits }}</h3>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-800">
                    <h3 class="text-lg font-black text-white tracking-widest uppercase">Recent Cash Flow</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tenant</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Property / Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentPayments as $payment)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                        {{ $payment->tenant->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-blue-900">{{ $payment->unit->property->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">Unit {{ $payment->unit->unit_number ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-black text-green-600">
                                        KES {{ number_format($payment->amount_paid, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->updated_at->format('M d, Y - h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 font-medium">
                                        No payments have been received yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>