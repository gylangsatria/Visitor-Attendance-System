@extends('layouts.app')

@section('title', __('Register Visitor'))

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <h3 class="text-lg font-semibold mb-4">{{ __('Register New Visitor') }}</h3>
    
    <form method="POST" action="{{ route('visitors.store') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Name') }} *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Phone') }} *</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('ID Card Number') }}</label>
            <input type="text" name="id_card_number" value="{{ old('id_card_number') }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Company') }}</label>
            <input type="text" name="company" value="{{ old('company') }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Purpose') }} *</label>
            <textarea name="purpose" class="w-full px-3 py-2 border rounded-lg" rows="3" required>{{ old('purpose') }}</textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Person to Meet') }} *</label>
            <input type="text" name="person_to_meet" value="{{ old('person_to_meet') }}" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="flex justify-end">
            <a href="{{ route('visitors.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                {{ __('Register Visitor') }}
            </button>
        </div>
    </form>
</div>
@endsection