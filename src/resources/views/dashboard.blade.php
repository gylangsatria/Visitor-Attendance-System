@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <!-- Tutup akses Guest / user -->
    @if(auth()->user()->access_level !== 4)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-calendar-check text-indigo-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Absensi Hari Ini</p>
                    <p class="text-2xl font-bold">{{ $today_attendance }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Visitor Hari Ini</p>
                    <p class="text-2xl font-bold">{{ $today_visitors }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-user-clock text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Visitor Aktif</p>
                    <p class="text-2xl font-bold">{{ $active_visitors }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Quick Actions - Sembunyikan untuk Guest (level 4) -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
        <div class="flex space-x-4">
            <form method="POST" action="{{ route('attendance.check-in') }}" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-sign-in-alt"></i> Check In
                </button>
            </form>
            <form method="POST" action="{{ route('attendance.check-out') }}" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    <i class="fas fa-sign-out-alt"></i> Check Out
                </button>
            </form>
            
            @if(auth()->user()->access_level !== 4)
            <a href="{{ route('visitors.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                <i class="fas fa-user-plus"></i> Register Visitor
            </a>
            @endif
        </div>
    </div>
    
    <!-- Recent Activities -->
    @if(isset($recent_activities))
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recent_activities as $activity)
                    <tr>
                        <td class="px-6 py-4">{{ $activity->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $activity->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $activity->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $activity->attendance_time }}</td>
                        <td class="px-6 py-4">{{ $activity->ip_address ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(isset($my_attendances))
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">My Recent Attendances</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($my_attendances as $attendance)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $attendance->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $attendance->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $attendance->attendance_time }}</td>
                        <td class="px-6 py-4">{{ $attendance->ip_address ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $attendance->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection