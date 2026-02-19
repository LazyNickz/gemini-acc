<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $query = Buyer::withCount('accounts');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('contact', 'like', '%' . $request->search . '%');
            });
        }

        $buyers = $query->orderBy('name')->paginate(15);

        return view('buyers.index', compact('buyers'));
    }

    public function create()
    {
        return view('buyers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'meta_campaign' => 'nullable|string|max:255',
            'meta_ad_set' => 'nullable|string|max:255',
            'meta_notes' => 'nullable|string|max:500',
        ]);

        $buyer = Buyer::create($validated);

        ActivityLog::log('buyer_created', "Buyer {$buyer->name} created", $buyer);

        return redirect()->route('buyers.index')->with('success', 'Buyer created successfully.');
    }

    public function show(Buyer $buyer)
    {
        $buyer->load(['accounts.motherAccount', 'orders']);
        return view('buyers.show', compact('buyer'));
    }

    public function edit(Buyer $buyer)
    {
        return view('buyers.edit', compact('buyer'));
    }

    public function update(Request $request, Buyer $buyer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'meta_campaign' => 'nullable|string|max:255',
            'meta_ad_set' => 'nullable|string|max:255',
            'meta_notes' => 'nullable|string|max:500',
        ]);

        $buyer->update($validated);

        ActivityLog::log('buyer_updated', "Buyer {$buyer->name} updated", $buyer);

        return redirect()->route('buyers.show', $buyer)->with('success', 'Buyer updated.');
    }

    public function destroy(Buyer $buyer)
    {
        $name = $buyer->name;
        $buyer->delete();

        ActivityLog::log('buyer_deleted', "Buyer {$name} deleted");

        return redirect()->route('buyers.index')->with('success', 'Buyer deleted.');
    }
}
