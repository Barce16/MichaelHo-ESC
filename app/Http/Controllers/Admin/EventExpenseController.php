<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventExpenseController extends Controller
{
    /**
     * Display expenses for an event
     */
    public function index(Event $event)
    {
        $expenses = $event->expenses()
            ->with('addedBy')
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $expensesByCategory = $expenses->groupBy('category')->map(fn($items) => $items->sum('amount'));

        return view('admin.events.expenses.index', compact(
            'event',
            'expenses',
            'totalExpenses',
            'expensesByCategory'
        ));
    }

    /**
     * Store a new expense
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category' => 'nullable|string|max:50',
            'expense_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        // Handle receipt image upload
        if ($request->hasFile('receipt_image')) {
            $validated['receipt_image'] = $request->file('receipt_image')
                ->store('expense-receipts/' . $event->id, 'public');
        }

        $validated['event_id'] = $event->id;
        $validated['added_by'] = Auth::id();
        $validated['expense_date'] = $validated['expense_date'] ?? now()->toDateString();

        EventExpense::create($validated);

        return back()->with('success', 'Expense added successfully.');
    }

    /**
     * Update an expense
     */
    public function update(Request $request, Event $event, EventExpense $expense)
    {
        // Ensure expense belongs to event
        if ($expense->event_id !== $event->id) {
            abort(404);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:9999999.99',
            'category' => 'nullable|string|max:50',
            'expense_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle receipt image upload
        if ($request->hasFile('receipt_image')) {
            // Delete old image if exists
            if ($expense->receipt_image) {
                Storage::disk('public')->delete($expense->receipt_image);
            }
            $validated['receipt_image'] = $request->file('receipt_image')
                ->store('expense-receipts/' . $event->id, 'public');
        }

        $expense->update($validated);

        return back()->with('success', 'Expense updated successfully.');
    }

    /**
     * Delete an expense
     */
    public function destroy(Event $event, EventExpense $expense)
    {
        // Ensure expense belongs to event
        if ($expense->event_id !== $event->id) {
            abort(404);
        }

        // Delete receipt image if exists
        if ($expense->receipt_image) {
            Storage::disk('public')->delete($expense->receipt_image);
        }

        $expense->delete();

        return back()->with('success', 'Expense deleted successfully.');
    }

    /**
     * Get expenses summary (for AJAX)
     */
    public function summary(Event $event)
    {
        $expenses = $event->expenses;

        return response()->json([
            'total' => $expenses->sum('amount'),
            'count' => $expenses->count(),
            'by_category' => $expenses->groupBy('category')->map(fn($items) => [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
            ]),
        ]);
    }
}
