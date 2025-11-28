<?php

use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\SitemapGenerator;

// Frontend Controllers
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\RoomController as FrontendRoomController;
use App\Http\Controllers\Frontend\MiceController as FrontendMiceController;
use App\Http\Controllers\Frontend\RestaurantController as FrontendRestaurantController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\AffiliateController;
use App\Http\Controllers\Frontend\MiceInquiryController;

// Backend (Admin) Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\MiceRoomController as AdminMiceRoomController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\MiceInquiryController as AdminMiceInquiryController;
use App\Http\Controllers\Admin\AffiliateController as AdminAffiliateController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\MidtransCallbackController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\AffiliatePageController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\PriceOverrideController as AdminPriceOverrideController;

// Affiliate Dashboard Controller
use App\Http\Controllers\Affiliate\DashboardController as AffiliateDashboardController;
use App\Http\Controllers\Affiliate\BookingController as AffiliateBookingController;
// PERHATIKAN: Kita MASIH perlu import RoomPriceController karena file api.php Anda mungkin di-cache oleh web.php
// Namun, jika Anda menggunakan Laravel 11+ style routing, ini mungkin tidak perlu.
// Untuk saat ini, biarkan saja agar aman.
use App\Http\Controllers\Api\RoomPriceController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == FRONTEND ROUTES ==
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rooms
Route::get('/rooms', [FrontendRoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/availability', [FrontendRoomController::class, 'checkAvailability'])->name('rooms.availability');
Route::get('/rooms/{slug}', [FrontendRoomController::class, 'show'])->name('rooms.show');

// MICE & Restaurants, etc.
Route::get('/mice', [FrontendMiceController::class, 'index'])->name('mice.index');
Route::get('/mice/{slug}', [FrontendMiceController::class, 'show'])->name('mice.show');
Route::get('/restaurants', [FrontendRestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{slug}', [FrontendRestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/contact-us', [ContactController::class, 'index'])->name('contact.index');

// Booking & Inquiries
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::post('/mice-inquiries', [MiceInquiryController::class, 'store'])->name('mice.inquiries.store');
Route::get('/booking/success/{booking:access_token}', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/payment/{booking:access_token}', [BookingController::class, 'payment'])->name('booking.payment');

// Affiliate Registration
Route::get('/affiliate/register', [AffiliateController::class, 'create'])->name('affiliate.register.create');
Route::post('/affiliate/register', [AffiliateController::class, 'store'])->name('affiliate.register.store');

// Static Pages
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/apa-itu-affiliate', [PageController::class, 'affiliateInfo'])->name('pages.affiliate_info');

// Midtrans & Sitemap
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'callback'])->name('midtrans.callback');
Route::get('/sitemap.xml', function () {
    return SitemapGenerator::create(config('app.url'))->generate()->toResponse(request());
});

// ======================= BLOK API DIHAPUS =======================
// Rute 'api.room-prices.month' sekarang HANYA ada di file 'routes/api.php'
// ================================================================


// == BACKEND (ADMIN) & AFFILIATE DASHBOARD ROUTES ==
Route::middleware(['auth', 'verified', 'affiliate.active'])->prefix('affiliate')->name('affiliate.')->group(function () {
    Route::get('/dashboard', [AffiliateDashboardController::class, 'index'])->name('dashboard');
    Route::resource('bookings', AffiliateBookingController::class)->only(['create', 'store']);
    Route::get('/mice-kit', [App\Http\Controllers\Affiliate\MiceKitController::class, 'index'])->name('mice-kit.index');
    
    // ======================= AWAL PERBAIKAN =======================
    // Menghapus baris {filename} yang duplikat
    Route::get('/mice-kit/download/{id}', [App\Http\Controllers\Affiliate\MiceKitController::class, 'download'])->name('mice-kit.download');
    // ======================== AKHIR PERBAIKAN =======================
    
    Route::get('/mice-kit/preview/{id}', [App\Http\Controllers\Affiliate\MiceKitController::class, 'preview'])->name('mice-kit.preview');
    Route::get('/mice-kit/stream/{id}', [App\Http\Controllers\Affiliate\MiceKitController::class, 'stream'])->name('mice-kit.stream');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // ... (Semua route admin Anda tetap sama)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::middleware('role:admin,accounting')->group(function () {
        Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
        Route::get('/commissions/{affiliate}', [CommissionController::class, 'show'])->name('commissions.show');
        Route::post('/commissions/{affiliate}/pay', [CommissionController::class, 'markAsPaid'])->name('commissions.pay');
        Route::resource('commissions', CommissionController::class)->only(['create', 'store']);
    });
    Route::middleware('role:admin')->group(function () {
        Route::resource('rooms', AdminRoomController::class);
        Route::resource('mice', AdminMiceRoomController::class);
        Route::resource('restaurants', AdminRestaurantController::class);
        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::resource('bookings', AdminBookingController::class);
        Route::resource('mice-inquiries', AdminMiceInquiryController::class)->only(['index', 'destroy']);
        Route::resource('affiliates', AdminAffiliateController::class)->only(['index', 'update']);
        Route::resource('banners', AdminBannerController::class);
        Route::get('/price-overrides', [AdminPriceOverrideController::class, 'index'])->name('price-overrides.index');
        Route::post('/price-overrides', [AdminPriceOverrideController::class, 'store'])->name('price-overrides.store');
        Route::delete('/price-overrides/{priceOverride}', [AdminPriceOverrideController::class, 'destroy'])->name('price-overrides.destroy');
        Route::delete('restaurants/images/{image}', [AdminRestaurantController::class, 'destroyImage'])->name('restaurants.image.destroy');
        Route::delete('images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/maintenance-settings', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::post('/maintenance-settings', [MaintenanceController::class, 'update'])->name('maintenance.update');
        Route::get('/affiliate-page-settings', [AffiliatePageController::class, 'index'])->name('affiliate_page.index');
        Route::put('/affiliate-page-settings', [AffiliatePageController::class, 'update'])->name('affiliate_page.update');
        Route::resource('mice-kits', App\Http\Controllers\Admin\MiceKitController::class);
        Route::get('mice-inquiries', [\App\Http\Controllers\Admin\MiceInquiryController::class, 'index'])->name('mice-inquiries.index');
        Route::post('mice-inquiries', [\App\Http\Controllers\Admin\MiceInquiryController::class, 'store'])->name('mice-inquiries.store');
        Route::delete('mice-inquiries/{commission}', [\App\Http\Controllers\Admin\MiceInquiryController::class, 'destroy'])->name('mice-inquiries.destroy');
        Route::post('bookings/{booking}/confirm-pay-at-hotel', [AdminBookingController::class, 'confirmPayAtHotel'])->name('bookings.confirmPayAtHotel');
        Route::resource('hero-sliders', App\Http\Controllers\Admin\HeroSliderController::class);
    });
});

// Route bawaan dari Laravel Breeze
require __DIR__.'/auth.php';