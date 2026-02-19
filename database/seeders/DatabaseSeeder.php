<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MotherAccount;
use App\Models\Account;
use App\Models\Buyer;
use App\Models\Order;
use App\Models\Alert;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Users ---
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mam.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@mam.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@mam.com',
            'password' => bcrypt('password'),
            'role' => 'viewer',
        ]);

        // --- Mother Accounts ---
        $mother1 = MotherAccount::create([
            'email' => 'mother_alpha@gmail.com',
            'max_capacity' => 5,
            'lifespan_days' => 30,
            'start_date' => now()->subDays(10)->toDateString(),
            'expiry_date' => now()->addDays(20)->toDateString(),
            'status' => 'active',
            'notes' => 'Primary mother account for Team A',
        ]);

        $mother2 = MotherAccount::create([
            'email' => 'mother_bravo@gmail.com',
            'max_capacity' => 3,
            'lifespan_days' => 14,
            'start_date' => now()->subDays(12)->toDateString(),
            'expiry_date' => now()->addDays(2)->toDateString(),
            'status' => 'active',
            'notes' => 'Expiring soon — demo mother',
        ]);

        $mother3 = MotherAccount::create([
            'email' => 'mother_charlie@gmail.com',
            'max_capacity' => 4,
            'lifespan_days' => 7,
            'start_date' => now()->subDays(8)->toDateString(),
            'expiry_date' => now()->subDays(1)->toDateString(),
            'status' => 'expired',
            'notes' => 'Already expired, for testing',
        ]);

        $mother4 = MotherAccount::create([
            'email' => 'mother_delta@gmail.com',
            'max_capacity' => 6,
            'lifespan_days' => 60,
            'start_date' => now()->toDateString(),
            'expiry_date' => now()->addDays(60)->toDateString(),
            'status' => 'active',
            'notes' => 'Long-lived mother with lots of capacity',
        ]);

        // --- Buyers ---
        $buyer1 = Buyer::create([
            'name' => 'John Rivera',
            'contact' => '+639171234567',
            'meta_campaign' => 'Spring 2026',
            'meta_ad_set' => 'AdSet-01',
            'meta_notes' => 'VIP buyer, high priority',
        ]);

        $buyer2 = Buyer::create([
            'name' => 'Maria Santos',
            'contact' => 'maria.santos@email.com',
            'meta_campaign' => 'Summer Promo',
            'meta_ad_set' => 'AdSet-05',
        ]);

        $buyer3 = Buyer::create([
            'name' => 'Kevin Lim',
            'contact' => 'telegram: @kevinlim',
        ]);

        $buyer4 = Buyer::create([
            'name' => 'Angela Cruz',
            'contact' => '+639209876543',
            'meta_campaign' => 'Black Friday 2026',
            'meta_ad_set' => 'AdSet-BF-01',
            'meta_notes' => 'Bulk buyer, needs fast turnaround',
        ]);

        // --- Accounts (assigned to mothers) ---
        $acct1 = Account::create([
            'email' => 'acc_alpha1@accounts.com',
            'mother_account_id' => $mother1->id,
            'buyer_id' => $buyer1->id,
            'plan_duration_days' => 30,
            'plan_start_date' => now()->subDays(5)->toDateString(),
            'plan_expiry_date' => now()->addDays(25)->toDateString(),
            'status' => 'active',
            'assigned_at' => now()->subDays(5),
        ]);

        $acct2 = Account::create([
            'email' => 'acc_alpha2@accounts.com',
            'mother_account_id' => $mother1->id,
            'buyer_id' => $buyer2->id,
            'plan_duration_days' => 15,
            'plan_start_date' => now()->subDays(10)->toDateString(),
            'plan_expiry_date' => now()->addDays(5)->toDateString(),
            'status' => 'active',
            'assigned_at' => now()->subDays(10),
        ]);

        $acct3 = Account::create([
            'email' => 'acc_bravo1@accounts.com',
            'mother_account_id' => $mother2->id,
            'buyer_id' => $buyer3->id,
            'plan_duration_days' => 14,
            'plan_start_date' => now()->subDays(12)->toDateString(),
            'plan_expiry_date' => now()->addDays(2)->toDateString(),
            'status' => 'active',
            'assigned_at' => now()->subDays(12),
        ]);

        $acct4 = Account::create([
            'email' => 'acc_bravo2@accounts.com',
            'mother_account_id' => $mother2->id,
            'buyer_id' => $buyer1->id,
            'plan_duration_days' => 7,
            'plan_start_date' => now()->subDays(8)->toDateString(),
            'plan_expiry_date' => now()->subDays(1)->toDateString(),
            'status' => 'cooldown',
            'assigned_at' => now()->subDays(8),
        ]);

        // Unassigned account (mother expired)
        $acct5 = Account::create([
            'email' => 'acc_unassigned@accounts.com',
            'mother_account_id' => null,
            'buyer_id' => $buyer4->id,
            'plan_duration_days' => 30,
            'plan_start_date' => now()->subDays(3)->toDateString(),
            'plan_expiry_date' => now()->addDays(27)->toDateString(),
            'status' => 'unassigned',
        ]);

        $acct6 = Account::create([
            'email' => 'acc_delta1@accounts.com',
            'mother_account_id' => $mother4->id,
            'buyer_id' => $buyer2->id,
            'plan_duration_days' => 60,
            'plan_start_date' => now()->toDateString(),
            'plan_expiry_date' => now()->addDays(60)->toDateString(),
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        // --- Orders ---
        Order::create([
            'buyer_id' => $buyer1->id,
            'account_id' => $acct1->id,
            'order_id' => 'ORD-001',
            'amount' => 150.00,
            'notes' => 'Initial purchase',
        ]);

        Order::create([
            'buyer_id' => $buyer2->id,
            'account_id' => $acct2->id,
            'order_id' => 'ORD-002',
            'amount' => 100.00,
            'notes' => 'Standard plan',
        ]);

        Order::create([
            'buyer_id' => $buyer3->id,
            'account_id' => $acct3->id,
            'order_id' => 'ORD-003',
            'amount' => 75.00,
        ]);

        Order::create([
            'buyer_id' => $buyer4->id,
            'account_id' => $acct5->id,
            'order_id' => 'ORD-004',
            'amount' => 200.00,
            'notes' => 'Premium bulk order',
        ]);

        // --- Alerts ---
        Alert::create([
            'alertable_type' => MotherAccount::class,
            'alertable_id' => $mother2->id,
            'type' => 'mother_expiry',
            'severity' => 'critical',
            'message' => "Mother account {$mother2->email} is expiring in 2 days!",
        ]);

        Alert::create([
            'alertable_type' => Account::class,
            'alertable_id' => $acct3->id,
            'type' => 'plan_expiry',
            'severity' => 'warning',
            'message' => "Account {$acct3->email} plan expiring in 2 days.",
        ]);

        Alert::create([
            'alertable_type' => Account::class,
            'alertable_id' => $acct5->id,
            'type' => 'transfer_needed',
            'severity' => 'warning',
            'message' => "Account {$acct5->email} needs to be transferred to a new mother.",
        ]);

        Alert::create([
            'alertable_type' => Account::class,
            'alertable_id' => $acct4->id,
            'type' => 'plan_expiry',
            'severity' => 'warning',
            'message' => "Account {$acct4->email} plan has expired and moved to cooldown.",
            'resolved' => true,
            'resolved_at' => now()->subHour(),
        ]);

        echo "✅ Database seeded with demo data!\n";
        echo "   Users: admin@mam.com / manager@mam.com / viewer@mam.com (password: password)\n";
        echo "   Mothers: 4 (2 active, 1 expiring soon, 1 expired)\n";
        echo "   Accounts: 6 (3 active, 1 cooldown, 1 unassigned, 1 new)\n";
        echo "   Buyers: 4 | Orders: 4 | Alerts: 4 (3 unresolved, 1 resolved)\n";
    }
}
