<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    private const THANKS = 'Thanks! Your message has been sent. We will get back to you soon.';

    public function store(Request $request)
    {
        // Honeypot: bots fill this hidden field, humans never see it.
        if ($request->filled('company_site_check')) {
            Log::warning('Contact form: spam trap field was filled, message ignored.');

            return $this->done($request);
        }

        // For JavaScript requests Laravel answers validation problems as JSON (422) by itself.
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:190'],
            'phone'   => ['nullable', 'string', 'max:40'],
            'service' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($data);

        return $this->done($request);
    }

    /** JSON for the no-reload form, a normal redirect if JavaScript is off. */
    private function done(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => self::THANKS]);
        }

        return redirect(url('/') . '#contact')->with('contact_success', self::THANKS);
    }
}