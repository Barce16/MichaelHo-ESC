<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Show contact page
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'event_date' => ['nullable', 'date'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        try {
            // Send email to admin
            Mail::to(config('mail.from.address'))->send(new ContactFormMail($validated));

            // Optional: Store in database
            // Contact::create($validated);

            return back()->with('success', 'Thank you for contacting us! We\'ll get back to you within 24 hours.');
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            return back()->with('error', 'Sorry, something went wrong. Please try again or contact us directly.');
        }
    }
}
