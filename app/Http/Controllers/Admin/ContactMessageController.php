<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        // Opening a message marks it as read.
        if ($message->isUnread()) {
            $message->forceFill(['read_at' => now()])->save();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('status', 'Message deleted.');
    }
}