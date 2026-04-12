<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VAS - @yield('title', 'Visitor & Attendance System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Smooth transition untuk mobile menu */
        .mobile-menu {
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }
        .mobile-menu.open {
            transform: translateX(0);
        }
        @media (max-width: 768px) {
            .desktop-menu {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-indigo-600">VAS</h1>
                        <span class="ml-2 text-sm text-gray-500 hidden sm:inline-block">Visitor & Attendance System</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    @auth
                    <div class="flex space-x-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600 transition">
                            Dashboard
                        </a>
                        <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600 transition">
                            History Absensi
                        </a>
                        @if(auth()->user()->access_level !== 4)
                        <a href="{{ route('visitors.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600 transition">
                            Visitors
                        </a>
                        @endif
                        @if(auth()->user()->canEdit())
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600 transition">
                            Manajemen User
                        </a>
                        @endif
                    </div>
                    @endauth
                </div>

                <!-- Desktop User Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-user-tag"></i> {{ auth()->user()->access_level_name }}
                    </span>
                    <a href="{{ route('profile.show') }}" class="text-gray-700 hover:text-indigo-600 transition">
                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 transition">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                @auth
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-2" aria-label="Toggle menu">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        @auth
        <div id="mobile-menu" class="mobile-menu fixed top-0 left-0 h-full w-64 bg-white shadow-xl z-50 md:hidden">
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-indigo-600">Menu</h2>
                    <button id="close-menu-button" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fas fa-user-circle text-3xl text-gray-600"></i>
                    <div>
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->access_level_name }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    <a href="{{ route('dashboard') }}" class="block py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 transition">
                        <i class="fas fa-tachometer-alt w-6"></i> Dashboard
                    </a>
                    <a href="{{ route('attendances.index') }}" class="block py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 transition">
                        <i class="fas fa-history w-6"></i> History Absensi
                    </a>
                    @if(auth()->user()->access_level !== 4)
                    <a href="{{ route('visitors.index') }}" class="block py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 transition">
                        <i class="fas fa-users w-6"></i> Visitors
                    </a>
                    @endif
                    @if(auth()->user()->canEdit())
                    <a href="{{ route('users.index') }}" class="block py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 transition">
                        <i class="fas fa-user-cog w-6"></i> Manajemen User
                    </a>
                    @endif
                    <a href="{{ route('profile.show') }}" class="block py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded px-2 transition">
                        <i class="fas fa-user w-6"></i> Profile
                    </a>
                    <hr class="my-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded px-2 transition">
                            <i class="fas fa-sign-out-alt w-6"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Overlay untuk mobile menu -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
        @endauth
    </nav>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- JavaScript untuk mobile menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeMenuButton = document.getElementById('close-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-overlay');

            function openMenu() {
                mobileMenu.classList.add('open');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeMenu() {
                mobileMenu.classList.remove('open');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', openMenu);
            }

            if (closeMenuButton) {
                closeMenuButton.addEventListener('click', closeMenu);
            }

            if (overlay) {
                overlay.addEventListener('click', closeMenu);
            }

            // Close menu with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('open')) {
                    closeMenu();
                }
            });

            // Close menu on window resize if open
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768 && mobileMenu && mobileMenu.classList.contains('open')) {
                    closeMenu();
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>