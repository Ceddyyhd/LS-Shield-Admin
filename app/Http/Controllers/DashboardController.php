<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Rabatt;
use App\Models\Announcement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $attendanceData = Attendance::with(['user.role'])
        ->where('status', 'present')
        ->orderBy('timestamp', 'desc')
        ->get();

    $rabatte = Rabatt::all();
    
    $announcements = Announcement::with('creator')
        ->orderBy('priority', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.dashboard', compact('attendanceData', 'rabatte', 'announcements'));
}

public function getAnnouncements()
{
    $announcements = Announcement::with('creator')
        ->orderBy('priority', 'desc')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($announcement) {
            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'description' => $announcement->description,
                'priority' => $announcement->priority,
                'created_by' => $announcement->creator->name,
                'created_at' => $announcement->created_at
            ];
        });

    return response()->json($announcements);
}
}