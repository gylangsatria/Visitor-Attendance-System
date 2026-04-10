@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <h3 class="text-lg font-semibold mb-4">Add New User</h3>
    
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password *</label>
            <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Access Level *</label>
            <select name="access_level" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="2">Editor (Level 2)</option>
                <option value="3">Viewer (Level 3)</option>
                <option value="4">Guest (Level 4)</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
            <textarea name="address" class="w-full px-3 py-2 border rounded-lg" rows="3">{{ old('address') }}</textarea>
        </div>
        
        <div class="flex justify-end">
            <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                Cancel
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Create User
            </button>
        </div>
    </form>
</div>
@endsection