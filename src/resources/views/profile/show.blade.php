@extends('layouts.app')

@section('title', __('My Profile'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">{{ __('Profile Information') }}</h3>
        
        @if(auth()->user()->canEdit())
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Name') }}</label>
                <input type="text" name="name" value="{{ $user->name }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Phone') }}</label>
                <input type="text" name="phone" value="{{ $user->phone }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Address') }}</label>
                <textarea name="address" class="w-full px-3 py-2 border rounded-lg" rows="3">{{ $user->address }}</textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Avatar') }}</label>
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-full mb-2">
                @endif
                <input type="file" name="avatar" accept="image/*" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                {{ __('Update Profile') }}
            </button>
        </form>
        @else
        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Name') }}</label>
                <p class="text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Email') }}</label>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Access Level') }}</label>
                <p class="text-gray-900">{{ $user->access_level_name }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Phone') }}</label>
                <p class="text-gray-900">{{ $user->phone ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">{{ __('Address') }}</label>
                <p class="text-gray-900">{{ $user->address ?? '-' }}</p>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Change Password -->
    @if(auth()->user()->canEdit())
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">{{ __('Change Password') }}</h3>
        
        <form method="POST" action="{{ route('profile.update-password') }}">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Current Password') }}</label>
                <input type="password" name="current_password" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('New Password') }}</label>
                <input type="password" name="new_password" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Confirm New Password') }}</label>
                <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                {{ __('Change Password') }}
            </button>
        </form>
    </div>
    @endif
</div>
@endsection