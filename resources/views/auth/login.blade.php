<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LBDAIRY - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
        }
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.97);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 40px 0 rgba(78, 115, 223, 0.18), 0 2px 8px 0 rgba(60, 90, 166, 0.10), 0 1.5px 8px 0 rgba(0,0,0,0.08);
        }
        .tab-active {
            background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.3);
        }
        .tab-inactive {
            background: rgba(255, 255, 255, 0.1);
            color: #6b7280;
            backdrop-filter: blur(8px);
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #3c5aa6 100%);
            transform: translateY(0);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(78, 115, 223, 0.4);
        }
        .hidden { display: none; }
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="min-h-screen" style="background-color: #fff;">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl">
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Side - Branding -->
                    <div class="lg:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 p-12 flex flex-col justify-center items-center text-white relative overflow-hidden">
                        <div class="absolute inset-0 bg-black opacity-20"></div>
                        <div class="relative z-10 text-center">
                            <h1 class="text-4xl font-bold mb-4">LBDAIRY</h1>
                            <p class="text-xl opacity-90 mb-8">Modern Dairy Management System</p>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="lg:w-1/2 p-12">
                        <div class="max-w-md mx-auto">
                            <div class="text-center mb-8">
                                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                                <p class="text-gray-600">Please sign in to your account</p>
                            </div>

                            <!-- User Type Tabs -->
                            <div class="flex rounded-2xl p-1 bg-gray-100 mb-8">
                                <button class="tab-btn flex-1 py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 tab-active" data-tab="farmer">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span>Farmer</span>
                                    </div>
                                </button>
                                <button class="tab-btn flex-1 py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 tab-inactive" data-tab="admin">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                                        </svg>
                                        <span>Admin</span>
                                    </div>
                                </button>
                                <button class="tab-btn flex-1 py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 tab-inactive" data-tab="superadmin">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M5 16L3 14l5.5-5.5L10 10l-1.5 1.5L5 16zm8.5-6.5L16 10l-2 2-1.5-1.5L15 8.5l-1.5-1.5zm-5 0L10 8l2 2-1.5 1.5L8 9.5 6.5 8l1.5-1.5z"/>
                                        </svg>
                                        <span>Super</span>
                                    </div>
                                </button>
                            </div>

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                                @csrf
                                <input type="hidden" name="role" id="selectedRole" value="farmer">
                                
                                <!-- Validation Errors -->
                                @if ($errors->any())
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <!-- Dynamic Login Form -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                        <input type="text" id="username" name="username" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                            placeholder="Enter your username">
                                    </div>
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                        <input type="password" id="password" name="password" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                            placeholder="Enter your password">
                                    </div>
                                </div>

                                <button type="submit" class="w-full btn-primary text-white font-semibold py-3 px-6 rounded-xl text-lg transition-all duration-300">
                                    Sign In
                                </button>
                            </form>

                            <div class="text-center mt-6">
                                <p class="text-gray-600">Don't have an account? 
                                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium">Register here</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const selectedRoleInput = document.getElementById('selectedRole');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tab = btn.dataset.tab;
                
                // Update tab buttons
                tabBtns.forEach(b => {
                    b.classList.remove('tab-active');
                    b.classList.add('tab-inactive');
                });
                btn.classList.remove('tab-inactive');
                btn.classList.add('tab-active');
                
                // Update hidden input
                selectedRoleInput.value = tab;
                
                // Update form placeholders based on role
                if (tab === 'farmer') {
                    usernameInput.placeholder = 'Enter your username (e.g., johnfarmer)';
                    passwordInput.placeholder = 'Enter your password';
                } else if (tab === 'admin') {
                    usernameInput.placeholder = 'Enter your username (e.g., admin)';
                    passwordInput.placeholder = 'Enter your password';
                } else if (tab === 'superadmin') {
                    usernameInput.placeholder = 'Enter your username (e.g., superadmin)';
                    passwordInput.placeholder = 'Enter your password';
                }
                
                // Clear form fields when switching tabs
                usernameInput.value = '';
                passwordInput.value = '';
            });
        });
    </script>
</body>
</html>
