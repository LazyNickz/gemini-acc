<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $query = Alert::with('alertable');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $filter = $request->input('filter');
        if ($filter === 'resolved') {
            $query->resolved();
        } elseif ($filter === 'unresolved') {
            $query->unresolved();
        }

        $alerts = $query->orderByRaw("FIELD(severity, 'critical', 'warning')")
            ->latest()
            ->paginate(20);

        $unresolvedCount = Alert::unresolved()->count();

        return view('alerts.index', compact('alerts', 'unresolvedCount'));
    }

    public function resolve(Alert $alert)
    {
        $alert->resolve();

        ActivityLog::log('alert_resolved', "Alert resolved: {$alert->message}", $alert);

        return back()->with('success', 'Alert resolved.');
    }

    public function resolveAll()
    {
        $count = Alert::unresolved()->count();

        Alert::unresolved()->update([
            'resolved' => true,
            'resolved_at' => now(),
        ]);

        ActivityLog::log('alerts_cleared', "All {$count} alerts resolved");

        return back()->with('success', "{$count} alerts resolved.");
    }
}
