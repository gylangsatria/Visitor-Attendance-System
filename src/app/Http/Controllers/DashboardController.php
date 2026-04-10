<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'today_attendance' => Attendance::whereDate('attendance_time', today())->count(),
            'today_visitors' => Visitor::whereDate('check_in_time', today())->count(),
            'active_visitors' => Visitor::where('status', 'active')->count(),
            'user' => $user
        ];

        if ($user->isAdmin() || $user->isEditor()) {
            $data['recent_activities'] = DB::table('attendances')
                ->join('users', 'attendances.user_id', '=', 'users.id')
                ->select('attendances.*', 'users.name')
                ->orderBy('attendances.created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            $data['my_attendances'] = $user->attendances()
                ->orderBy('attendance_time', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', $data);
    }
}