<?php

use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LearningMaterialController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ForumController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'teacher' => redirect('/teacher/dashboard'),
            'student' => redirect('/student/dashboard'),
            default => redirect('/login'),
        };
    }
    return redirect('/login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'showProfile'])->name('profile');

        // Student management
        Route::get('/students', [AdminController::class, 'students'])->name('students.index');
        Route::get('/students/create', [AdminController::class, 'createStudent'])->name('students.create');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{student}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{student}', [AdminController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{student}', [AdminController::class, 'deleteStudent'])->name('students.delete');

        // Teacher management
        Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers.index');
        Route::get('/teachers/create', [AdminController::class, 'createTeacher'])->name('teachers.create');
        Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('teachers.store');
        Route::get('/teachers/{teacher}/edit', [AdminController::class, 'editTeacher'])->name('teachers.edit');
        Route::put('/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [AdminController::class, 'deleteTeacher'])->name('teachers.delete');
    });

    // Teacher routes
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/student-performance', [TeacherController::class, 'studentPerformance'])->name('student-performance');
        Route::get('/students/{student}', [TeacherController::class, 'studentDetail'])->name('student.detail');
        Route::get('/profile', [TeacherController::class, 'showProfile'])->name('profile');
        Route::get('/profile/edit', [TeacherController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [TeacherController::class, 'updateProfile'])->name('profile.update');
    });

    // Student routes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

        Route::get('/calendar', [StudentController::class, 'calendarIndex'])->name('calendar.index');
        Route::post('/calendar/store', [StudentController::class, 'calendarStore'])->name('calendar.store');
        Route::put('/calendar/{event}', [StudentController::class, 'calendarUpdate'])->name('calendar.update');
        Route::delete('/calendar/{event}', [StudentController::class, 'calendarDelete'])->name('calendar.delete');

        Route::post('/reminders/store', [StudentController::class, 'reminderStore'])->name('reminders.store');
        Route::put('/reminders/{reminder}', [StudentController::class, 'reminderUpdate'])->name('reminders.update');
        Route::delete('/reminders/{reminder}', [StudentController::class, 'reminderDelete'])->name('reminders.delete');

        Route::get('/profile', [StudentController::class, 'showProfile'])->name('profile');
        Route::get('/profile/edit', [StudentController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    });

    // Calendar Events
    Route::middleware(['auth'])->group(function () {
        Route::get('/calendar-events', [CalendarEventController::class, 'index']);
        Route::post('/calendar-events', [CalendarEventController::class, 'store']);
        Route::put('/calendar-events/{event}', [CalendarEventController::class, 'update']);
        Route::delete('/calendar-events/{event}', [CalendarEventController::class, 'destroy']);
});

    // Learning Materials (accessible by teachers and students)
    Route::resource('learning-materials', LearningMaterialController::class);

    // Quizzes
    Route::middleware(['auth', 'role:teacher'])->group(function () {
        Route::resource('quizzes', QuizController::class)->except(['show', 'index']);
        Route::get('/quizzes/{quiz}/statistics', [QuizController::class, 'statistics'])->name('quizzes.statistics');
});

    Route::resource('quizzes', QuizController::class);
    Route::get('quizzes/{quiz}/start', [QuizController::class, 'start'])->name('quizzes.start');
    Route::get('quizzes/{quiz}/take/{attempt}', [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('quizzes/{quiz}/submit/{attempt}', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('quizzes/{quiz}/result/{attempt}', [QuizController::class, 'result'])->name('quizzes.result');
    
    // Quiz Questions
    Route::prefix('quizzes/{quiz}/questions')->name('quizzes.questions.')->group(function () {
        Route::get('/', [QuizQuestionController::class, 'index'])->name('index');
        Route::get('/create', [QuizQuestionController::class, 'create'])->name('create');
        Route::post('/', [QuizQuestionController::class, 'store'])->name('store');
        Route::get('/{question}/edit', [QuizQuestionController::class, 'edit'])->name('edit');
        Route::put('/{question}', [QuizQuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuizQuestionController::class, 'destroy'])->name('destroy');
    });

    Route::middleware(['auth', 'role:teacher'])->group(function () {
        Route::resource('games', GameController::class)->except(['show', 'index']);
        Route::get('games/{game}/statistics', [GameController::class, 'statistics'])->name('games.statistics');
});

    Route::middleware(['auth'])->group(function () {
        Route::get('games', [GameController::class, 'index'])->name('games.index');
        Route::get('games/leaderboard', [GameController::class, 'leaderboard'])->name('games.leaderboard');
        Route::get('games/{game}', [GameController::class, 'show'])->name('games.show');
        Route::get('games/{game}/play', [GameController::class, 'play'])->name('games.play');
        Route::get('games/{game}/leaderboard', [GameController::class, 'leaderboard'])->name('games.leaderboard.game');
        Route::post('games/{game}/submit', [GameController::class, 'submitAttempt'])->name('games.submit');
        Route::get('game-attempts/{attempt}/results', [GameController::class, 'results'])->name('games.results');
});

    // Games
    Route::resource('games', GameController::class);
    Route::get('games/{game}/play', [GameController::class, 'play'])->name('games.play');
    Route::post('games/{game}/save-attempt', [GameController::class, 'saveAttempt'])->name('games.save-attempt');

    // Forums
    Route::get('forums', [ForumController::class, 'index'])->name('forums.index');
    Route::get('forums/create', [ForumController::class, 'create'])->name('forums.create');
    Route::post('forums', [ForumController::class, 'store'])->name('forums.store');
    Route::get('forums/{forum}', [ForumController::class, 'show'])->name('forums.show');
    Route::get('forums/{forum}/edit', [ForumController::class, 'edit'])->name('forums.edit');
    Route::put('forums/{forum}', [ForumController::class, 'update'])->name('forums.update');
    Route::delete('forums/{forum}', [ForumController::class, 'destroy'])->name('forums.destroy');

    Route::resource('forums', ForumController::class);
    Route::post('forums/{forum}/posts', [ForumController::class, 'storePost'])->name('forums.posts.store');
    Route::put('forums/{forum}/posts/{post}', [ForumController::class, 'updatePost'])->name('forums.posts.update'); // ADD THIS LINE
    Route::delete('forums/{forum}/posts/{post}', [ForumController::class, 'deletePost'])->name('forums.posts.destroy');
});