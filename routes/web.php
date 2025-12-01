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
use App\Http\Controllers\Frontend\RecreationAreaController;

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
use App\Http\Controllers\Admin\MiceKitController as AdminMiceKitController;
use App\Http\Controllers\Admin\HeroSliderController as AdminHeroSliderController;

// Affiliate Dashboard Controller
use App\Http\Controllers\Affiliate\DashboardController as AffiliateDashboardController;
use App\Http\Controllers\Affiliate\BookingController as AffiliateBookingController;
use App\Http\Controllers\Affiliate\MiceKitController as AffiliateMiceKitController;
use App\Http\Controllers\Affiliate\AffiliateMiceBookingController;
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
Route::get('/rooms/search', [FrontendRoomController::class, 'checkAvailability'])->name('rooms.search');
Route::get('/rooms/availability', [FrontendRoomController::class, 'checkAvailability'])->name('rooms.availability');
Route::get('/rooms/{slug}', [FrontendRoomController::class, 'show'])->name('rooms.show');

// MICE & Restaurants, etc.
Route::get('/mice', [FrontendMiceController::class, 'index'])->name('mice.index');
Route::get('/mice/{slug}', [FrontendMiceController::class, 'show'])->name('mice.show');
Route::get('/restaurants', [FrontendRestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{slug}', [FrontendRestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/recreation-areas', [RecreationAreaController::class, 'index'])->name('recreation-areas.index');
Route::get('/recreation-areas/{slug}', [RecreationAreaController::class, 'show'])->name('recreation-areas.show');
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


// == BACKEND (ADMIN) & AFFILIATE DASHBOARD ROUTES ==
Route::middleware(['auth', 'verified', 'affiliate.active'])->prefix('affiliate')->name('affiliate.')->group(function () {
    Route::get('/dashboard', [AffiliateDashboardController::class, 'index'])->name('dashboard');
    Route::resource('bookings', AffiliateBookingController::class)->only(['create', 'store']);
    Route::get('/mice-kit', [AffiliateMiceKitController::class, 'index'])->name('mice-kit.index');
    
    Route::get('/mice-kit/download/{id}', [AffiliateMiceKitController::class, 'download'])->name('mice-kit.download');
    Route::get('/mice-kit/preview/{id}', [AffiliateMiceKitController::class, 'preview'])->name('mice-kit.preview');
    Route::get('/mice-kit/stream/{id}', [AffiliateMiceKitController::class, 'stream'])->name('mice-kit.stream');

    // Special MICE Booking Routes
    Route::get('/special-mice', [AffiliateMiceBookingController::class, 'index'])->name('special_mice.index');
    Route::get('/special-mice/{id}', [AffiliateMiceBookingController::class, 'show'])->name('special_mice.show');
    Route::post('/special-mice', [AffiliateMiceBookingController::class, 'store'])->name('special_mice.store');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. General Dashboard & Profile (Semua Role Staff)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2. Group: Admin + Accounting + Frontoffice (Commissions)
    Route::middleware('role:admin,accounting,frontoffice')->group(function () {
        Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
        Route::get('/commissions/{affiliate}', [CommissionController::class, 'show'])->name('commissions.show');
        Route::post('/commissions/{affiliate}/pay', [CommissionController::class, 'markAsPaid'])->name('commissions.pay');
        Route::resource('commissions', CommissionController::class)->only(['create', 'store']);
    });

    // 3. Group: Admin + Frontoffice (Bookings & Inquiries)
    Route::middleware('role:admin,frontoffice')->group(function () {
        Route::resource('bookings', AdminBookingController::class);
        Route::post('bookings/{booking}/confirm-pay-at-hotel', [AdminBookingController::class, 'confirmPayAtHotel'])->name('bookings.confirmPayAtHotel');
    });

    // 4. Group: Admin ONLY (Semua menu pengaturan dan konten)
    Route::middleware('role:admin')->group(function () {
        Route::resource('rooms', AdminRoomController::class);
        Route::resource('mice', AdminMiceRoomController::class);
        Route::resource('restaurants', AdminRestaurantController::class);
        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
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
        Route::resource('mice-kits', AdminMiceKitController::class);
        Route::resource('hero-sliders', AdminHeroSliderController::class);
        Route::resource('mice-inquiries', AdminMiceInquiryController::class)->only(['index', 'destroy']);
    });
});

// Route bawaan dari Laravel Breeze
require __DIR__.'/auth.php';