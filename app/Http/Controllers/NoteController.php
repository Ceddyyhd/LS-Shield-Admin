<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Auth;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'note_title' => 'required|string|max:255',
            'note_type' => 'required|string',
            'note_content' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $note = new Note();
        $note->title = $request->note_title;
        $note->type = $request->note_type;
        $note->content = $request->note_content;
        $note->user_id = $request->user_id;
        $note->created_by = Auth::id();
        $note->save();

        return redirect()->back()->with('success', 'Note added successfully.');
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return redirect()->back()->with('success', 'Note deleted successfully.');
    }
}