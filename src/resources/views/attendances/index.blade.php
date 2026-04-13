@extends('layouts.app')

@section('title', 'Attendance History')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Attendance History</h3>
    
    <!-- Search and Filter Section -->
    <div class="mb-6 space-y-4">
        <form method="GET" action="{{ route('attendances.index') }}" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by user or notes..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <!-- Filter by Type -->
                <div class="sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Types</option>
                        <option value="check_in" {{ request('type') == 'check_in' ? 'selected' : '' }}>Check In</option>
                        <option value="check_out" {{ request('type') == 'check_out' ? 'selected' : '' }}>Check Out</option>
                    </select>
                </div>
                
                <!-- Filter by Date Range -->
                <div class="sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" 
                           name="start_date" 
                           value="{{ request('start_date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div class="sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" 
                           name="end_date" 
                           value="{{ request('end_date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            
            <!-- Filter Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-between items-center">
                <div class="flex gap-3 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i>
                        <span>Apply Filters</span>
                    </button>
                    
                    <a href="{{ route('attendances.index') }}" class="flex-1 sm:flex-none bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition flex items-center justify-center gap-2">
                        <i class="fas fa-undo-alt"></i>
                        <span>Reset</span>
                    </a>
                </div>
                
                <!-- Result Count -->
                <div class="text-sm text-gray-600">
                    <i class="fas fa-chart-line mr-1"></i>
                    Showing {{ $attendances->firstItem() ?? 0 }} - {{ $attendances->lastItem() ?? 0 }} of {{ $attendances->total() }} records
                </div>
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                <tr class="hover:bg-gray-50 transition-colors">
                    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-gray-400 mr-2"></i>
                            {{ $attendance->user->name }}
                        </div>
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $attendance->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $attendance->type == 'check_in' ? 'fa-sign-in-alt' : 'fa-sign-out-alt' }} mr-1"></i>
                            {{ $attendance->type == 'check_in' ? 'Check In' : 'Check Out' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ $attendance->attendance_time }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-network-wired mr-1"></i>
                        {{ $attendance->ip_address ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $attendance->notes ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() || auth()->user()->isEditor() ? 5 : 4 }}" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                            <p class="text-lg">No attendance records found.</p>
                            <p class="text-sm mt-1">Try adjusting your search or filter criteria.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $attendances->appends(request()->query())->links() }}
    </div>
</div>
@endsection