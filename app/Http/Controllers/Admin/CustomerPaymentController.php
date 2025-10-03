<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Billing;
use Illuminate\Http\Request;

class CustomerPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('billing', 'event')
            ->where('status', 'pending')
            ->get();

        return view('admin.payments.index', compact('payments'));
    }

    public function approve($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        $payment->status = 'approved';
        $payment->save();

        $billing = $payment->billing;
        $billing->total_amount -= $payment->amount;
        $billing->save();

        return redirect()->route('admin.payments.index')->with('success', 'Payment approved successfully.');
    }

    public function reject($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        $payment->status = 'rejected';
        $payment->save();

        return redirect()->route('admin.payments.index')->with('error', 'Payment rejected.');
    }
}
