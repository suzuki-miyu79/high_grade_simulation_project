<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopRepresentativeController;
use App\Http\Controllers\StripeController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RepresentativeMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 飲食店一覧ページ表示
Route::get('/', [RestaurantController::class, 'index'])->name('restaurant.index');

// 飲食店詳細ページ表示
Route::get('/detail/{restaurant_id}', [RestaurantController::class, 'showDetail'])->name('restaurant.detail');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // お気に入り登録/解除
    Route::post('/restaurants/{id}/favorite', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');

    // 予約登録
    Route::post('/reservation/{restaurant_id}', [ReservationController::class, 'store'])->name('reservation.store');

    // 予約完了ページの表示
    Route::get('/done', [ReservationController::class, 'showReserved'])->name('reserved.show');

    // 予約取消
    Route::get('/reservation-cancel/{reservationId}', [ReservationController::class, 'cancelReservation'])
        ->name('reservation.cancel');

    //予約内容変更
    Route::put('/update-reservation/{reservationId}', [ReservationController::class, 'updateReservation'])
        ->name('reservation.update');

    // レビュー登録
    Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');

    // マイページの表示
    Route::get('/mypage', [MyPageController::class, 'showMyPage'])->name('mypage');

    // 管理ページの表示
    Route::get('/admin', [AdminController::class, 'showAdminPage'])->name('admin.show')->middleware(AdminMiddleware::class)->middleware('auth');

    // 店舗代表者登録ページ表示
    Route::get('/representative-register', [AdminController::class, 'showRepresentativeRegister'])
        ->name('register.show');

    // 店舗代表者登録
    Route::post('/representative-register', [AdminController::class, 'register'])
        ->name('representative.register');

    // 登録完了ページ表示
    Route::get('/registered', [AdminController::class, 'showRegistered'])->name('registered.show');

    // メール送信フォームの表示
    Route::get('/mailform', [AdminController::class, 'showMailForm'])->name('mailform.show');

    // メール送信
    Route::post('/send-mail', [AdminController::class, 'sendMail'])->name('send.mail');

    // 店舗管理ページの表示
    Route::get('/shop-management', [ShopRepresentativeController::class, 'index'])->name('shop-management.index')->middleware(RepresentativeMiddleware::class)->middleware('auth');

    // 店舗情報登録
    Route::post('/shop-management/store', [RestaurantController::class, 'store'])->name('restaurant.store');

    // 店舗情報更新
    Route::put('/shop-management/update/{id}', [RestaurantController::class, 'update'])->name('restaurant.update');

    // 決済機能
    Route::post('/charge', [StripeController::class, 'charge'])->name('stripe.charge');

    // リマインド機能
    Route::post('/send-mail', [AdminController::class, 'sendMail'])->name('send.mail');
});

require __DIR__ . '/auth.php';
