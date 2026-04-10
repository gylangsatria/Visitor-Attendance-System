<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isEditor()) {
            $attendances = Attendance::with('user')
                ->orderBy('attendance_time', 'desc')
                ->paginate(20);
        } else {
            $attendances = $user->attendances()
                ->orderBy('attendance_time', 'desc')
                ->paginate(20);
        }
        
        return view('attendances.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        $todayAttendance = Attendance::where('user_id', auth()->id())
            ->where('type', 'check_in')
            ->whereDate('attendance_time', today())
            ->exists();

        if ($todayAttendance) {
            return back()->with('error', 'You have already checked in today!');
        }

        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'type' => 'check_in',
            'attendance_time' => now(),
            'ip_address' => $request->ip(),
            'notes' => $request->notes
        ]);

        return redirect()->route('dashboard')->with('success', 'Checked in successfully!');
    }

    public function checkOut(Request $request)
    {
        $lastCheckIn = Attendance::where('user_id', auth()->id())
            ->where('type', 'check_in')
            ->whereDate('attendance_time', today())
            ->latest()
            ->first();

        if (!$lastCheckIn) {
            return back()->with('error', 'You need to check in first!');
        }

        $alreadyCheckedOut = Attendance::where('user_id', auth()->id())
            ->where('type', 'check_out')
            ->whereDate('attendance_time', today())
            ->exists();

        if ($alreadyCheckedOut) {
            return back()->with('error', 'You have already checked out today!');
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'type' => 'check_out',
            'attendance_time' => now(),
            'ip_address' => $request->ip(),
            'notes' => $request->notes
        ]);

        return redirect()->route('dashboard')->with('success', 'Checked out successfully!');
    }
}