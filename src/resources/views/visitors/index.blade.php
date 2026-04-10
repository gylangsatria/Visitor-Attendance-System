@extends('layouts.app')

@section('title', 'Visitors')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Visitor List</h3>
        <a href="{{ route('visitors.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            <i class="fas fa-plus"></i> Register Visitor
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Person to Meet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($visitors as $visitor)
                <tr>
                    <td class="px-6 py-4">{{ $visitor->name }}</td>
                    <td class="px-6 py-4">{{ $visitor->phone }}</td>
                    <td class="px-6 py-4">{{ $visitor->person_to_meet }}</td>
                    <td class="px-6 py-4">{{ $visitor->check_in_time->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded {{ $visitor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $visitor->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('visitors.show', $visitor) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-eye"></i> View
                        </a>
                        @if($visitor->status == 'active')
                        <form method="POST" action="{{ route('visitors.checkout', $visitor) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-sign-out-alt"></i> Check Out
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No visitors found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $visitors->links() }}
    </div>
</div>
@endsection