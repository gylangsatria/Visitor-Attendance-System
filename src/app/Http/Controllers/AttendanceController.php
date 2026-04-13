<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Base query
        if ($user->isAdmin() || $user->isEditor()) {
            $query = Attendance::with('user');
        } else {
            $query = $user->attendances();
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
                
                // Search by user name (for admin/editor)
                if (auth()->user()->isAdmin() || auth()->user()->isEditor()) {
                    $q->orWhereHas('user', function($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            });
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_time', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('attendance_time', '<=', $request->end_date);
        }
        
        // Order by latest first and paginate
        $attendances = $query->orderBy('attendance_time', 'desc')
            ->paginate(15)
            ->appends($request->query());
        
        return view('attendances.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        // Validate request
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if already checked in today
        $todayAttendance = Attendance::where('user_id', auth()->id())
            ->where('type', 'check_in')
            ->whereDate('attendance_time', today())
            ->exists();

        if ($todayAttendance) {
            return back()->with('error', 'You have already checked in today!');
        }

        // Create check-in record
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
        // Validate request
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if checked in today
        $lastCheckIn = Attendance::where('user_id', auth()->id())
            ->where('type', 'check_in')
            ->whereDate('attendance_time', today())
            ->latest()
            ->first();

        if (!$lastCheckIn) {
            return back()->with('error', 'You need to check in first!');
        }

        // Check if already checked out today
        $alreadyCheckedOut = Attendance::where('user_id', auth()->id())
            ->where('type', 'check_out')
            ->whereDate('attendance_time', today())
            ->exists();

        if ($alreadyCheckedOut) {
            return back()->with('error', 'You have already checked out today!');
        }

        // Create check-out record
        Attendance::create([
            'user_id' => auth()->id(),
            'type' => 'check_out',
            'attendance_time' => now(),
            'ip_address' => $request->ip(),
            'notes' => $request->notes
        ]);

        return redirect()->route('dashboard')->with('success', 'Checked out successfully!');
    }
    
    // Optional: Method to get attendance statistics
    public function statistics(Request $request)
    {
        $user = auth()->user();
        
        $query = Attendance::query();
        
        // Filter by user if not admin/editor
        if (!$user->isAdmin() && !$user->isEditor()) {
            $query->where('user_id', $user->id);
        }
        
        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_time', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('attendance_time', '<=', $request->end_date);
        }
        
        $statistics = [
            'total_check_ins' => (clone $query)->where('type', 'check_in')->count(),
            'total_check_outs' => (clone $query)->where('type', 'check_out')->count(),
            'unique_users' => (clone $query)->distinct('user_id')->count('user_id'),
            'today_check_ins' => Attendance::where('type', 'check_in')
                ->whereDate('attendance_time', today())
                ->count(),
            'today_check_outs' => Attendance::where('type', 'check_out')
                ->whereDate('attendance_time', today())
                ->count(),
        ];
        
        return response()->json($statistics);
    }
}