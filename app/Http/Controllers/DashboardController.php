<?php

namespace App\Http\Controllers;

use App\Models\MotherAccount;
use App\Models\Account;
use App\Models\Alert;
use App\Models\Buyer;

class DashboardController extends Controller
{
    public function index()
    {
        $mothers = MotherAccount::active()
            ->withCount([
                'accounts as seats_used_count' => function ($q) {
                    $q->where('status', 'active');
                }
            ])
            ->orderBy('expiry_date')
            ->get();

        $alerts = Alert::unresolved()
            ->orderByRaw("FIELD(severity, 'critical', 'warning')")
            ->latest()
            ->take(20)
            ->get();

        $stats = [
            'total_mothers' => MotherAccount::active()->count(),
            'total_accounts' => Account::whereIn('status', ['active', 'unassigned'])->count(),
            'unassigned_accounts' => Account::unassigned()->count(),
            'cooldown_accounts' => Account::cooldown()->count(),
            'expiring_mothers' => MotherAccount::expiringSoon()->count(),
            'expiring_plans' => Account::planExpiringSoon()->count(),
            'total_buyers' => Buyer::count(),
            'unresolved_alerts' => Alert::unresolved()->count(),
        ];

        return view('dashboard', compact('mothers', 'alerts', 'stats'));
    }
}
