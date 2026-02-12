<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobAds Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>

    <script>
        window.onload = function() {
            @if(session('error')) alert("{{ session('error') }}"); @endif
            @if(session('success')) alert("{{ session('success') }}"); @endif
        }
    </script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    
    <nav class="bg-indigo-700 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 text-2xl font-bold tracking-wide hover:text-indigo-200 transition">
                <i class="fa-solid fa-briefcase"></i>
                <span>JobAds<span class="text-indigo-300">Portal</span></span>
            </a>

            <div class="hidden md:flex items-center space-x-6 font-medium">
                <a href="{{ route('home') }}" class="hover:text-indigo-200 transition"><i class="fa-solid fa-house mr-1"></i> Home</a>
                
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.packages.create') }}" class="hover:text-indigo-200 transition">
                            <i class="fa-solid fa-plus-circle mr-1"></i> Add Package
                        </a>
                        <a href="{{ route('admin.ads.index') }}" class="hover:text-indigo-200 transition">
                            <i class="fa-solid fa-rectangle-list mr-1"></i> Pending Ads
                        </a>
                        
                        <a href="{{ route('admin.notifications') }}" class="relative hover:text-indigo-200 transition">
                            <i class="fa-solid fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        </a>

                    @else
                        <a href="{{ route('user.my_packages') }}" class="hover:text-indigo-200 transition">
                            <i class="fa-solid fa-box-open mr-1"></i> My Packages
                        </a>

                        <a href="{{ route('user.notifications') }}" class="relative hover:text-indigo-200 transition ml-2">
                             <i class="fa-solid fa-bell text-xl"></i>
                             <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        </a>
                    @endif
                    
                    <div class="relative group ml-4">
                        <a href="{{ route('profile') }}" class="flex items-center space-x-2 hover:text-indigo-200 transition">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/'.Auth::user()->profile_photo) }}" class="w-8 h-8 rounded-full border-2 border-white object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center border-2 border-white">
                                    <i class="fa-solid fa-user text-xs"></i>
                                </div>
                            @endif
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="ml-4 text-red-200 hover:text-red-100 transition" title="Logout">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-indigo-200 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-5 py-2 rounded-full font-bold shadow hover:bg-indigo-50 transition">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="flex-grow container mx-auto px-6 py-8">
        @yield('content')
    </div>

    <footer class="bg-gray-800 text-gray-400 py-8 mt-auto">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} JobAds Portal. All rights reserved.</p>
            <div class="mt-4 space-x-4">
                <a href="#" class="hover:text-white transition"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>