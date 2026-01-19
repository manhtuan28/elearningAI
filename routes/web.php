<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearningController;

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckInstructor;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- KHU VỰC HỌC TẬP (LEARNING) ---
    Route::get('/courses/{id}/detail', [LearningController::class, 'detail'])
        ->name('learning.detail');

    Route::get('/learning/{id}/{lesson_id?}', [LearningController::class, 'show'])
        ->name('learning.course');

    Route::post('/learning/lessons/{lessonId}/submit', [LearningController::class, 'submitLesson'])
        ->name('learning.lesson.submit');

    Route::get('/instructor/lessons/{lessonId}/submissions', [\App\Http\Controllers\Instructor\CourseContentController::class, 'viewSubmissions'])
        ->name('instructor.lessons.submissions');

    Route::post('/user/heartbeat', [\App\Http\Controllers\ActivityController::class, 'heartbeat'])->name('user.heartbeat');
});

// --- KHU VỰC ADMIN ---
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

// --- KHU VỰC GIẢNG VIÊN ---
Route::middleware(['auth', CheckInstructor::class])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::post('/submissions/{id}/grade', [\App\Http\Controllers\Instructor\CourseContentController::class, 'gradeSubmission'])
        ->name('submissions.grade');
    Route::get('/courses', [\App\Http\Controllers\Instructor\CourseController::class, 'index'])->name('courses.index');

    Route::get('/courses/{id}/manage', [\App\Http\Controllers\Instructor\CourseContentController::class, 'index'])->name('courses.manage');
    Route::post('/courses/{id}/chapters', [\App\Http\Controllers\Instructor\CourseContentController::class, 'storeChapter'])->name('chapters.store');
    Route::get('/courses/{id}/students', [\App\Http\Controllers\Instructor\CourseController::class, 'students'])->name('courses.students');


    Route::post('/chapters/{chapter_id}/lessons', [\App\Http\Controllers\Instructor\CourseContentController::class, 'storeLesson'])->name('lessons.store');

    Route::put('/chapters/{chapter}', [\App\Http\Controllers\Instructor\CourseContentController::class, 'updateChapter'])->name('chapters.update');
    Route::delete('/chapters/{chapter}', [\App\Http\Controllers\Instructor\CourseContentController::class, 'destroyChapter'])->name('chapters.destroy');
    Route::put('/lessons/{lesson}', [\App\Http\Controllers\Instructor\CourseContentController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [\App\Http\Controllers\Instructor\CourseContentController::class, 'destroyLesson'])->name('lessons.destroy');

    Route::post('/courses/{id}/import', [\App\Http\Controllers\Instructor\CourseContentController::class, 'importContent'])->name('courses.import');
    Route::get('/courses/import/template', [\App\Http\Controllers\Instructor\CourseContentController::class, 'downloadTemplate'])->name('courses.import_template');
});

require __DIR__ . '/auth.php';
