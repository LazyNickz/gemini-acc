<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Buyer;
use App\Models\MotherAccount;
use App\Models\ActivityLog;
use App\Services\TransferService;
use App\Services\AlertService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected TransferService $transferService;
    protected AlertService $alertService;

    public function __construct(TransferService $transferService, AlertService $alertService)
    {
        $this->transferService = $transferService;
        $this->alertService = $alertService;
    }

    public function index(Request $request)
    {
        $query = Account::with(['motherAccount', 'buyer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('mother_id')) {
            $query->where('mother_account_id', $request->mother_id);
        }

        $accounts = $query->orderByRaw("FIELD(status, 'unassigned', 'active', 'cooldown', 'deleted')")
            ->latest()
            ->paginate(15);

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $mothers = MotherAccount::active()->get()->filter(fn($m) => $m->seats_remaining > 0);
        $buyers = Buyer::orderBy('name')->get();

        return view('accounts.create', compact('mothers', 'buyers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'mother_account_id' => 'required|exists:mother_accounts,id',
            'buyer_id' => 'nullable|exists:buyers,id',
            'buyer_name' => 'required_without:buyer_id|nullable|string|max:255',
            'buyer_contact' => 'nullable|string|max:255',
            'meta_campaign' => 'nullable|string|max:255',
            'meta_ad_set' => 'nullable|string|max:255',
            'meta_notes' => 'nullable|string|max:500',
            'plan_duration_days' => 'required|integer|min:1|max:365',
            'order_id' => 'nullable|string|max:255|unique:orders,order_id',
            'amount' => 'nullable|numeric|min:0',
        ]);

        // Check email uniqueness across active mothers
        $emailExists = Account::where('email', $validated['email'])
            ->where('status', 'active')
            ->exists();

        if ($emailExists) {
            return back()->withInput()->withErrors(['email' => 'This email is already assigned to an active mother account.']);
        }

        // Check mother capacity
        $mother = MotherAccount::findOrFail($validated['mother_account_id']);
        if ($mother->seats_remaining <= 0) {
            return back()->withInput()->withErrors(['mother_account_id' => 'This mother account is at full capacity.']);
        }

        // Create or find buyer
        if (empty($validated['buyer_id'])) {
            $buyer = Buyer::create([
                'name' => $validated['buyer_name'],
                'contact' => $validated['buyer_contact'] ?? null,
                'meta_campaign' => $validated['meta_campaign'] ?? null,
                'meta_ad_set' => $validated['meta_ad_set'] ?? null,
                'meta_notes' => $validated['meta_notes'] ?? null,
            ]);
            $buyerId = $buyer->id;
        } else {
            $buyerId = $validated['buyer_id'];
        }

        $account = Account::create([
            'email' => $validated['email'],
            'mother_account_id' => $validated['mother_account_id'],
            'buyer_id' => $buyerId,
            'plan_duration_days' => $validated['plan_duration_days'],
            'plan_start_date' => now()->toDateString(),
            'plan_expiry_date' => now()->addDays((int) $validated['plan_duration_days'])->toDateString(),
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        // Create order if order_id provided
        if (!empty($validated['order_id'])) {
            $account->orders()->create([
                'order_id' => $validated['order_id'],
                'buyer_id' => $buyerId,
                'amount' => $validated['amount'] ?? null,
            ]);
        }

        ActivityLog::log('account_created', "Account {$account->email} created and assigned to {$mother->email}", $account);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $account->load(['motherAccount', 'buyer', 'orders']);
        return view('accounts.show', compact('account'));
    }

    public function transferForm(Account $account)
    {
        if (!in_array($account->status, ['unassigned', 'active'])) {
            return redirect()->route('accounts.show', $account)
                ->with('error', 'This account cannot be transferred.');
        }

        $suggestedMothers = $this->transferService->suggestMothers($account);

        return view('accounts.transfer', compact('account', 'suggestedMothers'));
    }

    public function transfer(Request $request, Account $account)
    {
        $validated = $request->validate([
            'mother_account_id' => 'required|exists:mother_accounts,id',
        ]);

        $mother = MotherAccount::findOrFail($validated['mother_account_id']);

        $success = $this->transferService->reassignAccount($account, $mother);

        if (!$success) {
            return back()->with('error', 'Transfer failed. Mother may be full or inactive.');
        }

        $this->alertService->resolveAlertsFor($account);

        return redirect()->route('accounts.show', $account)->with('success', 'Account transferred successfully.');
    }

    public function extendPlan(Request $request, Account $account)
    {
        $validated = $request->validate([
            'extension_days' => 'required|integer|min:1|max:365',
            'order_id' => 'nullable|string|max:255|unique:orders,order_id',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $this->transferService->extendPlan($account, (int) $validated['extension_days']);

        // Create order record if provided
        if (!empty($validated['order_id'])) {
            $account->orders()->create([
                'order_id' => $validated['order_id'],
                'buyer_id' => $account->buyer_id,
                'amount' => $validated['amount'] ?? null,
                'notes' => "Plan extension: {$validated['extension_days']} days",
            ]);
        }

        return redirect()->route('accounts.show', $account)->with('success', 'Plan extended successfully.');
    }

    public function destroy(Account $account)
    {
        $account->update(['status' => 'deleted']);

        ActivityLog::log('account_deleted', "Account {$account->email} marked as deleted", $account);

        $this->alertService->resolveAlertsFor($account);

        return redirect()->route('accounts.index')->with('success', 'Account deleted.');
    }
}
