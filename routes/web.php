<?php

use App\Http\Controllers\Api\StepController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CuratorStepController;
use App\Http\Controllers\LearnerController;
use App\Http\Controllers\CuratorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DemoDropController;

// Numatytoji Laravel Breeze sugeneruota pagrindinio puslapio nuoroda

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Numatytosios Laravel Breeze sugeneruotos profilio redagavimo nuorodos

Route::middleware('auth')->group(function () {
    Route::prefix('profilis')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /* 
    // Valdymo skydo generavimas pagal rolę
    // hasRole metodas gali atvaizduoti save kaip klaidą VS Code aplinkoje
    // Klaidos čia nėra, kadangi naudojamas Laravel skirtas Spatie rolių ir leidimų paketas
    */

    Route::middleware(['verified'])->group(function () {
        Route::get('/valdymas', function () {
            $user = auth()->user();
            if ($user->hasRole('curator')) {
                return Inertia::render('CuratorDashboard');
            } else {
                return Inertia::render('LearnerDashboard');
            }
        })->name('dashboard');

        // Kuratoriaus nuorodos / keliai (routes)

        Route::middleware(['auth', 'role:curator'])->group(function () {
            Route::get('/kuratorius/kursai', [CuratorController::class, 'index'])->name('courses.curator.index');
            Route::get('/kuratorius/kursai/sukurti', [CuratorController::class, 'create'])->name('courses.create');
            Route::get('/kuratorius/kursai/{course}/perziura', [CuratorController::class, 'show'])->name('courses.show');
            Route::post('/kuratorius/kursai', [CuratorController::class, 'store'])->name('courses.store');
            Route::get('/kuratorius/kursai/{course}/redaguoti', [CuratorController::class, 'edit'])->name('courses.edit');
            Route::put('/kuratorius/kursai/{course}', [CuratorController::class, 'update'])->name('courses.update');
            Route::post('/kuratorius/kursai/{course}/delete', [CuratorController::class, 'destroy'])->name('courses.curator.destroy');
            Route::resource('courses.steps', CuratorStepController::class)->scoped([
                'course' => 'course:id',
                'step' => 'step:id',
            ])->except(['show']);
            Route::get('/kuratorius/kursai/{course}/dalys/sukurti', [CuratorStepController::class, 'create'])->name('courses.steps.create');
            Route::post('/kuratorius/kursai/{course}/dalys', [CuratorStepController::class, 'store'])->name('courses.steps.store');
            Route::get('/kuratorius/kursai/isiregistravimai', [CuratorController::class, 'enrollmentIndex'])->name('enrollments.curator.index');
            Route::get('/kuratorius/perklausos', [DemoDropController::class, 'fetchCuratorDemos'])->name('demo-drops.curator');
            Route::get('/kuratorius/perklausos/perklausyta', function () {
                return app(DemoDropController::class)->fetchCuratorDemos('checked');
            })->name('demo-drops.curator.checked');
            Route::get('/kuratorius/perklausos/neperklausyta', function () {
                return app(DemoDropController::class)->fetchCuratorDemos('unchecked');
            })->name('demo-drops.curator.unchecked');


            Route::patch('/kuratorius/perklausos/{demoDrop}', [DemoDropController::class, 'update'])->name('demodrop.curator.update');
        });

        // Mokinio nuorodos / keliai (routes)

        Route::middleware(['auth', 'role:learner'])->group(function () {
            Route::get('/mokinys/kursai/isiregistravimai', [LearnerController::class, 'index'])->name('enrollments.learner.index');
            Route::post('/mokinys/kursai/isiregistravimai', [LearnerController::class, 'store'])->name('enrollments.learner.store');
            Route::get('/mokinys/kursai/isiregistravimai/{enrollment}/perziura', [LearnerController::class, 'enrolledCourse'])->name('enrollments.learner.enrolledCourse');
            Route::delete('/mokinys/kursai/isiregistravimai/{enrollment}/palikti', [LearnerController::class, 'unenroll'])->name('enrollments.learner.unenroll');
            Route::post('/mokinys/{enrollment}/mark-as-complete', [LearnerController::class, 'markCourseAsComplete'])->name('enrollments.complete');
            Route::get('/mokinys/kursai', [LearnerController::class, 'availableCourses'])->name('courses.available');
            Route::post('/mokinys/kursai/{course}/isiregistruoti', [LearnerController::class, 'enroll'])->name('courses.enroll');
            Route::get('/mokinys/kursai/{course}/perziura', [LearnerController::class, 'courseView'])->name('courses.view');

            Route::get('/mokinys/perklausos/sukurti', function () {
                return Inertia::render('DemoDrop/DemoDropCreate');
            })->name('demodrop.create');

            Route::get('/mokinys/perklausos', [DemoDropController::class, 'fetchLearnerDemos'])->name('demodrop.learner');
            Route::get('/demodrop/curators', [DemoDropController::class, 'fetchCurators'])->name('demodrop.curators');

            Route::post('/mokinys/perklausos', [DemoDropController::class, 'store'])->name('demodrop.store');
            Route::delete('/mokinys/perklausos/{demoDrop}', [DemoDropController::class, 'destroy'])->name('demodrop.destroy');
        });
    });

    /* 
    // Perklausų puslapio generavimas pagal rolę
    // hasRole metodas gali atvaizduoti save kaip klaidą VS Code aplinkoje
    // Klaidos čia nėra, kadangi naudojamas Laravel skirtas Spatie rolių ir leidimų paketas
    */

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/perklausos', function () {
            $user = auth()->user();
            if ($user->hasRole('curator')) {
                return Inertia::render('DemoDrop/DemoDropCurator');
            } else {
                return Inertia::render('DemoDrop/DemoDropLearner');
            }
        })->name('demodrop.index');
    });

    // Kurso dalių trinimo ir redagavimo nuorodos (kuratoriui)

    Route::delete('/kuratorius/kursai/dalys/{step}', [StepController::class, 'destroy'])->middleware(['auth', 'curator'])->name('api.steps.destroy');
    Route::put('/kuratorius/kursai/dalys/{step}', [CuratorStepController::class, 'update'])->middleware(['auth', 'curator'])->name('api.steps.update');

    // Kurso atsiliepimų nuorodos (abejoms rolėms)

    Route::post('kursai/{course}/atsiliepimai', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
    Route::get('kursai/{course}/atsiliepimai', [ReviewController::class, 'index'])->name('reviews.index')->middleware('auth');
});

require __DIR__ . '/auth.php';
