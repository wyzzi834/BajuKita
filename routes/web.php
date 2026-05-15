<?php

use App\Http\Controllers\Backend\IndexController;
use App\Http\Controllers\Auth\GoogleController;
use App\Models\Invoice;
use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome', [
        'products' => Product::with('category')
            ->where('status', 'publish')
            ->latest()
            ->paginate(8),
        'categories' => Categorie::orderBy('name')->get(),
    ]);
})->name('storefront');

Auth::routes();

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::get('/operator/login', [App\Http\Controllers\Auth\OperatorLoginController::class, 'showLoginForm'])
    ->name('operator.login');
Route::post('/operator/login', [App\Http\Controllers\Auth\OperatorLoginController::class, 'login'])
    ->name('operator.login.store');

Route::get('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout.get');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/pemesanan', [App\Http\Controllers\HomeController::class, 'orders'])
    ->middleware('auth')
    ->name('orders.index');
Route::delete('/pemesanan/{invoice}', [App\Http\Controllers\HomeController::class, 'destroyOrder'])
    ->middleware('auth')
    ->name('orders.destroy');
Route::post('/checkout/{product}', [App\Http\Controllers\HomeController::class, 'checkout'])
    ->middleware('auth')
    ->name('checkout.store');

Route::prefix('operator')->middleware(['auth', 'operator'])->group(function () {
    Route::get('orderan', [App\Http\Controllers\Operator\OrderController::class, 'index'])
        ->name('operator.order.index');
    Route::patch('orderan/{invoice}/status', [App\Http\Controllers\Operator\OrderController::class, 'updateStatus'])
        ->name('operator.order.status');
    Route::delete('orderan/{invoice}', [App\Http\Controllers\Operator\OrderController::class, 'destroy'])
        ->name('operator.order.destroy');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('', IndexController::class)->name('admin');

    Route::get('account', function () {
        return view('backend.account.index');
    })->name('admin.account.index');

    Route::get('settings', function () {
        return view('backend.settings.index');
    })->name('admin.settings.index');

    // route orderan
    Route::get('orderan', function (\Illuminate\Http\Request $request) {
        $orders = Invoice::query()
            ->with(['product', 'user'])
            ->when($request->filled('invoice'), function ($query) use ($request) {
                $invoice = str_replace(['#', 'INV', 'inv', '-'], '', $request->invoice);

                $query->whereRaw('CAST(id AS TEXT) ILIKE ?', ["%{$invoice}%"]);
            })
            ->when($request->filled('cari'), function ($query) use ($request) {
                $keyword = $request->cari;

                $query->where(function ($query) use ($keyword) {
                    $query->whereHas('user', fn ($query) => $query->where('name', 'ILIKE', "%{$keyword}%"))
                        ->orWhereHas('product', fn ($query) => $query->where('name', 'ILIKE', "%{$keyword}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->get();

        return view('backend.orders.index', [
            'orders' => $orders,
            'pendingCount' => Invoice::where('status', 'pending')->count(),
            'successCount' => Invoice::where('status', 'success')->count(),
            'expiredCount' => Invoice::where('status', 'expired')->count(),
        ]);
    })->name('admin.order.index');

    Route::delete('orderan/{invoice}', function (Invoice $invoice) {
        $invoice->delete();

        return redirect()
            ->route('admin.order.index')
            ->with('success', 'Orderan dan invoice berhasil dihapus');
    })->name('admin.order.destroy');

    // route product
    Route::resource('product', App\Http\Controllers\Backend\ProductController::class, [
        'as' => 'admin',
        'only' => ['index', 'edit', 'store', 'destroy', 'create', 'update']
    ]);

    // user route
    Route::resource('user', App\Http\Controllers\Backend\UserController::class, [
        'as' => 'admin',
        'only' => ['index', 'edit', 'store', 'destroy', 'create', 'update']
    ]);
});
