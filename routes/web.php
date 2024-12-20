    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\CategoryController;
    use App\Http\Controllers\ProductController;
    use App\Http\Controllers\WelcomeController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\CheckoutController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\ProfileController;
    use App\Services\AddressService;
    use App\Http\Controllers\Auth\ForgotPasswordController;
    use App\Http\Controllers\Auth\ResetPasswordController;
    use App\Http\Controllers\StatisticsController;
    use App\Http\Controllers\NewsController;
    use App\Http\Controllers\AboutController;
    use App\Http\Controllers\NotificationController;
    use Illuminate\Support\Facades\Mail;


    // trang chinh

    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

    //gui code
    Route::post('/send-verification-code', [AuthController::class, 'sendVerificationCode'])->name('send.verification.code');
    // login logout regis
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('forgot',[AuthController::class, 'showLinkRequestForm'])->name('forgot');

    // admin
    Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('/products', ProductController::class, ['as' => 'admin']);
        Route::resource('/categories', CategoryController::class, ['as' => 'admin']);
        Route::get('/carts', [CartController::class, 'index'])->name('admin.carts.index');
        Route::delete('/carts/{id}', [CartController::class, 'destroy'])->name('admin.carts.destroy');
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
        Route::put('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('admin.statistics.index');
    });


    // customer
        Route::get('/trangchu', [ProductController::class, 'index'])->name('customer.dashboard');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
        Route::resource('/products', ProductController::class);
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{category}/products', [ProductController::class, 'productsByCategory'])->name('products.byCategory');
        Route::get('/profile', [ProfileController::class, 'index'])->name('customer.profile.index');

        Route::prefix('profile')->group(function () {
            Route::post('/update', [ProfileController::class, 'update'])->name('customer.profile.update');
            Route::get('/orders', [ProfileController::class, 'orders'])->name('customer.profile.orders');
            Route::get('/orders/{order}', [ProfileController::class, 'orderDetail'])->name('customer.profile.orderDetail');
            Route::post('/orders/{order}/pay', [ProfileController::class, 'payOrder'])->name('customer.profile.order.pay');

            //dia chi
            Route::get('/addresses', [ProfileController::class, 'showAddresses'])->name('customer.profile.addresses');
            Route::get('/address', [ProfileController::class, 'addresses'])->name('customer.profile.addresses');

            Route::get('/address/add', [ProfileController::class, 'showAddAddressForm'])->name('customer.profile.address.add'); 
            Route::post('/address/add', [ProfileController::class, 'addAddress'])->name('customer.profile.address.store'); 

            //cập nhật địa chỉ
            Route::put('/addresses/{id}', [ProfileController::class, 'updateAdd'])->name('customer.profile.address.update'); 
            Route::get('/addresses/edit/{id}', [ProfileController::class, 'editAdd'])->name('customer.profile.address.edit'); 

            Route::post('/addresses/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('customer.profile.address.setDefault');
            Route::delete('/customer/profile/address/{id}', [ProfileController::class, 'destroy'])->name('customer.profile.address.delete');

            //qlmk
            Route::get('/password/change', [ProfileController::class, 'showChangePasswordForm'])->name('customer.profile.password.change');
            Route::post('/password/change', [ProfileController::class, 'changePassword'])->name('customer.profile.password.update');
            // Route::post('/password/send-code', [AuthController::class, 'sendVerificationCode'])->name('customer.emails.verification-code');
            Route::post('/send-verification-code', [ProfileController::class, 'sendVerificationCode'])->name('customer.emails.verification-code');
        });


        // thanh toan lai
        Route::get('/orders/unpaid', [CheckoutController::class, 'showUnpaidOrders'])->name('customer.orders.unpaid');
        // huy don
        Route::get('/orders/cancel/{id}', [CheckoutController::class, 'cancelPendingOrders'])->name('customer.orders.cancel');

    //route gio hang
    Route::post('/customer/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/customer/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/customer/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/customer/cart', [CartController::class, 'index'])->name('cart.index');



    Route::get('/districts/{provinceId}', [ProfileController::class, 'getDistricts']);
    Route::get('/wards/{districtId}', [ProfileController::class, 'getWards']);

//thanh toan
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::post('/checkout/vnpay/process', [CheckoutController::class, 'processVNPay'])->name('checkout.vnpay');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('customer.checkout.success');
    // Route để hiển thị form thanh toán lại
    // Route để xử lý thanh toán lại
    Route::post('/checkout/retry/{order}', [CheckoutController::class, 'retryPayment'])->name('checkout.retry.process');
    Route::get('/checkout/retry/{order}', [CheckoutController::class, 'retryPayment'])->name('customer.checkout.retry');
    Route::post('/checkout/retry/{order}/process', [CheckoutController::class, 'retryPaymentProcess'])->name('checkout.retry.process');



//rs pass
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
//tintuc
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
//about
Route::get('/about', [AboutController::class, 'index'])->name('about.index');
//noti
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
