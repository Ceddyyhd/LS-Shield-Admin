<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function toggle(Request $request)
{
    $user = Auth::user();
    $lastAttendance = $user->attendances()->latest()->first();
    
    if ($lastAttendance) {
        // User has an existing attendance record
        if ($lastAttendance->status === 'present') {
            // If present, delete record to mark as absent
            $lastAttendance->delete();
            $newStatus = 'absent';
        } else {
            // If absent, create new present record
            Attendance::create([
                'user_id' => $user->id,
                'status' => 'present',
                'timestamp' => now()
            ]);
            $newStatus = 'present';
        }
    } else {
        // No existing record, create new present record
        Attendance::create([
            'user_id' => $user->id,
            'status' => 'present',
            'timestamp' => now()
        ]);
        $newStatus = 'present';
    }
    
    return redirect()->back()->with('status', $newStatus);
}
}