<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComboPackController;
use App\Http\Controllers\ComboPackOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\AboutPage;
use App\Models\Category;
use App\Models\ComboPack;
use App\Models\Slider;
use App\Models\Product;
use App\Models\BankAccount;
use App\Models\Order;
use App\Settings\GeneralSettings;

/*</>
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (GeneralSettings $settings) {
    return Inertia::render(
        'HomePage',
    [
        'sliders' => Slider::all(),
        'products' => Product::all(),
        'orders' => Order::all(),
        'categories' => Category::with('products')->orderBy('sort', 'ASC')->get(),
        'bank_details' => BankAccount::all(),
        'testimonials' => \App\Models\Testimonial::all(),
        'galleries' => \App\Models\Gallery::all(),
        'featured_products' => \App\Models\FeaturedProduct::with('product')->active()->ordered()->get(),
        'global_discount' => $settings->global_discount,
        'min_order_value' => $settings->min_order_value,
        'delivery_charges'=>$settings->delivery_charges,
        



    ]);
})->name('/');

Route::get('/order-now', function (GeneralSettings $settings) {
    return Inertia::render(
        'Home',
        [
            'combo_packs'=> ComboPack::all(),
            'sliders' => Slider::all(),
            'products' => Product::orderBy('sort', 'ASC')->get(),
            'orders' => Order::all(),
            'categories' => Category::with(['products' => function ($query) {
                $query->orderBy('sort', 'ASC'); // Sort products within each category by 'sort' column
            }])->orderBy('sort', 'ASC')->get(),
            'bank_accounts' => BankAccount::all(),
            'featured_products' => \App\Models\FeaturedProduct::with('product')->active()->ordered()->get(),
            'global_discount' => $settings->global_discount,
            'min_order_value' => $settings->min_order_value,
            'mobile_number' => $settings->mobile_number,
            'delivery_charges'=>$settings->delivery_charges,
        ]
    );
})->name('order-now');

Route::get('/order-now/{category_id}', function (Request $request, GeneralSettings $settings) {
    $category = Category::with(['products' => function ($query) {
        $query->orderBy('sort'); // Sort products by the 'sort' column
    }])->where('id', $request->category_id)->first();

    if (!$category) {
        return redirect()->route('/'); // Redirect to home if category not found
    }

    return Inertia::render('CategoryProducts', [
        'category' => $category,
        'bank_accounts' => BankAccount::all(),
        'global_discount' => $settings->global_discount,
        'min_order_value' => $settings->min_order_value,
        'mobile_number' => $settings->mobile_number,
        'delivery_charges' => $settings->delivery_charges,
    ]);
})->name('category_products');

// Combo routes

Route::get('/combo-pack', function (Generalsettings $settings) {
    return Inertia::render('combopack',
     [
            // 'combo_packs'=> ComboPack::all(),
           'combo_packs'=> ComboPack::with('products')->get(),
            'products' => Product::all(),
            'orders' => Order::all(),
            'categories' => Category::with('products')->get(),
            'bank_accounts' => BankAccount::all(),
            'global_discount' => $settings->global_discount,
            'min_order_value' => $settings->min_order_value,
            'mobile_number' => $settings->mobile_number,
            'email' => $settings->email,
            'delivery_charges'=>$settings->delivery_charges,
    
]);

})->name('combo-pack');

Route::post('/combo-packs.store', [ComboPackOrderController::class, 'store'])->name('combo-packs.store');
Route::post('/combopackorder/bulk-download', [ComboPackOrderController::class, 'bulkPdfDownload']);
Route::get('/combo/bulk-download', [ComboPackOrderController::class, 'bulkpdfdownload'])->name('combo.bulk-download');
Route::get('/orders/bulk-download', [OrderController::class, 'bulkpdfdownload'])->name('orders.bulk-download');
Route::get('/admin/combo-orders/{id}/print', [ComboPackOrderController::class, 'printOrder'])->name('admin.combo.print');
Route::get('/admin/combo-orders/{id}/download', [ComboPackOrderController::class, 'downloadOrder'])->name('admin.combo.download');
// Route::get('/admin/combo-orders/bulk-download', [ComboPackOrderController::class, 'bulkPdfDownload'])->name('combo.bulk-download');

// combo

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('/admin/billings', [BillController::class, 'store'])->name('admin.billing');

Route::get('/admin/bill-orders/{id}/download',  [BillController::class, 'billDownloadOrder'])->name('admin.bill-orders.download');

Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::get('/admin/orders/{id}/print', [OrderController::class, 'printOrder'])->name('admin.orders.print');
Route::get('/admin/orders/{id}/download', [OrderController::class, 'downloadOrder'])->name('admin.orders.download');
Route::delete('/bill/{id}', [BillController::class, 'deleteBill'])->name('bill.delete');


Route::get('/about-us', function (GeneralSettings $settings) {
    return Inertia::render(
        'AboutUs',
        [
            'about_page' => AboutPage::all(),
            'sliders' => Slider::all(),
            'products' => Product::all(),
            'orders' => Order::all(),
            'categories' => Category::with('products')->get(),
            'bank_accounts' => BankAccount::all(),
            'global_discount' => $settings->global_discount,
            'min_order_value' => $settings->min_order_value,
            'mobile_number' => $settings->mobile_number,
// 'email' => $settings->email,
'delivery_charges'=>$settings->delivery_charges,
        ]
    );
})->name('about-us');


Route::get('/order-list', function (GeneralSettings $settings) {
    return Inertia::render(
        'OrderList',
        [
            'bank_accounts' => BankAccount::all(),
            'global_discount' => $settings->global_discount,
            'min_order_value' => $settings->min_order_value,
            'mobile_number' => $settings->mobile_number,
            // 'email' => $settings->email,
        ]
    );
})->name('order-list');

Route::get('/product', function () {
    return Inertia::render('Product');
})->name('product');

Route::get('/collection', function () {
    return Inertia::render('Collection');
})->name('collection');

Route::get('/portfolio', function () {
    return Inertia::render('Portfolio');
})->name('portfolio');

Route::get('/Thankyou', function () {
    return Inertia::render('Thankyou');
})->name('Thankyou');

Route::get('/checkout', function (GeneralSettings $settings) {
    return Inertia::render('Checkout', [
        'global_discount' => $settings->global_discount,
        'min_order_value' => $settings->min_order_value,
        'delivery_charges' => $settings->delivery_charges,
    ]);
})->name('checkout');

Route::get('/orderlist1', function () {
    return Inertia::render('Orderlist1');
})->name('orderlist1');

Route::get('/test-professional-components', function () {
    return Inertia::render('TestProfessionalComponents');
})->name('test-professional-components');

Route::get('/products', function () {
    return Inertia::render(
        'Products',
        [
            'products' => Product::all(),
        ]
    );
})->name('products');

Route::get('/contact-us', function (GeneralSettings $settings) {
    return Inertia::render(
        'ContactUs',
        [
            'bank_accounts' => BankAccount::all(),
            'global_discount' => $settings->global_discount,
            'min_order_value' => $settings->min_order_value,
            'company_address' => $settings->company_address,
            'mobile_number' => $settings->mobile_number,
            'email' => $settings->email,
            'delivery_charges'=>$settings->delivery_charges,
            
        ]
    );
})->name('contact-us');
// Route::post('/contact-us', [ContactUsController::class, 'store'])->name('contact-us.store');

Route::get('/address', function () {
    return Inertia::render(
        'Address'
    );
})->name('address');

Route::get('/email-route', function () {
    // Your route logic
})->middleware('email');

Route::get('/account-details', function () {
    return Inertia::render(
        'AccountDetails'
    );
})->name('account-details');

Route::get('/change-password', function () {
    return Inertia::render(
        'ChangePassword'
    );
})->name('user.change-password');

Route::get('/cart', function () {
    return Inertia::render(
        'cart'
    );
})->name('change-password');



/////////////products////////////

//orders
Route::get('/orderlist1', function () {
    return Inertia::render('Orderlist1');
})->name('orderlist1');

Route::get('/order-list', function (GeneralSettings $settings) {
    return Inertia::render(
        'OrderList',
        [
            'bank_accounts' => BankAccount::all(),
            'sliders' => Slider::all(),
            'products' => Product::all(),
            'orders' => Order::all(),
            'categories' => Category::with('products')->get(),
            'global_discount' => $settings->global_discount,
             'email' => $settings->email,

        ]
    );
})->name('orderlist');

//orders
Route::get('/kids-gun', function () {
    return Inertia::render('Kids-gun');
})->name('kids-gun');

Route::post('/enquiry', [EnquiryController::class, 'store']);

// Route::post('order-list');

Route::get('/thankyou', function (GeneralSettings $settings) {
    return Inertia::render(
        'Thankyou',
        [
            'mobile_number' => $settings->mobile_number,
        ]

    );
})->name('thankyou');
Route::get('/thankyou-combo', function (GeneralSettings $settings) {
    return Inertia::render(
        'ThankyouCombo',
        [
            'mobile_number' => $settings->mobile_number,
            'Company_address' => $settings->company_address,
            'Company_name' => $settings->company_name,
        ]

    );
})->name('thankyou-combo');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::get('/reports',[ProductController::class, 'selectedOrders'])->name('orders.SelectedOrders');

// Route::get('/admin/combo-orders/{id}/print', [ComboPackOrderController::class, 'printOrder'])->name('admin.combo.print');
// Route::get('/admin/combo-orders/{id}/download', [ComboPackOrderController::class, 'downloadOrder'])->name('admin.combo.download');
// Route::get('/combo/bulk-download', [ComboPackOrderController::class, 'bulkpdfdownload'])->name('combo.bulk-download');

Route::get('/download-pdf', [CategoryController::class, 'downloadPdf'])->name('download-pdf');


Route::get('/reports', [ProductController::class, 'selectedOrders'])->name('orders.SelectedOrders');
Route::get('/reports', [ComboPackController::class, 'selectedOrders'])->name('combo.SelectedOrders');


Route::get('/price-list', [CategoryController::class, 'downloadPdf'])->name('price-list');



