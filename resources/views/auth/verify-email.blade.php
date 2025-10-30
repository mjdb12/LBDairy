<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white shadow rounded-2xl p-8">
            <h1 class="text-2xl font-bold mb-2 text-gray-800">Verify your email</h1>
            <p class="text-gray-600 mb-6">We sent a verification link to your email. Please click the link to continue.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-3 text-sm rounded-lg bg-green-50 text-green-700 border border-green-200">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <div class="space-y-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                        Resend verification email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-xl transition">
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
