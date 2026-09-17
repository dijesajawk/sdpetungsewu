<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentController;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/guru/{user}/verify', [AdminController::class, 'verifyGuru'])->name('admin.verify');
    Route::post('/guru/{user}/reject', [AdminController::class, 'rejectGuru'])->name('admin.reject');
});

// Guru Routes
Route::prefix('guru')->middleware(['auth', 'guru'])->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');
    
    // Soal Routes
    Route::get('/soal', [GuruController::class, 'listSoal'])->name('guru.soal.list');
    Route::get('/soal/create', [GuruController::class, 'createSoal'])->name('guru.soal.create');
    Route::post('/soal', [GuruController::class, 'storeSoal'])->name('guru.soal.store');
    Route::get('/soal/{soal}/edit', [GuruController::class, 'editSoal'])->name('guru.soal.edit');
    Route::put('/soal/{soal}', [GuruController::class, 'updateSoal'])->name('guru.soal.update');
    Route::delete('/soal/{soal}', [GuruController::class, 'deleteSoal'])->name('guru.soal.delete');
    
    // Quiz Routes (Multiple Soal)
    Route::get('/quiz/start', [GuruController::class, 'startQuiz'])->name('guru.quiz.start');
    Route::post('/quiz/create', [GuruController::class, 'createSession'])->name('guru.quiz.create');
    // Create immediate display (tampilkan soal langsung tanpa membuat session)
    Route::post('/quiz/create-immediate', [GuruController::class, 'createImmediate'])->name('guru.quiz.create_immediate');
    Route::get('/quiz/immediate', [GuruController::class, 'showImmediate'])->name('guru.quiz.immediate.show');
    Route::post('/quiz/immediate/answer', [GuruController::class, 'answerImmediate'])->name('guru.quiz.immediate.answer');
    Route::post('/quiz/immediate/exit', [GuruController::class, 'exitImmediate'])->name('guru.quiz.immediate.exit');
    Route::post('/quiz/immediate/reshuffle', [GuruController::class, 'reshuffleImmediate'])->name('guru.quiz.immediate.reshuffle');
    Route::get('/quiz/{session}/display', [GuruController::class, 'displaySession'])->name('guru.session.display');
    Route::get('/quiz/{session}/info', [GuruController::class, 'sessionInfo'])->name('guru.session.info');
    Route::post('/quiz/{session}/end', [GuruController::class, 'endSession'])->name('guru.session.end');
    
    // OCR Routes
    Route::get('/soal/scan/upload', [GuruController::class, 'showScanUpload'])->name('guru.soal.scan.upload');
    Route::post('/soal/scan/process', [GuruController::class, 'processScanImage'])->name('guru.soal.scan.process');
    Route::get('/soal/scan/review', [GuruController::class, 'showScanReview'])->name('guru.soal.scan.review');
    Route::post('/soal/scan/save', [GuruController::class, 'saveScanResult'])->name('guru.soal.scan.save');
    
    // Upload File Routes (PDF/Word)
    Route::get('/soal/upload/file', [GuruController::class, 'showUploadFile'])->name('guru.soal.upload.file');
    Route::post('/soal/upload/process', [GuruController::class, 'processUploadFile'])->name('guru.soal.upload.process');
    Route::get('/soal/upload/review', [GuruController::class, 'showUploadReview'])->name('guru.soal.upload.review');
    Route::post('/soal/upload/save', [GuruController::class, 'saveUploadResult'])->name('guru.soal.upload.save');
});

// Public Quiz Routes (Murid tanpa login)
Route::prefix('quiz')->group(function () {
    Route::get('/{sessionCode}/name', [QuizController::class, 'showNameInput'])->name('quiz.name-input');
    Route::get('/{sessionCode}', [QuizController::class, 'quiz'])->name('quiz.start');
    Route::post('/{sessionCode}/submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');
    Route::get('/{sessionCode}/result', [QuizController::class, 'result'])->name('quiz.result');
});

// Student Routes (Siswa)
Route::prefix('student')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    
    // Soal Routes
    Route::get('/soal', [StudentController::class, 'listSoal'])->name('student.soal.list');
    Route::get('/soal/{id}/quiz', [StudentController::class, 'quiz'])->name('student.soal.quiz');
    Route::post('/soal/{id}/submit', [StudentController::class, 'submitJawaban'])->name('student.soal.submit');
    Route::get('/result', [StudentController::class, 'result'])->name('student.result');
});

