<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Parts\PartsController;
use App\Http\Controllers\Api\DefectCodeController;
use App\Http\Controllers\Api\ProductGroupController;
use App\Http\Controllers\Api\ResolutionTemplateController;
use App\Http\Controllers\Api\SymptomCodeController;
use App\Http\Controllers\WarrantyClaim\WarrantyClaimController;
use App\Http\Controllers\TechnicalConclusion\TechnicalConclusionController;


Route::group(['middleware' => ['auth']], function () {

    Route::get('/', function () {
        return redirect()->route('app.home.index');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('app.home.index');
    Route::get('/warranty_claims', [WarrantyClaimController::class, 'index'])->name('app.warranty.index');
    Route::get('/search', [WarrantyClaimController::class, 'search'])->name('app.search');

    Route::get('/warranty/edit/{id}', [WarrantyClaimController::class, 'edit'])->name('app.warranty.edit');
    Route::get('/warranty/{id}/parts', [WarrantyClaimController::class, 'getParts'])->name('warranty.getParts');

    Route::post('/conclusion', [TechnicalConclusionController::class, 'store'])->name('app.conclusion.store');
    Route::get('/conclusion', [TechnicalConclusionController::class, 'index'])->name('app.conclusion.index');

    // Довідники
    Route::get('/defect', [GuideController::class, 'defect'])->name('app.defect.index');
    Route::get('/symptom', [GuideController::class, 'symptom'])->name('app.symptom.index');
    Route::get('/service', [GuideController::class, 'service'])->name('app.service.index');

    // Отримати сервісні роботи по групі
    Route::get('/service/{group_id}', [GuideController::class, 'getServiceWorks'])->name('app.service.works');

    // Пошук запчастин
    Route::get('/parts/{articul}/{page?}', [PartsController::class, 'search'])->name('parts.search');

    // Збереження запчастини
    Route::post('/warranty-claim-spareparts', [PartsController::class, 'store'])->name('spareparts.store');

    // Видалення запчастини
    Route::delete('/warranty-claim-spareparts/{id}', [PartsController::class, 'destroy'])->name('spareparts.destroy');

});

Route::group(['middleware' => ['guest']], function () {
     Route::get('/login', [LoginController::class, 'index'])->name('index');
     Route::post('login', [LoginController::class, 'login'])->name('login');
    });

    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

 // API
    Route::post('/symptom-codes', [SymptomCodeController::class, 'store']);

    Route::post('/defect-codes', [DefectCodeController::class, 'store']);

    Route::post('/product-groups', [ProductGroupController::class, 'store']);

    Route::post('/resolution-templates', [ResolutionTemplateController::class, 'store']);