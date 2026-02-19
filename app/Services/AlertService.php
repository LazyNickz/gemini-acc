<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Account;
use App\Models\MotherAccount;

class AlertService
{
    /**
     * Generate alerts for mothers expiring in 2 or 1 days.
     */
    public function generateMotherExpiryAlerts(): int
    {
        $count = 0;
        $mothers = MotherAccount::active()->get();

        foreach ($mothers as $mother) {
            $days = $mother->days_until_expiry;

            if ($days === 2) {
                $count += $this->createAlertIfNotExists(
                    $mother,
                    'mother_expiry',
                    'warning',
                    "Mother account {$mother->email} expires in 2 days"
                );
            } elseif ($days === 1) {
                $count += $this->createAlertIfNotExists(
                    $mother,
                    'mother_expiry',
                    'critical',
                    "Mother account {$mother->email} expires tomorrow!"
                );
            }
        }

        return $count;
    }

    /**
     * Generate alerts for account plans expiring in 2 or 1 days.
     */
    public function generatePlanExpiryAlerts(): int
    {
        $count = 0;
        $accounts = Account::whereIn('status', ['active', 'unassigned'])->get();

        foreach ($accounts as $account) {
            $days = $account->plan_days_remaining;

            if ($days === 2) {
                $count += $this->createAlertIfNotExists(
                    $account,
                    'plan_expiry',
                    'warning',
                    "Account {$account->email} plan expires in 2 days"
                );
            } elseif ($days === 1) {
                $count += $this->createAlertIfNotExists(
                    $account,
                    'plan_expiry',
                    'critical',
                    "Account {$account->email} plan expires tomorrow!"
                );
            }
        }

        return $count;
    }

    /**
     * Generate alerts for accounts needing transfer.
     */
    public function generateTransferAlerts(): int
    {
        $count = 0;
        $accounts = Account::unassigned()->get();

        foreach ($accounts as $account) {
            $count += $this->createAlertIfNotExists(
                $account,
                'transfer_needed',
                'critical',
                "Account {$account->email} needs to be transferred to a new mother account"
            );
        }

        return $count;
    }

    /**
     * Resolve all alerts for a given entity.
     */
    public function resolveAlertsFor($entity): void
    {
        Alert::where('alertable_type', get_class($entity))
            ->where('alertable_id', $entity->id)
            ->unresolved()
            ->update([
                'resolved' => true,
                'resolved_at' => now(),
            ]);
    }

    /**
     * Create alert only if a matching unresolved alert doesn't already exist.
     */
    private function createAlertIfNotExists($entity, string $type, string $severity, string $message): int
    {
        $exists = Alert::where('alertable_type', get_class($entity))
            ->where('alertable_id', $entity->id)
            ->where('type', $type)
            ->where('severity', $severity)
            ->unresolved()
            ->exists();

        if (!$exists) {
            Alert::create([
                'type' => $type,
                'alertable_type' => get_class($entity),
                'alertable_id' => $entity->id,
                'message' => $message,
                'severity' => $severity,
            ]);
            return 1;
        }

        return 0;
    }
}
