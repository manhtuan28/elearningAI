<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckInstructor;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $activeCourses = \App\Models\Course::with('user')
            ->where('status', 'open')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('activeCourses'));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', CheckAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminCourseController::class, 'dashboard'])->name('dashboard');

    Route::get('users/import/template', [\App\Http\Controllers\Admin\UserController::class, 'downloadTemplate'])->name('users.import.template');
    Route::get('users/import', [\App\Http\Controllers\Admin\UserController::class, 'import'])->name('users.import');
    Route::post('users/import', [\App\Http\Controllers\Admin\UserController::class, 'storeImport'])->name('users.import.store');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    Route::resource('majors', \App\Http\Controllers\Admin\MajorController::class);

    Route::resource('classrooms', \App\Http\Controllers\Admin\ClassroomController::class);
    Route::prefix('classrooms')->name('classrooms.')->group(function () {
        Route::post('/{id}/add-student', [\App\Http\Controllers\Admin\ClassroomController::class, 'addStudent'])->name('add_student');
        Route::delete('/{id}/remove-student/{student_id}', [\App\Http\Controllers\Admin\ClassroomController::class, 'removeStudent'])->name('remove_student');
        Route::post('/{id}/import-students', [\App\Http\Controllers\Admin\ClassroomController::class, 'importStudents'])->name('import_students');
    });

    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [AdminCourseController::class, 'index'])->name('index');
        Route::get('/create', [AdminCourseController::class, 'create'])->name('create');
        Route::post('/', [AdminCourseController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminCourseController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [AdminCourseController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminCourseController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', CheckInstructor::class])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/', [InstructorDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';
