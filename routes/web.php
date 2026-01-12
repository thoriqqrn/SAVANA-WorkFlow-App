<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes - All authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    
    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('settings', SettingController::class)->only(['index', 'update']);
        Route::resource('drives', DriveController::class)->except(['index', 'show']);
    });
    
    // Admin & BPH Routes
    Route::middleware('role:admin,bph')->group(function () {
        Route::resource('cabinets', CabinetController::class);
        Route::resource('departments', DepartmentController::class);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
        
        // Links CRUD (Admin & BPH)
        Route::resource('links', LinkController::class)->except(['index', 'show']);
    });
    
    // Admin, BPH & Kabinet Routes
    Route::middleware('role:admin,bph,kabinet')->group(function () {
        Route::resource('programs', ProgramController::class);
        Route::post('/programs/{program}/members', [ProgramController::class, 'addMember'])->name('programs.members.add');
        Route::delete('/programs/{program}/members/{user}', [ProgramController::class, 'removeMember'])->name('programs.members.remove');
        Route::post('/programs/{program}/pics', [ProgramController::class, 'addPic'])->name('programs.pics.add');
        Route::delete('/programs/{program}/pics/{user}', [ProgramController::class, 'removePic'])->name('programs.pics.remove');
        
        Route::resource('evaluations', EvaluationController::class);
    });
    
    // Staff can view programs they are member/PIC of
    Route::get('/my-programs', [ProgramController::class, 'myPrograms'])->name('programs.my');
    
    // All Authenticated Users - Timelines
    Route::get('/timelines', [TimelineController::class, 'index'])->name('timelines.index');
    Route::get('/timelines/calendar', [TimelineController::class, 'calendar'])->name('timelines.calendar');
    Route::get('/timelines/calendar-data', [TimelineController::class, 'calendarData'])->name('timelines.calendar.data');
    Route::get('/timelines/global', [TimelineController::class, 'global'])->name('timelines.global');
    Route::get('/timelines/department/{department?}', [TimelineController::class, 'department'])->name('timelines.department');
    Route::get('/timelines/program/{program}', [TimelineController::class, 'program'])->name('timelines.program');
    
    Route::middleware('role:admin,bph,kabinet')->group(function () {
        Route::resource('timelines', TimelineController::class)->except(['index', 'show']);
    });
    
    // Task Routes - CREATE must come BEFORE {task} parameter!
    Route::middleware('role:admin,bph,kabinet')->group(function () {
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });
    
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}/progress', [TaskController::class, 'updateProgress'])->name('tasks.progress');
    
    // All users can view Drive & Links
    Route::get('/drives', [DriveController::class, 'index'])->name('drives.index');
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    
    // Announcements - All users can create, only creator can delete
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/{announcement}/comment', [AnnouncementController::class, 'comment'])->name('announcements.comment');
    Route::post('/announcements/{announcement}/react', [AnnouncementController::class, 'react'])->name('announcements.react');
    Route::post('/announcements/{announcement}/vote', [AnnouncementController::class, 'vote'])->name('announcements.vote');
    
    // Messages - All users
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread');
    Route::get('/messages/conversation/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/send/{user}', [MessageController::class, 'send'])->name('messages.send');
    Route::post('/messages/read/{user}', [MessageController::class, 'markRead'])->name('messages.read');
    
    // Staff: view own evaluations
    Route::get('/my-evaluations', [EvaluationController::class, 'myEvaluations'])->name('evaluations.my');
});



