<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VAS - @yield('title', 'Visitor & Attendance System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-indigo-600">VAS</h1>
                        <span class="ml-2 text-sm text-gray-500">Visitor & Attendance System</span>
                    </div>
                    @auth
                    <div class="ml-6 flex space-x-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600">Dashboard</a>
                        <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600">History Absensi</a>
                        @if(auth()->user()->access_level !== 4)
                        <a href="{{ route('visitors.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600">Visitors</a>
                        @endif
                        @if(auth()->user()->canEdit())
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600">Manajemen User</a>
                        @endif
                    </div>
                    @endauth
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-user-tag"></i> {{ auth()->user()->access_level_name }}
                    </span>
                    <a href="{{ route('profile.show') }}" class="text-gray-700 hover:text-indigo-600">
                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>