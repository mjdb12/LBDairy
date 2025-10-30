<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LBDAIRY - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-gradient-primary {
            background: #18375d;
        }
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.97);
            border: 1px solid rgba(24, 55, 93, 0.2);
            box-shadow: 0 8px 40px 0 rgba(24, 55, 93, 0.18), 0 2px 8px 0 rgba(18, 42, 71, 0.10), 0 1.5px 8px 0 rgba(0,0,0,0.08);
        }
        .tab-active {
            background: #387057;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(24, 55, 93, 0.3);
        }
        .tab-inactive {
            background: rgba(255, 255, 255, 0.1);
            color: #6b7280;
            backdrop-filter: blur(8px);
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(24, 55, 93, 0.15);
            border-color: #d4a574;
        }
        .btn-primary {
            background: #18375d;
            transform: translateY(0);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-primary:hover {
            background-color: #fca700;
            color: #ffffffff;
            border-color: #fca700;
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(24, 55, 93, 0.4);
        }
        .hidden { display: none; }
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="min-h-screen" style="background: #ffffff;">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-5xl">
            <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Side - Branding (Dark Blue) -->
                    <div class="lg:w-1/2 p-12 flex flex-col justify-center items-center text-white relative overflow-hidden" style="background: #18375d;">
                        <div class="logo-container">
                                <img src="{{ asset('img/lucban.png') }}" alt="LBDAIRY Logo" style="width: 200px; height: 200px;">
                            </div>
                        <div class="absolute inset-0 bg-black opacity-20"></div>
                        <div class="relative z-10 text-center">
                            <h1 class="text-4xl font-bold mb-4">LBDAIRY</h1>
                            <p class="text-xl opacity-90 mb-8">Smart Livestock Management</p>
                        </div>
                    </div>

                    <!-- Right Side - Login Form (White) -->
                    <div class="lg:w-1/2 p-12" style="background: #ffffff;">
                        <div class="max-w-md mx-auto">
                            @if (session('success'))
                                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="text-center mb-8">
                                <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome back</h2>
                                <p class="text-gray-600">Log in to your account</p>
                            </div>

                            <!-- User Type Tabs -->
                            <div class="flex rounded-2xl p-1.5 bg-gray-100 w-full">
                                <button class="tab-btn flex-1 py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 tab-active" data-tab="farmer">
                                    <div class="flex items-center justify-center space-x-2">
                                        <span>Farmer</span>
                                    </div>
                                </button>
                                <button class="tab-btn flex-1 py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 tab-inactive" data-tab="admin">
                                    <div class="flex items-center justify-center space-x-2">
                                        <span>Admin</span>
                                    </div>
                                </button>
                                <button class="tab-btn flex-1 py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 tab-inactive" data-tab="superadmin">
                                    <div class="flex items-center justify-center space-x-2">
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
                                        <div class="relative">
                                            <input type="password" id="password" name="password" required
                                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your password">
                                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password')">
                                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors duration-200" id="passwordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <a href="{{ route('password.request') }}" class="text-sm font-medium" style="color: #18375d;">Forgot your password?</a>
                                    </div>
                                </div>

                                <button type="submit"  class="w-full btn-primary text-white font-semibold py-3 px-6 rounded-xl text-lg transition-all duration-300">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                                </button>
                            </form>

                            <div class="text-center mt-6">
                            <a class="small" href="{{ route('register') }}"></a>
                                <p class="text-gray-600">Don't have an account? 
                                    <a href="{{ route('register') }}" id="sign-in" class="font-medium" style="color: #18375d;">Register here</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
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
