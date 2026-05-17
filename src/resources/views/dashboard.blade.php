@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    @if(auth()->user()->access_level !== 4)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-calendar-check text-indigo-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">{{ __('Absensi Hari Ini') }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $today_attendance ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">{{ __('Visitor Hari Ini') }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $today_visitors ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-user-clock text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">{{ __('Visitor Aktif') }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $active_visitors ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ __('Quick Actions') }}</h3>
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Check In Button -->
            <form method="POST" action="{{ route('attendance.check-in') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center justify-center gap-2 font-medium">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>{{ __('Check In') }}</span>
                </button>
            </form>
            
            <!-- Check Out Button -->
            <form method="POST" action="{{ route('attendance.check-out') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center justify-center gap-2 font-medium">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ __('Check Out') }}</span>
                </button>
            </form>
            
            <!-- Register Visitor Button (only for non-guest) -->
            @if(auth()->user()->access_level !== 4)
            <a href="{{ route('visitors.create') }}" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors duration-200 flex items-center justify-center gap-2 font-medium text-center">
                <i class="fas fa-user-plus"></i>
                <span>{{ __('Register Visitor') }}</span>
            </a>
            @endif
        </div>
    </div>
    
    <!-- Recent Activities Section -->
    @if(isset($recent_activities) && count($recent_activities) > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('Recent Activities') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Time') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('IP Address') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recent_activities as $activity)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $activity->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $activity->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas {{ $activity->type == 'check_in' ? 'fa-sign-in-alt' : 'fa-sign-out-alt' }} mr-1"></i>
                                {{ ucfirst($activity->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->attendance_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->ip_address ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(isset($my_attendances) && count($my_attendances) > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('My Recent Attendances') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Time') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('IP Address') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($my_attendances as $attendance)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $attendance->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas {{ $attendance->type == 'check_in' ? 'fa-sign-in-alt' : 'fa-sign-out-alt' }} mr-1"></i>
                                {{ ucfirst($attendance->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->attendance_time }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->ip_address ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $attendance->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection