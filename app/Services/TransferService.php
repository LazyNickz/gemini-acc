<?php

namespace App\Services;

use App\Models\Account;
use App\Models\MotherAccount;
use App\Models\ActivityLog;
use Illuminate\Support\Collection;

class TransferService
{
    /**
     * Handle mother account expiry — process all attached accounts.
     */
    public function handleMotherExpiry(MotherAccount $mother): array
    {
        $results = ['unassigned' => [], 'cooldown' => []];

        $accounts = $mother->accounts()->whereIn('status', ['active'])->get();

        foreach ($accounts as $account) {
            if ($account->plan_days_remaining > 0) {
                // Case A: account has remaining plan days
                $this->unassignAccount($account);
                $results['unassigned'][] = $account;
            } else {
                // Case B: plan expired — goes to cooldown
                $account->update(['status' => 'cooldown', 'mother_account_id' => null]);
                ActivityLog::log('account_cooldown', "Account {$account->email} moved to cooldown (plan expired)", $account);
                $results['cooldown'][] = $account;
            }
        }

        // Archive the mother
        $mother->update(['status' => 'expired']);
        ActivityLog::log('mother_expired', "Mother account {$mother->email} expired and accounts processed", $mother);

        return $results;
    }

    /**
     * Unassign account from mother (Case A).
     */
    public function unassignAccount(Account $account): void
    {
        $account->update([
            'status' => 'unassigned',
            'mother_account_id' => null,
            'assigned_at' => null,
        ]);

        ActivityLog::log('account_unassigned', "Account {$account->email} unassigned — awaiting transfer", $account);
    }

    /**
     * Suggest available mothers sorted by longest remaining days.
     */
    public function suggestMothers(Account $account): Collection
    {
        return MotherAccount::withAvailableCapacity()
            ->orderByLongestRemaining()
            ->get()
            ->map(function ($mother) {
                $mother->suggestion_score = $mother->days_until_expiry;
                return $mother;
            });
    }

    /**
     * Reassign account to a new mother.
     */
    public function reassignAccount(Account $account, MotherAccount $mother): bool
    {
        // Validate capacity
        if ($mother->seats_remaining <= 0) {
            return false;
        }

        // Validate mother is active
        if ($mother->status !== 'active') {
            return false;
        }

        // Check email uniqueness on this mother
        $emailExists = Account::where('email', $account->email)
            ->where('mother_account_id', $mother->id)
            ->where('id', '!=', $account->id)
            ->where('status', 'active')
            ->exists();

        if ($emailExists) {
            return false;
        }

        $account->update([
            'mother_account_id' => $mother->id,
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        ActivityLog::log('account_transferred', "Account {$account->email} transferred to mother {$mother->email}", $account, [
            'mother_account_id' => $mother->id,
        ]);

        return true;
    }

    /**
     * Extend plan for an account (buyer paid).
     */
    public function extendPlan(Account $account, int $days): void
    {
        $newExpiry = $account->plan_expiry_date->addDays($days);

        $account->update([
            'plan_duration_days' => $account->plan_duration_days + $days,
            'plan_expiry_date' => $newExpiry,
            'status' => $account->mother_account_id ? 'active' : 'unassigned',
        ]);

        ActivityLog::log('plan_extended', "Account {$account->email} plan extended by {$days} days", $account);
    }
}
