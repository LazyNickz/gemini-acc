<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MotherAccount;
use App\Services\TransferService;

class CheckMotherExpiry extends Command
{
    protected $signature = 'app:check-mother-expiry';
    protected $description = 'Check for expired mother accounts and process their attached accounts';

    public function handle(TransferService $transferService): int
    {
        $expiredMothers = MotherAccount::active()
            ->where('expiry_date', '<=', now()->toDateString())
            ->get();

        $this->info("Found {$expiredMothers->count()} expired mother accounts.");

        foreach ($expiredMothers as $mother) {
            $results = $transferService->handleMotherExpiry($mother);
            $this->line("  - {$mother->email}: " .
                count($results['unassigned']) . ' unassigned, ' .
                count($results['cooldown']) . ' cooldown');
        }

        return Command::SUCCESS;
    }
}
