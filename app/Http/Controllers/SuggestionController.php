<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Models\SuggestionVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::with(['user', 'votes'])
            ->withCount(['upvotes', 'downvotes'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.feedback.suggestions', compact('suggestions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'area' => 'required|string',
            'is_anonymous' => 'boolean'
        ]);

        $suggestion = new Suggestion($validated);
        $suggestion->user_id = Auth::id();
        $suggestion->status = 'eingegangen';
        $suggestion->save();

        return redirect()->back()->with('success', 'Vorschlag wurde erstellt');
    }

    public function update(Request $request, Suggestion $suggestion)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'area' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $suggestion->update($validated);

        return redirect()->back()->with('success', 'Vorschlag wurde aktualisiert');
    }

    public function vote(Request $request, Suggestion $suggestion)
    {
        $vote = SuggestionVote::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'suggestion_id' => $suggestion->id
            ],
            ['is_upvote' => $request->is_upvote]
        );

        $upvotesCount = $suggestion->votes()->where('is_upvote', true)->count();
        $downvotesCount = $suggestion->votes()->where('is_upvote', false)->count();

        return response()->json([
            'success' => true,
            'upvotes' => $upvotesCount,
            'downvotes' => $downvotesCount
        ]);
    }

    public function destroy(Suggestion $suggestion)
    {
        if ($suggestion->user_id !== Auth::id()) {
            abort(403);
        }

        $suggestion->delete();
        return redirect()->back()->with('success', 'Vorschlag wurde gelöscht');
    }
}