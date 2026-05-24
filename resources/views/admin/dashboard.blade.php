<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Command Center') }}
            </h2>
            <a href="{{ route('admin.properties.index') }}" class="bg-blue-800 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700 transition transform hover:scale-105">
                Manage Portfolio &rarr;
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Properties</p>
                    <p class="mt-2 text-3xl font-black text-gray-900">{{ $stats['total_properties'] }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Units</p>
                    <p class="mt-2 text-3xl font-black text-gray-900">{{ $stats['total_units'] }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Tenants</p>
                    <p class="mt-2 text-3xl font-black text-gray-900">{{ $stats['active_tenants'] }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Unpaid Revenue</p>
                    <p class="mt-2 text-2xl font-black text-red-600">KES {{ number_format($stats['outstanding_revenue'], 2) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">System Management</h3>
                    <p class="text-gray-500 text-sm">Property mapping and tenant assignment modules will be managed through the property tab.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>