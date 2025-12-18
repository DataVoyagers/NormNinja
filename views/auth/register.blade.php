<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NormNinja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-indigo-500 to-purple-600 min-h-screen flex items-center justify-center py-8">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <img src="/images/logo.png" alt="NormNinja Logo" class="h-20 w-auto mx-auto mb-4" onerror="this.style.display='none'">
            <h1 class="text-3xl font-bold text-gray-800">Create Account</h1>
            <p class="text-gray-600 mt-2">Join NormNinja LMS</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700 font-semibold mb-2">I am a...</label>
                <select id="role" name="role" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>

            <div class="mb-4" id="student-id-field">
                <label for="student_id" class="block text-gray-700 font-semibold mb-2">Student ID</label>
                <input type="text" id="student_id" name="student_id" value="{{ old('student_id') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold">
                Register
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">Already have an account? 
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Login here</a>
            </p>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const studentIdField = document.getElementById('student-id-field');
        
        function toggleStudentId() {
            if (roleSelect.value === 'student') {
                studentIdField.style.display = 'block';
                document.getElementById('student_id').required = true;
            } else {
                studentIdField.style.display = 'none';
                document.getElementById('student_id').required = false;
            }
        }
        
        roleSelect.addEventListener('change', toggleStudentId);
        toggleStudentId();
    </script>
</body>
</html>
