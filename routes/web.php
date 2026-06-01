<?php

use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\TestimonialController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PackageController;
use App\Http\Controllers\Frontend\RegistrationController;
use App\Http\Controllers\Admin\RegistrationBundlePdfController;
use App\Http\Controllers\Admin\PaymentReceiptPdfController;
use App\Http\Controllers\Admin\DepartureManifestPdfController;
use App\Http\Controllers\Admin\ReportExportController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/paket-umroh', [PackageController::class, 'umroh'])->name('packages.umroh');
Route::get('/paket-haji', [PackageController::class, 'haji'])->name('packages.haji');
Route::get('/paket/{slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/galeri/{gallery}', [GalleryController::class, 'show'])->name('galleries.show');

Route::get('/testimoni', [TestimonialController::class, 'index'])->name('testimonials.index');

Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');

Route::post('/pendaftaran', [RegistrationController::class, 'store'])->name('registrations.store');

Route::get('/admin-reports/registrations/{registration}/bundle-pdf', [RegistrationBundlePdfController::class, 'show'])
    ->middleware(['auth'])
    ->name('admin.registrations.bundle-pdf');

Route::get('/admin-reports/payments/{payment}/receipt-pdf', [PaymentReceiptPdfController::class, 'show'])
    ->middleware(['auth'])
    ->name('admin.payments.receipt-pdf');

Route::get('/admin-reports/departure-groups/{departureGroup}/manifest-pdf', [DepartureManifestPdfController::class, 'show'])
    ->middleware(['auth'])
    ->name('admin.departure-groups.manifest-pdf');

Route::get('/admin-reports/export/registrations', [ReportExportController::class, 'registrations'])
    ->middleware(['auth'])
    ->name('admin.export.registrations');

Route::get('/admin-reports/export/payments', [ReportExportController::class, 'payments'])
    ->middleware(['auth'])
    ->name('admin.export.payments');

Route::get('/admin-reports/export/manifests', [ReportExportController::class, 'manifests'])
    ->middleware(['auth'])
    ->name('admin.export.manifests');

Route::get('/dashboard', function () {
    return Inertia::render('dashboard');
})->name('dashboard');

if (file_exists(__DIR__ . '/settings.php')) {
    require __DIR__ . '/settings.php';
}

if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}