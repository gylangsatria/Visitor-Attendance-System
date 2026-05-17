@extends('layouts.app')

@section('title', __('Login'))

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-indigo-600">VAS</h2>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">{{ __('Visitor & Attendance System') }}</p>
        </div>
        <h3 class="text-xl mb-6 text-center">{{ __('Login') }}</h3>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition" 
                       placeholder="{{ __('your@email.com') }}" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Password') }}</label>
                <input type="password" name="password" autocomplete="current-password"
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition" 
                       placeholder="••••••••" required>
            </div>
            
            <button type="submit" 
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold">
                {{ __('Login') }}
            </button>
        </form>
        
        <div class="mt-4 text-sm text-gray-600">
            <p class="text-center">{{ __('Demo Accounts:') }}</p>
            <ul class="text-xs mt-2 space-y-1">
                <li class="text-center">admin@vas.com / password123</li>
            </ul>
            <div class="mt-3 text-center border-t pt-3">
                <a href="{{ route('language.switch', ['locale' => app()->getLocale() === 'id' ? 'en' : 'id']) }}" class="text-indigo-600 hover:text-indigo-800 text-xs">
                    <i class="fas fa-globe"></i> {{ app()->getLocale() === 'id' ? __('English') : __('Indonesian') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection