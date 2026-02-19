<?php

namespace App\Http\Controllers;

use App\Models\MotherAccount;
use App\Models\Account;
use App\Models\Buyer;
use App\Models\Order;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected ExportService $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function index()
    {
        return view('exports.index');
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:mothers,accounts,buyers,orders',
            'format' => 'required|in:csv,xlsx,pdf',
        ]);

        $entity = $validated['type'];
        $format = $validated['format'];

        switch ($entity) {
            case 'mothers':
                $data = MotherAccount::all()->map(fn($m) => [
                    'id' => $m->id,
                    'email' => $m->email,
                    'max_capacity' => $m->max_capacity,
                    'seats_used' => $m->seats_used,
                    'lifespan_days' => $m->lifespan_days,
                    'start_date' => $m->start_date->format('Y-m-d'),
                    'expiry_date' => $m->expiry_date->format('Y-m-d'),
                    'days_remaining' => $m->days_until_expiry,
                    'status' => $m->status,
                ]);
                $headers = ['ID', 'Email', 'Max Capacity', 'Seats Used', 'Lifespan Days', 'Start Date', 'Expiry Date', 'Days Remaining', 'Status'];
                break;

            case 'accounts':
                $data = Account::with(['motherAccount', 'buyer'])->get()->map(fn($a) => [
                    'id' => $a->id,
                    'email' => $a->email,
                    'mother_email' => $a->motherAccount?->email ?? 'Unassigned',
                    'buyer_name' => $a->buyer?->name ?? 'N/A',
                    'plan_duration_days' => $a->plan_duration_days,
                    'plan_start_date' => $a->plan_start_date->format('Y-m-d'),
                    'plan_expiry_date' => $a->plan_expiry_date->format('Y-m-d'),
                    'plan_days_remaining' => $a->plan_days_remaining,
                    'status' => $a->status,
                ]);
                $headers = ['ID', 'Email', 'Mother Email', 'Buyer Name', 'Plan Duration Days', 'Plan Start Date', 'Plan Expiry Date', 'Plan Days Remaining', 'Status'];
                break;

            case 'buyers':
                $data = Buyer::withCount('accounts')->get()->map(fn($b) => [
                    'id' => $b->id,
                    'name' => $b->name,
                    'contact' => $b->contact ?? '',
                    'accounts_count' => $b->accounts_count,
                    'meta_campaign' => $b->meta_campaign ?? '',
                    'meta_ad_set' => $b->meta_ad_set ?? '',
                ]);
                $headers = ['ID', 'Name', 'Contact', 'Accounts Count', 'Meta Campaign', 'Meta Ad Set'];
                break;

            case 'orders':
                $data = Order::with(['buyer', 'account'])->get()->map(fn($o) => [
                    'id' => $o->id,
                    'order_id' => $o->order_id,
                    'buyer_name' => $o->buyer?->name ?? '',
                    'account_email' => $o->account?->email ?? '',
                    'amount' => $o->amount ?? '',
                    'notes' => $o->notes ?? '',
                    'created_at' => $o->created_at->format('Y-m-d H:i'),
                ]);
                $headers = ['ID', 'Order ID', 'Buyer Name', 'Account Email', 'Amount', 'Notes', 'Created At'];
                break;
        }

        $filename = "{$entity}_export_" . date('Y-m-d');

        return match ($format) {
            'csv' => $this->exportService->exportCSV($data, $filename, $headers),
            'xlsx' => $this->exportService->exportExcel($data, $filename, $headers),
            'pdf' => $this->exportService->exportPDF($data, $filename, 'exports.pdf', [
                'title' => ucfirst($entity) . ' Export',
                'headers' => $headers,
            ]),
        };
    }
}
