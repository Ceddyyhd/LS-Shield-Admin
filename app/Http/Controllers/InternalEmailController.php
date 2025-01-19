<?php

namespace App\Http\Controllers;

use App\Models\InternalEmail;
use App\Models\User;
use Illuminate\Http\Request;

class InternalEmailController extends Controller
{
    public function index()
    {
        $inboxEmails = InternalEmail::with('sender')
            ->where('to_user_id', auth()->id())
            ->where('status', 'sent')
            ->orderBy('created_at', 'desc')
            ->get();

        $sentEmails = InternalEmail::with('recipient')
            ->where('from_user_id', auth()->id())
            ->where('status', 'sent')
            ->orderBy('created_at', 'desc')
            ->get();

        $draftEmails = InternalEmail::with('recipient')
            ->where('from_user_id', auth()->id())
            ->where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->get();

        $users = User::where('id', '!=', auth()->id())->get();

        return view('apps.email', compact('inboxEmails', 'sentEmails', 'draftEmails', 'users'));
    }

    public function show($id)
{
    $email = InternalEmail::with(['sender', 'recipient'])->findOrFail($id);
    
    if ($email->to_user_id === auth()->id() && !$email->read_at) {
        $email->update(['read_at' => now()]);
    }

    return response()->json([
        'email' => $email,
        'sender_name' => $email->sender->name,
        'recipient_name' => $email->recipient->name,
        'formatted_date' => $email->created_at->format('d.m.Y H:i')
    ]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'required|in:draft,sent'
        ]);

        InternalEmail::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $validated['to_user_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'status' => $validated['status']
        ]);

        return redirect()->route('email.index')
            ->with('success', 'Email ' . ($validated['status'] === 'sent' ? 'sent' : 'saved as draft'));
    }
}