@extends('layouts.app')

@section('title', 'Attendance History')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Attendance History</h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                <tr>
                    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <td class="px-6 py-4">{{ $attendance->user->name }}</td>
                    @endif
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded {{ $attendance->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $attendance->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $attendance->attendance_time }}</td>
                    <td class="px-6 py-4">{{ $attendance->ip_address ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $attendance->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() || auth()->user()->isEditor() ? 5 : 4 }}" class="px-6 py-4 text-center text-gray-500">
                        No attendance records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $attendances->links() }}
    </div>
</div>
@endsection