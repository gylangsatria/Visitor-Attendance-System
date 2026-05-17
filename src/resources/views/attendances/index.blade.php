@extends('layouts.app')

@section('title', __('Attendance History'))

@section('content')
<div class="bg-white rounded-lg shadow p-4 sm:p-6">
    <h3 class="text-lg font-semibold mb-4">{{ __('Attendance History') }}</h3>
    
    <!-- Search and Filter Section -->
    <div class="mb-6">
        <form method="GET" action="{{ route('attendances.index') }}" class="space-y-3">
            <!-- Row 1: Search and Type Filter -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search Input -->
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="{{ __('Search by user or notes...') }}" 
                               class="w-full pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <!-- Filter by Type -->
                <div class="sm:w-36">
                    <select name="type" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">{{ __('All Types') }}</option>
                        <option value="check_in" {{ request('type') == 'check_in' ? 'selected' : '' }}>{{ __('Check In') }}</option>
                        <option value="check_out" {{ request('type') == 'check_out' ? 'selected' : '' }}>{{ __('Check Out') }}</option>
                    </select>
                </div>
            </div>
            
            <!-- Row 2: Date Range Filters -->
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 sm:w-44">
                    <input type="date" 
                           name="start_date" 
                           value="{{ request('start_date') }}" 
                           placeholder="Start Date"
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div class="flex-1 sm:w-44">
                    <input type="date" 
                           name="end_date" 
                           value="{{ request('end_date') }}" 
                           placeholder="End Date"
                           class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-2 sm:ml-auto">
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition flex items-center gap-1.5">
                        <i class="fas fa-filter text-xs"></i>
                        <span>{{ __('Filter') }}</span>
                    </button>
                    
                    <a href="{{ route('attendances.index') }}" class="px-4 py-1.5 bg-gray-500 text-white text-sm rounded hover:bg-gray-600 transition flex items-center gap-1.5">
                        <i class="fas fa-undo-alt text-xs"></i>
                        <span>{{ __('Reset') }}</span>
                    </a>
                </div>
            </div>
            
            <!-- Result Count (compact) -->
            <div class="text-xs text-gray-500 pt-1">
                <i class="fas fa-chart-line mr-1 text-xs"></i>
                {{ __('Showing') }} {{ $attendances->firstItem() ?? 0 }} - {{ $attendances->lastItem() ?? 0 }} {{ __('of') }} {{ $attendances->total() }} {{ __('records') }}
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                    @endif
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date & Time') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('IP Address') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Notes') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                <tr class="hover:bg-gray-50 transition-colors">
                    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-gray-400 mr-2 text-xs"></i>
                            {{ $attendance->user->name }}
                        </div>
                    </td>
                    @endif
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $attendance->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $attendance->type == 'check_in' ? 'fa-sign-in-alt' : 'fa-sign-out-alt' }} mr-1 text-xs"></i>
                            {{ $attendance->type == 'check_in' ? __('Check In') : __('Check Out') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1 text-xs"></i>
                        {{ $attendance->attendance_time }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-network-wired mr-1 text-xs"></i>
                        {{ $attendance->ip_address ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 break-words max-w-xs">
                        {{ Str::limit($attendance->notes ?? '-', 50) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() || auth()->user()->isEditor() ? 5 : 4 }}" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm">{{ __('No attendance records found.') }}</p>
                            <p class="text-xs mt-1">{{ __('Try adjusting your search or filter criteria.') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $attendances->appends(request()->query())->links() }}
    </div>
</div>
@endsection