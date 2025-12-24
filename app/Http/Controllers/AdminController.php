<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'active_students' => User::where('role', 'student')->where('is_active', true)->count(),
            'active_teachers' => User::where('role', 'teacher')->where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
    public function showProfile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    // Student Management
    public function students()
    {
        $students = User::where('role', 'student')->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function createStudent()
    {
        return view('admin.students.create');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'student_id' => 'required|string|unique:users',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'is_active' => true,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function editStudent(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        return view('admin.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'student_id' => 'required|string|unique:users,student_id,' . $student->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $student->update($request->only([
            'name', 'email', 'student_id', 'phone', 'address', 'date_of_birth', 'is_active'
        ]));

        if ($request->filled('password')) {
            $student->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function deleteStudent(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    // Teacher Management
    public function teachers()
    {
        $teachers = User::where('role', 'teacher')->paginate(20);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function createTeacher()
    {
        return view('admin.teachers.create');
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'teacher_id' => 'required|string|unique:users',
            'phone' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'teacher_id' => $request->teacher_id,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        User::create($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully.');
    }

    public function editTeacher(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function updateTeacher(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'teacher_id' => 'required|string|unique:users,teacher_id,' . $teacher->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $teacher->update($request->only([
            'name', 'email', 'teacher_id', 'phone', 'address', 'date_of_birth', 'is_active'
        ]));

        if ($request->filled('password')) {
            $teacher->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function deleteTeacher(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }
        
        // Delete profile picture if exists
        if ($teacher->profile_picture) {
            Storage::disk('public')->delete($teacher->profile_picture);
        }
        
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}