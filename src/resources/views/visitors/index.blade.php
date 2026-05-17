@extends('layouts.app')

@section('title', __('Visitors'))

@section('content')
<div class="bg-white rounded-lg shadow p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <h3 class="text-lg font-semibold">{{ __('Visitor List') }}</h3>
        
        <div class="flex items-center gap-2">
            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
            <a href="{{ route('visitors.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-1.5 rounded hover:bg-green-700 transition flex items-center gap-1.5 text-sm">
                <i class="fas fa-download text-xs"></i>
                <span>CSV</span>
            </a>
            @endif
            @if(auth()->user()->access_level !== 4)
            <a href="{{ route('visitors.create') }}" class="bg-indigo-600 text-white px-4 py-1.5 rounded hover:bg-indigo-700 transition flex items-center gap-1.5 text-sm">
                <i class="fas fa-plus text-xs"></i>
                <span>{{ __('Register Visitor') }}</span>
            </a>
            @endif
        </div>
    </div>
    
    <!-- Search and Filter Section -->
    <div class="mb-6">
        <form method="GET" action="{{ route('visitors.index') }}" class="space-y-3">
            <!-- Row 1: Search and Status Filter -->
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
                               placeholder="{{ __('Search by name, phone, or person to meet...') }}" 
                               class="w-full pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <!-- Filter by Status -->
                <div class="sm:w-36">
                    <select name="status" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
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
                    
                    <a href="{{ route('visitors.index') }}" class="px-4 py-1.5 bg-gray-500 text-white text-sm rounded hover:bg-gray-600 transition flex items-center gap-1.5">
                        <i class="fas fa-undo-alt text-xs"></i>
                        <span>{{ __('Reset') }}</span>
                    </a>
                </div>
            </div>
            
            <!-- Result Count -->
            <div class="text-xs text-gray-500 pt-1">
                <i class="fas fa-chart-line mr-1 text-xs"></i>
                {{ __('Showing') }} {{ $visitors->firstItem() ?? 0 }} - {{ $visitors->lastItem() ?? 0 }} {{ __('of') }} {{ $visitors->total() }} {{ __('records') }}
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Phone') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Person to Meet') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Check In') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($visitors as $visitor)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-gray-400 mr-2 text-xs"></i>
                            {{ $visitor->name }}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-phone mr-1 text-xs"></i>
                        {{ $visitor->phone }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-user-tie mr-1 text-xs"></i>
                        {{ $visitor->person_to_meet }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1 text-xs"></i>
                        {{ $visitor->check_in_time->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $visitor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fas {{ $visitor->status == 'active' ? 'fa-circle' : 'fa-check-circle' }} mr-1 text-xs"></i>
                            {{ $visitor->status == 'active' ? __('Active') : __('Completed') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('visitors.show', $visitor) }}" class="text-indigo-600 hover:text-indigo-900 transition" title="{{ __('View') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if(auth()->user()->access_level !== 4)
                                @if($visitor->status == 'active')
                                <form method="POST" action="{{ route('visitors.checkout', $visitor) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition" title="{{ __('Check Out') }}" onclick="return confirm('{{ __('Check Out') }}?')">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm">{{ __('No visitors found.') }}</p>
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
        {{ $visitors->appends(request()->query())->links() }}
    </div>
</div>
@endsection