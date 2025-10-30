<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white shadow rounded-2xl p-8">
            <h1 class="text-2xl font-bold mb-2 text-gray-800">Forgot your password?</h1>
            <p class="text-gray-600 mb-6">Enter your email and we'll send you a link to reset your password.</p>

            @if (session('status'))
                <div class="mb-4 p-3 text-sm rounded-lg bg-green-50 text-green-700 border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 text-sm rounded-lg bg-red-50 text-red-700 border border-red-200">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">Send reset link</button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-blue-700 hover:underline">Back to login</a>
            </div>
        </div>
    </div>
</body>
</html>
