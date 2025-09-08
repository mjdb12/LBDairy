<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LB Dairy - Smart Livestock Management System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <style>
        .hero-gradient {
            background: #18375d;
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        a#manage-dairy-link:hover {
            background-color: #387057;
            color: #ffffffff;
            border-color: #387057;
        }
        a#landing-page:hover {
            background-color: #fca700;
            color: #ffffffff;
            border-color: #fca700;
        }
        a#login-btn:hover {
            background-color: #f6f4e8;
            color: #18375d;
            border-color: #18375d;
        }
        a#features:hover {
            background-color: #18375d;
            color: #ffffffff;
            border-color: #18375d;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .stats-card {
            background: #18375d;
        }
        .stats-cards {
            background: #fca700;
        }
        .stats-cardss {
            background: #39a400;
        }
        .cta-gradient {
            background: #18375d;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 w-full z-50" style="background-color: #f6f4e8;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center space-x-4" style="align-items: flex-start;">
                        <img src="/img/LBDairy.png" alt="LB Dairy Logo" class="h-12 w-auto" style="max-height: 48px; object-fit: contain;">
                        <img src="/img/LBDairy_Rectangle.png" alt="LB Dairy Logo" class="h-12 w-auto" style="max-height: 48px; object-fit: contain;">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-900 hover:bg-blue-950 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out shadow-md" style="background: #18375d !important;">
                            Dashboard
                        </a>
                    @else
                         <a href="{{ url('/login') }}" id="login-btn" class="bg-#f6f4e8 text-blue-900 border-2 border-transparent hover:border-blue-900 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out !important;">
                            Sign In
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" id="manage-dairy-link" class="bg-#f6f4e8 text-blue-900 border-2 border-transparent hover:border-blue-900 px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out !important;">
                                Sign Up
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white" style="background: url('/img/cow.jpg') no-repeat center center; background-size: cover; padding: 200px 0;">
        <div class="flex items-center justify-center w-full"></div>
            <div class="flex flex-col sm:flex-row items-center px-4 sm:px-6 lg:px-8">
                <div class="text-left">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6" style="text-shadow: 2px 2px 4px rgba(0, 7, 20, 0.92);">
                        Smart Livestock Management
                        <span class="block" style="color: #fca700; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                            for Modern Dairy Farms
                        </span>
                    </h1>

                    <p class="text-xl md:text-2xl mb-8 max-w-3xl" style="color: #ffffff; text-shadow: 1px 1px 2px rgba(0,0,0,0.6);">
                        Make managing your dairy farm easier. Keep track of your animals, monitor their health, and improve farm productivity.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-white text-blue-900 hover:bg-gray-50 px-8 py-3 rounded-lg text-lg font-semibold transition duration-150 ease-in-out shadow-lg" style="color: #18375d !important;">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="#features" id="landing-page" class="border-2 border-white text-white hover:bg-white hover:text-blue-900 px-8 py-3 rounded-lg text-lg font-semibold transition duration-150 ease-in-out" style="color: white !important;">
                                Learn More
                            </a>
                            <a href="#contact" id="features" class="border-2 border-white text-white hover:bg-white hover:text-blue-900 px-8 py-3 rounded-lg text-lg font-semibold transition duration-150 ease-in-out" style="color: white !important;">
                                Join Us
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <!-- Stats Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="stats-card rounded-lg p-6 text-white mb-4">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $activeFarms ?? 0 }}+</h3>
                    <p class="text-gray-600">Active Farms</p>
                </div>
                <div class="text-center">
                    <div class="stats-cards rounded-lg p-6 text-white mb-4">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $totalLivestock ?? 0 }}+</h3>
                    <p class="text-gray-600">Livestock Tracked</p>
                </div>
                <div class="text-center">
                    <div class="stats-cardss rounded-lg p-6 text-white mb-4">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $uptime ?? 99.5 }}%</h3>
                    <p class="text-gray-600">Uptime</p>
                </div>
            </div>
        </div>
        <br><br><br>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" color: #1b3043 !important;">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Everything You Need to Manage Your Dairy Farm
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From livestock tracking to production monitoring, our comprehensive platform 
                    provides all the tools you need for efficient farm management.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Livestock Management -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4" style="background: rgba(24, 55, 93, 0.1) !important;">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #18375d !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Livestock Management</h3>
                    <p class="text-gray-600">
                        Manage animal records, monitor health, track breeding, and measure performance in a single system.
                    </p>
                </div>

                <!-- Production Tracking -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4" style="background: rgba(24, 55, 93, 0.1) !important;">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #18375d !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Production Tracking</h3>
                    <p class="text-gray-600">
                        Keep track of daily milk output, quality, and yield to run your dairy more efficiently.
                    </p>
                </div>

                <!-- Health Monitoring -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4" style="background: rgba(24, 55, 93, 0.1) !important;">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #18375d !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Health Monitoring</h3>
                    <p class="text-gray-600">
                        Manage livestock health by recording vaccinations, treatments, and health alerts to keep animals well.
                    </p>
                </div>

                <!-- Financial Management -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4" style="background: rgba(24, 55, 93, 0.1) !important;">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #18375d !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Financial Management</h3>
                    <p class="text-gray-600">
                        Track farm finances including expenses, sales, and revenue to improve profitability.
                    </p>
                </div>

                <!-- Issue Management -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4" style="background: rgba(24, 55, 93, 0.1) !important;">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #18375d !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Issue Management</h3>
                    <p class="text-gray-600">
                        Handle issues effectively by logging problems, receiving alerts, and resolving concerns quickly.
                    </p>
                </div>

                <!-- Analytics & Reports -->
                <div class="feature-card bg-white rounded-lg p-6 shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4" style="background: rgba(24, 55, 93, 0.1) !important;">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #18375d !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Analytics & Reports</h3>
                    <p class="text-gray-600">
                        Use data and analytics to generate reports that support smarter, data-driven decisions.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="cta-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Ready to Take Your Dairy Farm to the Next Level?
            </h2>
            <p class="text-xl mb-8 text-blue-100 max-w-2xl mx-auto">
                Make Your Dairy Farm Smarter, Stronger, Better. Join hundreds of farmers who have already improved their operations with LB Dairy.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                        <a href="{{ url('/dashboard') }}" class="bg-white text-blue-900 hover:bg-gray-50 px-8 py-3 rounded-lg text-lg font-semibold transition duration-150 ease-in-out shadow-lg" style="color: #18375d !important;">
                            Access Dashboard
                        </a>
                @else
                        <a href="{{ route('register') }}" id="landing-page" class="bg-white text-blue-900 hover:bg-gray-50 px-8 py-3 rounded-lg text-lg font-semibold transition duration-150 ease-in-out shadow-lg" style="color: #18375d !important;">
                            Join Now
                        </a>
                        <a href="{{ route('login') }}" id="landing-page" class="border-2 border-white text-white hover:bg-white hover:text-blue-900 px-8 py-3 rounded-lg text-lg font-semibold transition duration-150 ease-in-out" style="color: white !important;">
                            Sign In
                        </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="mb-4">
                        <img src="/img/LBDairy.png" alt="LB Dairy Logo" class="h-10 w-auto" onerror="console.log('Footer logo failed to load');">
                    </div>
                    <p class="text-gray-400">
                        Empowering dairy farmers with smart technology for better livestock management and increased productivity.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Features</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition duration-150 ease-in-out">Livestock Management</a></li>
                        <li><a href="#features" class="hover:text-white transition duration-150 ease-in-out">Production Tracking</a></li>
                        <li><a href="#features" class="hover:text-white transition duration-150 ease-in-out">Health Monitoring</a></li>
                        <li><a href="#features" class="hover:text-white transition duration-150 ease-in-out">Financial Reports</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out">Training</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition duration-150 ease-in-out">Careers</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} LB Dairy. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
