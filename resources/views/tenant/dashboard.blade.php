<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($unit)
                <!-- Active Lease Information -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-8 border-blue-800 mb-8">
                    <div class="p-6 sm:p-10 bg-white">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight">My Active Lease</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 font-bold rounded-full text-sm">Active</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Property Info -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Property</p>
                                <p class="font-black text-xl text-blue-900">{{ $unit->property->name }}</p>
                                <p class="text-gray-600 mt-1">{{ $unit->property->location }}</p>
                            </div>
                            
                            <!-- Unit Info -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Unit Details</p>
                                <p class="font-black text-xl text-gray-900">{{ $unit->unit_number }}</p>
                                <p class="text-gray-600 mt-1">{{ $unit->unit_type }}</p>
                            </div>
                            
                            <!-- Rent -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Monthly Rent</p>
                                <p class="font-black text-3xl text-green-600">KES {{ number_format($unit->rent_amount, 2) }}</p>
                            </div>
                            
                            <!-- Deposit -->
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Security Deposit</p>
                                <p class="font-black text-2xl text-gray-700">KES {{ number_format($unit->fixed_deposit, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Unit Assigned Yet -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-400 mb-8">
                    <div class="p-6 bg-white text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-yellow-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">Account Under Review</h3>
                        <p class="text-gray-500 mt-2 max-w-md mx-auto">You have successfully registered, but property management has not assigned your account to a specific unit yet.</p>
                    </div>
                </div>
            @endif
            
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

            @php
    $currentBalance = 0;
    if(isset($invoices)) {
        // This hunts down all unpaid or partially paid invoices and subtracts what they already paid
        $currentBalance = $invoices->whereIn('status', ['unpaid', 'partial'])->sum(function($invoice) {
            return $invoice->total_due - $invoice->amount_paid;
        });
    }
@endphp

@if($currentBalance > 0)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-8 border-red-600 mb-8 flex justify-between items-center p-6 sm:p-8">
        <div>
            <h3 class="text-xl font-black text-gray-900 tracking-widest uppercase">Current Balance</h3>
            <p class="text-sm text-gray-500 mt-1">Total outstanding arrears and unbilled utilities.</p>
        </div>
        <div class="flex items-center space-x-6">
            <span class="font-black text-4xl text-red-600">KES {{ number_format($currentBalance, 2) }}</span>
            
            <button type="button" onclick="document.getElementById('mpesaModal').classList.remove('hidden')" class="bg-green-600 text-white px-8 py-3 rounded-md font-black shadow-lg hover:bg-green-500 transition transform hover:scale-105">
                Pay Now
            </button>
        </div>
    </div>
@else
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-8 border-green-500 mb-8 flex justify-between items-center p-6 sm:p-8">
        <div>
            <h3 class="text-xl font-black text-gray-900 tracking-widest uppercase">Account Cleared</h3>
            <p class="text-sm text-gray-500 mt-1">You have no outstanding arrears. Thank you for paying on time!</p>
        </div>
        <div class="flex items-center space-x-6">
            <span class="font-black text-4xl text-green-600">KES 0.00</span>
        </div>
    </div>
@endif

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

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-black text-gray-900 tracking-widest uppercase">Active Maintenance</h3>
        <button type="button" onclick="document.getElementById('ticketModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-md font-bold shadow hover:bg-blue-500 transition text-sm">
            + New Ticket
        </button>
    </div>
    <div class="p-0">
        @if(isset($tickets) && $tickets->count() > 0)
            <ul class="divide-y divide-gray-100">
                @foreach($tickets as $ticket)
                    <li class="p-6 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $ticket->subject }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $ticket->description }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ strtoupper($ticket->status) }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="p-6 text-center text-gray-500 text-sm">
                No active maintenance requests.
            </div>
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
    <div id="mpesaModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity" onclick="document.getElementById('mpesaModal').classList.add('hidden')"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="relative z-[110] inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border-t-8 border-green-500">
            <form method="POST" action="{{ route('tenant.payment.process') }}">
                @csrf
                
                @if(isset($invoices) && $invoices->where('status', 'unpaid')->first())
                    <input type="hidden" name="invoice_id" value="{{ $invoices->where('status', 'unpaid')->first()->id }}">
                @endif

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900">Lipa na M-Pesa</h3>
                    <p class="text-sm text-gray-500 mt-2">Enter your phone number to receive the STK push prompt.</p>
                    
                    <div class="mt-6 text-left">
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">M-Pesa Phone Number</label>
                            <input type="text" name="phone_number" class="w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 font-bold" placeholder="07XX XXX XXX" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Amount to Pay (KES)</label>
                            <input type="number" name="amount" value="{{ isset($currentBalance) ? $currentBalance : 0 }}" class="w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 font-bold text-green-600" required>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between space-x-3 border-t border-gray-200">
                    <button type="button" onclick="document.getElementById('mpesaModal').classList.add('hidden')" class="w-1/2 rounded-md border border-gray-300 shadow-sm px-4 py-3 bg-white text-gray-700 font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="w-1/2 rounded-md shadow-sm px-4 py-3 bg-green-600 text-white font-bold hover:bg-green-700 transition">
                        Send STK Push
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="ticketModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity" onclick="document.getElementById('ticketModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="relative z-[110] inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-8 border-blue-600">
            <form method="POST" action="{{ route('tenant.maintenance.store') }}">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Report an Issue</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Issue Subject</label>
                        <input type="text" name="subject" class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="e.g., Leaking kitchen pipe" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Detailed Description</label>
                        <textarea name="description" rows="4" class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Please describe the issue in detail so maintenance can bring the right tools..." required></textarea>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between space-x-3 border-t border-gray-200">
                    <button type="button" onclick="document.getElementById('ticketModal').classList.add('hidden')" class="w-1/2 rounded-md border border-gray-300 shadow-sm px-4 py-3 bg-white text-gray-700 font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="w-1/2 rounded-md shadow-sm px-4 py-3 bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>