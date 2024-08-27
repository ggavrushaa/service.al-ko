<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Parts\PartsController;
use App\Http\Controllers\Auth\LoginController;  
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\Api\DefectCodeController;
use App\Http\Controllers\Api\ServiceWorkController;
use App\Http\Controllers\Api\SymptomCodeController;
use App\Http\Controllers\GuaranteeCouponController;
use App\Http\Controllers\Api\ProductGroupController;
use App\Http\Controllers\WarrantyClaimFileController;
use App\Http\Controllers\ResolutionTemplatesController;
use App\Http\Controllers\Api\ResolutionTemplateController;
use App\Http\Controllers\TechnicalConclusion\PDFController;
use App\Http\Controllers\WarrantyClaim\WarrantyClaimController;
use App\Http\Controllers\WarrantyClaim\WarrantyClaimCommentController;
use App\Http\Controllers\TechnicalConclusion\TechnicalConclusionController;
use App\Http\Controllers\Api\WarrantyClaimController as ApiWarrantyClaimController;
use App\Http\Controllers\Api\TechnicalConclusionController as ApiTechnicalConclusionController;

Route::group(['middleware' => ['auth',]], function () {

    Route::get('/', function () {
        return redirect()->route('app.home.index');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('app.home.index');
    Route::get('/warranty_claims', [WarrantyClaimController::class, 'index'])->name('app.warranty.index');
    Route::get('/search', [GuaranteeCouponController::class, 'index'])->name('app.search');

    Route::get('/warranty/create/{barcode?}/{factory_number?}', [WarrantyClaimController::class, 'create'])->name('app.warranty.create');
    Route::get('/warranty/edit/{id}', [WarrantyClaimController::class, 'edit'])->name('app.warranty.edit');

    Route::post('/conclusion', [TechnicalConclusionController::class, 'store'])->name('app.conclusion.store');
    Route::get('/conclusion', [TechnicalConclusionController::class, 'index'])->name('app.conclusion.index');

    // Довідники
    Route::get('/defect', [GuideController::class, 'defect'])->name('app.defect.index');
    Route::get('/symptom', [GuideController::class, 'symptom'])->name('app.symptom.index');
    Route::get('/service', [GuideController::class, 'service'])->name('app.service.index');
    // Користувач
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('users.update');

    // Отримати сервісні роботи по групі
    Route::get('/service/{groupId}', [GuideController::class, 'getServiceWorksByGroupId']);

    // Отримати всіх менеджерів 
    Route::get('/managers', [UserController::class, 'getManagers'])->name('managers.list');

    // Отримати шаблони резолюцій
    Route::get('/resolution', [ResolutionTemplatesController::class, 'getTemplates'])->name('resolution.list');

    // Видалити завантаженний файл
    Route::delete('/delete-file/{id}', [WarrantyClaimFileController::class, 'destroy'])->name('file.destroy');

    // Переназначення менеджера
    Route::post('/warranty-claims/{claim}/update-manager', [WarrantyClaimController::class, 'updateManager']);

    // Пошук запчастин
    Route::get('/parts/{articul}/{page?}', [PartsController::class, 'search'])->name('parts.search');
    Route::post('/parts-destroy/{id}', [PartsController::class, 'destroy'])->name('parts.destroy');

    // Збереження акту
    Route::post('/technical-conclusions/{id}/save', [TechnicalConclusionController::class, 'save'])->name('technical-conclusions.save');

    // Збереження акту і виход
    Route::post('/technical-conclusions/{id}/save-and-exit', [TechnicalConclusionController::class, 'exit'])->name('conclusions.save-and-exit');

    // Підбір контракту по сервісному центру
    Route::post('/get-contract-details', [WarrantyClaimController::class, 'getContractDetails']);

    // Збереження запчастини
    Route::post('/warranty-claim-spareparts', [PartsController::class, 'store'])->name('spareparts.store');

    // Видалення запчастини
    Route::delete('/warranty-claim-spareparts/{id}', [PartsController::class, 'destroy'])->name('spareparts.destroy');

    // Пошук контрактів
    Route::get('/contracts/{centerId}', [WarrantyClaimController::class, 'getContracts']);

    // Зміна статуса гарантійки
    Route::post('/warranty-claims/{id}/send-to-review', [WarrantyClaimController::class, 'sendToReview'])->name('warranty-claims.send-to-review');
    Route::get('/warranty-claims/{id}/take-to-work', [WarrantyClaimController::class, 'takeToWork'])->name('warranty-claims.take-to-work');

    // Акт технічної єкспертизи 
    Route::get('/warranty-claims/{id}/create-technical-conclusion', [TechnicalConclusionController::class, 'create'])->name('technical-conclusions.create');
    Route::post('/warranty-claims/{id}/store-technical-conclusion', [TechnicalConclusionController::class, 'store'])->name('technical-conclusions.store');
    Route::put('/warranty-claims/{id}/update-technical-conclusion', [TechnicalConclusionController::class, 'update'])->name('technical-conclusions.update');

    // Документація
    Route::get('/documentations', [DocumentationController::class, 'index'])->name('documentations.index');
    Route::get('/fees', [DocumentationController::class, 'fees'])->name('documentations.fees');
    Route::post('/documentations/import', [DocumentationController::class, 'import'])->name('documentations.import');
    Route::put('/documentations/update/{id}', [DocumentationController::class, 'update'])->name('documentations.update');
    Route::delete('/documentations/delete/{id}', [DocumentationController::class, 'delete'])->name('documentations.delete');

    // Генерація ПДФ
    Route::get('/generate-pdf/{id}', [PDFController::class, 'generatePDF'])->name('generate.pdf');

    // Отримання контрактів по сервіс центру
    Route::get('/contracts/{centerId}', [ContractController::class, 'getContractsByServiceCenter']);

    // Фільтри
    Route::get('/warranty-claims/filter', [WarrantyClaimController::class, 'filter'])->name('warranty-claims.filter');
    Route::get('/technical-conclusions/filter', [TechnicalConclusionController::class, 'filter'])->name('technical-conclusions.filter');

    // Отримати список партнерів
    Route::get('/user-partners', [UserController::class, 'getUserPartners']);

});


// Збереження гарантійки
Route::post('/warranty-claims/save', [WarrantyClaimController::class, 'save'])->name('warranty-claims.save');

Route::delete('/warranty-claims/{id}', [WarrantyClaimController::class, 'delete'])->name('warranty-claims.delete');

Route::get('/warranty-claims/sort', [WarrantyClaimController::class, 'sort'])->name('warranty-claims.sort');
Route::get('/technical-conclusions/sort', [TechnicalConclusionController::class, 'sort'])->name('technical-conclusions.sort');

// Видалення зображення
Route::post('/warranty-image/{id}', [WarrantyClaimController::class, 'destroyImage'])->name('warranty-image.remove');

// Видалення запчастини
Route::post('/warranty-claim-spareparts/{id}', [PartsController::class, 'destroy'])->name('spareparts.destroy');


Route::group(['middleware' => ['guest']], function () {
     Route::get('/login', [LoginController::class, 'index'])->name('index');
     Route::post('login', [LoginController::class, 'login'])->name('login');
    });

    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

    // Коментарі для обговорення ГЗ
    Route::prefix('warranty-claims/{claimId}/comments')->group(function () {
        Route::get('/', [WarrantyClaimCommentController::class, 'index']);
        Route::post('/', [WarrantyClaimCommentController::class, 'store']);
        Route::put('{commentId}', [WarrantyClaimCommentController::class, 'update']);
        Route::delete('{commentId}', [WarrantyClaimCommentController::class, 'destroy']);
    });
    

 // API
    Route::post('/symptom-codes', [SymptomCodeController::class, 'store']);
    Route::post('/defect-codes', [DefectCodeController::class, 'store']);
    Route::post('/product-groups', [ProductGroupController::class, 'store']);
    Route::post('/resolution-templates', [ResolutionTemplateController::class, 'store']);
    Route::post('/service-works', [ServiceWorkController::class, 'store']);

    Route::post('/warranty-claims', [ApiWarrantyClaimController::class, 'store']);
    Route::get('/warranty-claims-get', [ApiWarrantyClaimController::class, 'index']);

    Route::get('/technical-conclusions', [ApiTechnicalConclusionController::class, 'index']);
    Route::post('/technical-conclusions', [ApiTechnicalConclusionController::class, 'store']);