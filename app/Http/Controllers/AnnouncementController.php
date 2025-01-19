<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|in:low,mid,high'
        ]);

        Announcement::create([
            ...$validated,
            'created_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Ankündigung wurde erstellt');
    }
    public function destroy($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            
            // Optional: Add authorization check
            if (Auth::id() !== $announcement->created_by) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            $announcement->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete announcement'], 500);
        }
    }
}