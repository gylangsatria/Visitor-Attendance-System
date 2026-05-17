@if(auth()->user()->access_level !== 1)
    @php abort(403, 'Only Admin can create users.') @endphp
@endif

@extends('layouts.app')

@section('title', __('Add User'))

@section('content')
<div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl mx-auto">
    <h3 class="text-lg font-semibold mb-4">{{ __('Add New User') }}</h3>
    
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Name') }} *</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email') }} *</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Password') }} *</label>
            <input type="password" name="password" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            <p class="text-xs text-gray-500 mt-1">{{ __('Minimum 8 characters') }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Confirm Password') }} *</label>
            <input type="password" name="password_confirmation" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Access Level') }} *</label>
            <select name="access_level" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="1" {{ old('access_level') == '1' ? 'selected' : '' }}>{{ __('Admin') }} (Level 1)</option>
                <option value="2" {{ old('access_level') == '2' ? 'selected' : '' }}>{{ __('Editor') }} (Level 2)</option>
                <option value="3" {{ old('access_level') == '3' ? 'selected' : '' }}>{{ __('Staff') }} (Level 3)</option>
                <option value="4" {{ old('access_level') == '4' ? 'selected' : '' }}>{{ __('Attendee') }} (Level 4)</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone') }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Address') }}</label>
            <textarea name="address" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="3">{{ old('address') }}</textarea>
        </div>
        
        <div class="flex justify-end gap-2">
            <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                {{ __('Create User') }}
            </button>
        </div>
    </form>
</div>
@endsection