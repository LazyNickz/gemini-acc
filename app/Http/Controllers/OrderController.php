<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Account;
use App\Models\Buyer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'account']);

        if ($request->filled('search')) {
            $query->where('order_id', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $buyers = Buyer::orderBy('name')->get();
        $accounts = Account::whereIn('status', ['active', 'unassigned', 'cooldown'])->get();

        return view('orders.create', compact('buyers', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string|max:255|unique:orders,order_id',
            'buyer_id' => 'required|exists:buyers,id',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = Order::create($validated);

        ActivityLog::log('order_created', "Order {$order->order_id} created", $order);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['buyer', 'account.motherAccount']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $buyers = Buyer::orderBy('name')->get();
        $accounts = Account::whereIn('status', ['active', 'unassigned', 'cooldown'])->get();

        return view('orders.edit', compact('order', 'buyers', 'accounts'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_id' => 'required|string|max:255|unique:orders,order_id,' . $order->id,
            'buyer_id' => 'required|exists:buyers,id',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $order->update($validated);

        ActivityLog::log('order_updated', "Order {$order->order_id} updated", $order);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated.');
    }

    public function destroy(Order $order)
    {
        $orderId = $order->order_id;
        $order->delete();

        ActivityLog::log('order_deleted', "Order {$orderId} deleted");

        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }
}
