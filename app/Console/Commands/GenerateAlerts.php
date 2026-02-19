<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AlertService;

class GenerateAlerts extends Command
{
    protected $signature = 'app:generate-alerts';
    protected $description = 'Generate expiry and transfer alerts';

    public function handle(AlertService $alertService): int
    {
        $motherAlerts = $alertService->generateMotherExpiryAlerts();
        $planAlerts = $alertService->generatePlanExpiryAlerts();
        $transferAlerts = $alertService->generateTransferAlerts();

        $total = $motherAlerts + $planAlerts + $transferAlerts;
        $this->info("Generated {$total} alerts ({$motherAlerts} mother, {$planAlerts} plan, {$transferAlerts} transfer).");

        return Command::SUCCESS;
    }
}
