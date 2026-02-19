<?php

namespace App\Http\Controllers;

use App\Models\MotherAccount;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MotherAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = MotherAccount::query()
            ->withCount([
                'accounts as seats_used_count' => function ($q) {
                    $q->where('status', 'active');
                }
            ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $mothers = $query->orderByRaw("FIELD(status, 'active', 'expired', 'archived')")
            ->orderBy('expiry_date')
            ->paginate(15);

        return view('mothers.index', compact('mothers'));
    }

    public function create()
    {
        return view('mothers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'max_capacity' => 'required|integer|min:1|max:10',
            'lifespan_days' => 'required|integer|min:1|max:365',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['start_date'] = now()->toDateString();
        $validated['expiry_date'] = now()->addDays((int) $validated['lifespan_days'])->toDateString();
        $validated['status'] = 'active';

        $mother = MotherAccount::create($validated);

        ActivityLog::log('mother_created', "Mother account {$mother->email} created", $mother);

        return redirect()->route('mothers.show', $mother)->with('success', 'Mother account created successfully.');
    }

    public function show(MotherAccount $mother)
    {
        $mother->load([
            'accounts' => function ($q) {
                $q->whereIn('status', ['active', 'unassigned', 'cooldown'])->with('buyer');
            }
        ]);

        return view('mothers.show', compact('mother'));
    }

    public function edit(MotherAccount $mother)
    {
        if ($mother->status === 'archived') {
            return redirect()->route('mothers.show', $mother)->with('error', 'Archived mothers cannot be edited.');
        }

        return view('mothers.edit', compact('mother'));
    }

    public function update(Request $request, MotherAccount $mother)
    {
        if ($mother->status === 'archived') {
            return redirect()->route('mothers.show', $mother)->with('error', 'Archived mothers cannot be edited.');
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'max_capacity' => 'required|integer|min:1|max:10',
            'lifespan_days' => 'required|integer|min:1|max:365',
            'notes' => 'nullable|string|max:500',
        ]);

        // Recalculate expiry if lifespan changed
        if ($validated['lifespan_days'] != $mother->lifespan_days) {
            $validated['expiry_date'] = $mother->start_date->addDays((int) $validated['lifespan_days'])->toDateString();
        }

        $mother->update($validated);

        ActivityLog::log('mother_updated', "Mother account {$mother->email} updated", $mother);

        return redirect()->route('mothers.show', $mother)->with('success', 'Mother account updated.');
    }

    public function archive(MotherAccount $mother)
    {
        $mother->update(['status' => 'archived']);

        ActivityLog::log('mother_archived', "Mother account {$mother->email} archived permanently", $mother);

        return redirect()->route('mothers.index')->with('success', 'Mother account archived.');
    }
}
