<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-medium rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-medium rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 flex justify-between items-center border-l-4 {{ $outstandingBalance > 0 ? 'border-red-500' : 'border-green-500' }}">
                    <div>
                        <h3 class="text-lg font-bold text-gray-700 uppercase tracking-wider">Current Balance</h3>
                        <p class="text-sm text-gray-500">Total outstanding arrears and unbilled utilities.</p>
                    </div>
                    <div class="text-right flex items-center gap-4">
                        <span class="text-4xl font-black {{ $outstandingBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
                            KES {{ number_format($outstandingBalance, 2) }}
                        </span>
                        @if($outstandingBalance > 0)
                            <button type="button" onclick="document.getElementById('paymentModal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow-lg transition transform hover:scale-105">
                                Pay Now
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Invoices</h3>
                        @if($recentInvoices->isEmpty())
                            <p class="text-gray-500 text-sm">No invoices found on your account.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($recentInvoices as $invoice)
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-gray-700">{{ $invoice->invoice_month }}</p>
                                            <p class="text-xs text-gray-500">Due: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900">KES {{ number_format($invoice->total_due, 2) }}</p>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Active Maintenance</h3>
                            <button type="button" onclick="document.getElementById('ticketModal').classList.remove('hidden')" class="text-sm bg-blue-600 text-white px-3 py-1 rounded shadow hover:bg-blue-700 transition">
                                + New Ticket
                            </button>
                        </div>
                        
                        @if($activeTickets->isEmpty())
                            <p class="text-gray-500 text-sm">No active maintenance requests.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($activeTickets as $ticket)
                                    <li class="py-3">
                                        <div class="flex justify-between">
                                            <p class="font-semibold text-gray-700">{{ $ticket->category }} Issue</p>
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $ticket->status }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1 truncate">{{ $ticket->description }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="ticketModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('ticketModal').classList.add('hidden')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="relative z-[110] inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('tenant.maintenance.store') }}">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Submit Maintenance Request</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-blue-500">
                                    <option>Plumbing</option><option>Electrical</option><option>Structural</option><option>Appliance</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Priority</label>
                                <select name="priority" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-blue-500">
                                    <option>Low</option><option selected>Medium</option><option>High</option><option>Emergency</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="3" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-blue-500" required></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Submit Ticket</button>
                            <button type="button" onclick="document.getElementById('ticketModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="paymentModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="document.getElementById('paymentModal').classList.add('hidden')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="relative z-[110] inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-green-600 px-4 py-4 text-center">
                        <h3 class="text-xl font-bold text-white tracking-widest">M-PESA PAYMENT</h3>
                    </div>
                    <form method="POST" action="{{ route('tenant.payment.process') }}">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <p class="text-sm text-gray-500 mb-4 text-center">Enter your phone number and the amount to receive an STK push prompt on your phone.</p>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">M-Pesa Phone Number</label>
                                <input type="text" name="phone_number" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-green-500" placeholder="e.g. 0712345678" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Amount to Pay (KES)</label>
                                <input type="number" name="amount" value="{{ $outstandingBalance > 0 ? $outstandingBalance : '' }}" class="mt-1 w-full rounded border-gray-300 shadow-sm focus:border-green-500 font-bold" required {{ $outstandingBalance <= 0 ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md shadow-sm px-4 py-2 bg-green-600 text-white font-bold hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Send Prompt
                            </button>
                            <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>