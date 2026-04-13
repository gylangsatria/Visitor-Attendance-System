@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="bg-white rounded-lg shadow p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <h3 class="text-lg font-semibold">User List</h3>
        @if(auth()->user()->access_level === 1)
        <a href="{{ route('users.create') }}" class="bg-indigo-600 text-white px-4 py-1.5 rounded hover:bg-indigo-700 transition flex items-center gap-1.5 text-sm">
            <i class="fas fa-plus text-xs"></i>
            <span>Add User</span>
        </a>
        @endif
    </div>
    
    <!-- Search and Filter Section -->
    <div class="mb-6">
        <form method="GET" action="{{ route('users.index') }}" class="space-y-3">
            <!-- Row 1: Search and Access Level Filter -->
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
                               placeholder="Search by name, email, or phone..." 
                               class="w-full pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <!-- Filter by Access Level -->
                <div class="sm:w-44">
                    <select name="access_level" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Access Levels</option>
                        <option value="1" {{ request('access_level') == '1' ? 'selected' : '' }}>Admin (Level 1)</option>
                        <option value="2" {{ request('access_level') == '2' ? 'selected' : '' }}>Editor (Level 2)</option>
                        <option value="3" {{ request('access_level') == '3' ? 'selected' : '' }}>Staf (Level 3)</option>
                        <option value="4" {{ request('access_level') == '4' ? 'selected' : '' }}>Attendee (Level 4)</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-2 sm:ml-auto">
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition flex items-center gap-1.5">
                        <i class="fas fa-filter text-xs"></i>
                        <span>Filter</span>
                    </button>
                    
                    <a href="{{ route('users.index') }}" class="px-4 py-1.5 bg-gray-500 text-white text-sm rounded hover:bg-gray-600 transition flex items-center gap-1.5">
                        <i class="fas fa-undo-alt text-xs"></i>
                        <span>Reset</span>
                    </a>
                </div>
            </div>
            
            <!-- Result Count -->
            <div class="text-xs text-gray-500 pt-1">
                <i class="fas fa-chart-line mr-1 text-xs"></i>
                Showing {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Access Level</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-gray-400 mr-2 text-xs"></i>
                            {{ $user->name }}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-envelope mr-1 text-xs"></i>
                        {{ $user->email }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                            @if($user->access_level == 1) bg-red-100 text-red-800
                            @elseif($user->access_level == 2) bg-yellow-100 text-yellow-800
                            @elseif($user->access_level == 3) bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <i class="fas 
                                @if($user->access_level == 1) fa-crown
                                @elseif($user->access_level == 2) fa-edit
                                @elseif($user->access_level == 3) fa-eye
                                @else fa-user @endif mr-1 text-xs"></i>
                            {{ $user->access_level_name }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-phone mr-1 text-xs"></i>
                        {{ $user->phone ?? '-' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        <div class="flex items-center gap-2">
                            @if(auth()->user()->access_level === 1)
                                <!-- Admin: bisa edit semua user -->
                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->id() != $user->id)
                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            @elseif(auth()->user()->access_level === 2 && $user->access_level > 2)
                                <!-- Editor: hanya bisa edit user level 3 dan 4 -->
                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @elseif(auth()->user()->access_level === 2 && $user->access_level <= 2)
                                <!-- Editor: tidak bisa edit admin atau editor lain -->
                                <span class="text-gray-400 text-xs" title="No access">
                                    <i class="fas fa-lock"></i>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection