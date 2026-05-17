@extends('layouts.app')

@section('title', __('Visitor Details'))

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">{{ __('Visitor Details') }}</h3>
        <a href="{{ route('visitors.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            {{ __('Back to List') }}
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Name') }}</label>
                <p class="text-gray-900">{{ $visitor->name }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Email') }}</label>
                <p class="text-gray-900">{{ $visitor->email ?? '-' }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Phone') }}</label>
                <p class="text-gray-900">{{ $visitor->phone }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('ID Card Number') }}</label>
                <p class="text-gray-900">{{ $visitor->id_card_number ?? '-' }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Company') }}</label>
                <p class="text-gray-900">{{ $visitor->company ?? '-' }}</p>
            </div>
        </div>
        
        <div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Purpose') }}</label>
                <p class="text-gray-900">{{ $visitor->purpose }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Person to Meet') }}</label>
                <p class="text-gray-900">{{ $visitor->person_to_meet }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Check In Time') }}</label>
                <p class="text-gray-900">{{ $visitor->check_in_time->format('d/m/Y H:i:s') }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Check Out Time') }}</label>
                <p class="text-gray-900">{{ $visitor->check_out_time ? $visitor->check_out_time->format('d/m/Y H:i:s') : '-' }}</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Status') }}</label>
                <p class="text-gray-900">
                    <span class="px-2 py-1 text-xs rounded {{ $visitor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $visitor->status == 'active' ? __('Active') : __('Completed') }}
                    </span>
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Registered By') }}</label>
                <p class="text-gray-900">{{ $visitor->registrar->name }}</p>
            </div>
        </div>
    </div>
    
    @if(auth()->user()->access_level !== 4 && $visitor->status == 'active')
    <form method="POST" action="{{ route('visitors.checkout', $visitor) }}" class="inline">
        @csrf
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-sign-out-alt"></i> {{ __('Check Out') }}
        </button>
    </form>
    @endif
</div>
@endsection