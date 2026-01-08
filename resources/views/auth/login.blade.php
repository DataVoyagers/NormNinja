<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NormNinja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-indigo-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <img src="/images/logo.png" alt="NormNinja Logo" class="h-20 w-auto mx-auto mb-4" onerror="this.style.display='none'">
            <p class="text-gray-600 mt-2">Database Learning System</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4 flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-gray-700">Remember me</span>
                </label>
                <a href="#" onclick="showForgotPasswordModal(event)" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold">
                Login
            </button>
        </form>

        <!--<div class="mt-6 text-center">
            <p class="text-gray-600">Don't have an account? 
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Register here</a>
            </p>
        </div>-->
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md mx-4">
            <div class="text-center">
                <div class="mb-4">
                    <svg class="w-16 h-16 text-indigo-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Forgot Your Password?</h2>
                <p class="text-gray-600 mb-6">
                    Please contact your system administrator to reset your password.
                </p>
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Contact Information:</p>
                    <p class="text-gray-800">
                        <span class="font-semibold">Email:</span> admin@normninja.com
                    </p>
                    <p class="text-gray-800 mt-1">
                        <span class="font-semibold">Phone:</span> +60 12-345 6789
                    </p>
                </div>
                <button onclick="closeForgotPasswordModal()" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function showForgotPasswordModal(event) {
            event.preventDefault();
            document.getElementById('forgotPasswordModal').classList.remove('hidden');
        }

        function closeForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('forgotPasswordModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeForgotPasswordModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeForgotPasswordModal();
            }
        });
    </script>
</body>
</html>
