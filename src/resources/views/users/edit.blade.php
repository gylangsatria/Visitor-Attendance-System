@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
@php
    $currentUser = auth()->user();
    
    // Cek akses: Editor tidak bisa edit Admin
    if ($currentUser->access_level === 2 && $user->access_level === 1) {
        echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Access Denied!</strong>
                <span class="block sm:inline ml-2">Editor cannot edit Admin users.</span>
              </div>';
        echo '<div class="mt-4 text-center">
                <a href="' . route('users.index') . '" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Back to Users
                </a>
              </div>';
        return;
    }
    
    // Editor tidak bisa edit user level 2 (Editor lain)
    if ($currentUser->access_level === 2 && $user->access_level === 2) {
        echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Access Denied!</strong>
                <span class="block sm:inline ml-2">Editor cannot edit other Editor users.</span>
              </div>';
        echo '<div class="mt-4 text-center">
                <a href="' . route('users.index') . '" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Back to Users
                </a>
              </div>';
        return;
    }
@endphp

<div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl mx-auto">
    <h3 class="text-lg font-semibold mb-4">Edit User: {{ $user->name }}</h3>
    
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password <span class="text-gray-500 text-xs font-normal">(Leave blank to keep current)</span></label>
            <input type="password" name="password" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters if changing</p>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Access Level *</label>
            <select name="access_level" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                @if($currentUser->access_level === 1)
                    <!-- Admin bisa pilih semua level termasuk Admin -->
                    <option value="1" {{ $user->access_level == 1 ? 'selected' : '' }}>Admin (Level 1)</option>
                    <option value="2" {{ $user->access_level == 2 ? 'selected' : '' }}>Editor (Level 2)</option>
                    <option value="3" {{ $user->access_level == 3 ? 'selected' : '' }}>Staf (Level 3)</option>
                    <option value="4" {{ $user->access_level == 4 ? 'selected' : '' }}>Attendee (Level 4)</option>
                @else
                    <!-- Editor hanya bisa set level 3-4 -->
                    <option value="3" {{ $user->access_level == 3 ? 'selected' : '' }}>Staf (Level 3)</option>
                    <option value="4" {{ $user->access_level == 4 ? 'selected' : '' }}>Attendee (Level 4)</option>
                @endif
            </select>
            @error('access_level')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            @error('phone')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
            <textarea name="address" class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="3">{{ old('address', $user->address) }}</textarea>
            @error('address')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex justify-end gap-2 mt-6">
            <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                Cancel
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                Update User
            </button>
        </div>
    </form>
</div>
@endsection