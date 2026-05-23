<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Unit;
use App\Models\Invoice;
use Carbon\Carbon;

class GenerateMonthlyInvoices extends Command
{
    // The command you will type in the terminal to run this
    protected $signature = 'billing:generate-invoices';

    protected $description = 'Automatically generate monthly rent invoices for all occupied units';

    public function handle()
    {
        // 1. Find all occupied units that have a tenant assigned
        $occupiedUnits = Unit::where('status', 'occupied')->whereNotNull('tenant_id')->get();
        $currentMonth = Carbon::now()->format('F Y'); // e.g., "June 2026"

        $count = 0;

        foreach ($occupiedUnits as $unit) {
            // 2. firstOrCreate ensures we don't accidentally double-bill a tenant for the same month
            $invoice = Invoice::firstOrCreate([
                'unit_id' => $unit->id,
                'user_id' => $unit->tenant_id,
                'invoice_month' => $currentMonth,
            ], [
                'rent_amount' => $unit->monthly_rent,
                'total_due' => $unit->monthly_rent, // Utilities added later
                'due_date' => Carbon::now()->startOfMonth()->addDays(4), // Rent is due by the 5th
                'status' => 'unpaid'
            ]);

            if ($invoice->wasRecentlyCreated) {
                $count++;
            }
        }

        // 3. Output a success message to the server log
        $this->info("Successfully generated {$count} invoices for {$currentMonth}!");
    }
}