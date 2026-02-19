<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;

class CheckPlanExpiry extends Command
{
    protected $signature = 'app:check-plan-expiry';
    protected $description = 'Check for expired account plans and move them to cooldown';

    public function handle(): int
    {
        $expired = Account::whereIn('status', ['active', 'unassigned'])
            ->where('plan_expiry_date', '<=', now()->toDateString())
            ->get();

        $this->info("Found {$expired->count()} accounts with expired plans.");

        foreach ($expired as $account) {
            $account->update(['status' => 'cooldown']);
            $this->line("  - {$account->email} moved to cooldown");
        }

        return Command::SUCCESS;
    }
}
