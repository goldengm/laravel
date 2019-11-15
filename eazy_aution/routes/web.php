<?php
/*
Project Name: laravelcommerce
Project URI: http://laravelcommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
Version: 1.1 -desktop
 */
header("Cache-Control: no-cache, must-revalidate");
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization, consumer-nonce, consumer-secret,consumer-key, consumer-device-id');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

/*
|--------------------------------------------------------------------------
| Admin controller Routes
|--------------------------------------------------------------------------
|
| This section contains all admin Routes
|
|
 */

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
});

Route::get('/key-genrate', function () {
    $exitCode = Artisan::call('key:generate');
});

Route::group(['prefix' => 'admin'], function () {

    Route::group(['namespace' => 'Admin'], function () {

        Route::group(['middleware' => 'admin'], function () {
            Route::get('/dashboard/{reportBase}', 'AdminController@dashboard');
            Route::get('/post', 'AdminController@myPost');
            //show admin personal info record
            Route::get('/adminInfo', 'AdminController@adminInfo');

            /*
            |--------------------------------------------------------------------------
            | categories/Product Controller Routes
            |--------------------------------------------------------------------------
            |
            | This section contains categories/Product Controller Routes
            |
            |
             */

            // Auction
            Route::resource('vendors', 'VendorController');
            Route::resource('buyers', 'BuyerController');
            Route::resource('auctions', 'AuctionController');

            //main listingManufacturer
            Route::get('/manufacturers', 'AdminManufacturerController@manufacturers');
            Route::get('/addmanufacturer', 'AdminManufacturerController@addmanufacturer');
            Route::post('/addnewmanufacturer', 'AdminManufacturerController@addnewmanufacturer');
            Route::get('/editmanufacturer/{id}', 'AdminManufacturerController@editmanufacturer');
            Route::post('/updatemanufacturer', 'AdminManufacturerController@updatemanufacturer');
            Route::post('/deletemanufacturer', 'AdminManufacturerController@deletemanufacturer');

            //main categories
            Route::get('/categories', 'AdminCategoriesController@categories');
            Route::get('/addcategory', 'AdminCategoriesController@addcategory');
            Route::post('/addnewcategory', 'AdminCategoriesController@addnewcategory');
            Route::get('/editcategory/{id}', 'AdminCategoriesController@editcategory');
            Route::post('/updatecategory', 'AdminCategoriesController@updatecategory');
            Route::get('/deletecategory/{id}', 'AdminCategoriesController@deletecategory');

            //sub categories
            Route::get('/subcategories', 'AdminCategoriesController@subcategories');
            Route::get('/addsubcategory', 'AdminCategoriesController@addsubcategory');
            Route::post('/addnewsubcategory', 'AdminCategoriesController@addnewsubcategory');
            Route::get('/editsubcategory/{id}', 'AdminCategoriesController@editsubcategory');
            Route::post('/updatesubcategory', 'AdminCategoriesController@updatesubcategory');
            Route::get('/deletesubcategory/{id}', 'AdminCategoriesController@deletesubcategory');

            Route::post('/getajaxcategories', 'AdminCategoriesController@getajaxcategories');

            //products
            Route::get('/products', 'AdminProductsController@products');
            Route::get('/addproduct', 'AdminProductsController@addproduct');
            Route::post('/addnewproduct', 'AdminProductsController@addnewproduct');

            //listing
            Route::get('/listings', 'AdminListingsController@listings');
            Route::get('/addlisting', 'AdminListingsController@addlisting');
            Route::post('/addnewlisting', 'AdminListingsController@addnewlisting');

            //add attribute against newly added product
            Route::get('/addproductattribute/{id}/', 'AdminProductsController@addproductattribute');
            Route::get('/addinventory/{id}/', 'AdminProductsController@addinventory');
            Route::post('/currentstock', 'AdminProductsController@currentstock');
            Route::post('/addnewstock', 'AdminProductsController@addnewstock');
            Route::post('/addminmax', 'AdminProductsController@addminmax');
            Route::get('/addproductimages/{id}/', 'AdminProductsController@addproductimages');
            Route::post('/addnewdefaultattribute', 'AdminProductsController@addnewdefaultattribute');
            Route::post('/addnewproductattribute', 'AdminProductsController@addnewproductattribute');
            Route::post('/updateproductattribute', 'AdminProductsController@updateproductattribute');
            Route::post('/updatedefaultattribute', 'AdminProductsController@updatedefaultattribute');
            Route::post('/deleteproduct', 'AdminProductsController@deleteproduct');
            Route::post('/deleteproductattribute', 'AdminProductsController@deleteproductattribute');
            Route::post('/addnewproductimage', 'AdminProductsController@addnewproductimage');
            Route::post('/deletedefaultattribute', 'AdminProductsController@deletedefaultattribute');
            Route::post('editproductattribute', 'AdminProductsController@editproductattribute');
            Route::post('editdefaultattribute', 'AdminProductsController@editdefaultattribute');
            Route::post('addnewproductimagemodal', 'AdminProductsController@addnewproductimagemodal');
            Route::post('deleteproductattributemodal', 'AdminProductsController@deleteproductattributemodal');
            Route::post('deletedefaultattributemodal', 'AdminProductsController@deletedefaultattributemodal');

            //product attribute
            Route::post('/addnewproductimage', 'AdminProductsController@addnewproductimage');
            Route::post('editproductimage', 'AdminProductsController@editproductimage');
            Route::post('/updateproductimage', 'AdminProductsController@updateproductimage');
            Route::post('/deleteproductimagemodal', 'AdminProductsController@deleteproductimagemodal');
            Route::post('/deleteproductimage', 'AdminProductsController@deleteproductimage');
            Route::get('/editproduct/{id}', 'AdminProductsController@editproduct');
            Route::post('/updateproduct', 'AdminProductsController@updateproduct');
            Route::post('/getOptions', 'AdminProductsController@getOptions');
            Route::post('/getOptionsValue', 'AdminProductsController@getOptionsValue');

            //Attribute
            Route::get('/attributes', 'AdminProductsController@attributes');
            Route::get('/addoptions', 'AdminProductsController@addoptions');
            Route::post('/addnewoptions', 'AdminProductsController@addnewoptions');
            //
            Route::post('/addnewattributes', 'AdminProductsController@addnewattributes');
            //Route::get('/editattributes/{id}/{language_id}', 'AdminProductsController@editattributes');
            Route::get('/manage-options/{id}', 'AdminProductsController@manageoptions');
            Route::get('/edit-values/{id}', 'AdminProductsController@editvalues');
            Route::post('/updatevalue', 'AdminProductsController@updatevalue');
            Route::post('/addnewvalues', 'AdminProductsController@addnewvalues');

            Route::get('/manage-options-values/{id}', 'AdminProductsController@manageoptionsvalues');
            Route::post('/updateoptions/', 'AdminProductsController@updateoptions');
            Route::post('/deleteattribute', 'AdminProductsController@deleteattribute');
            Route::post('/addattributevalue', 'AdminProductsController@addattributevalue');
            Route::post('/updateattributevalue', 'AdminProductsController@updateattributevalue');
            Route::post('/checkattributeassociate', 'AdminProductsController@checkattributeassociate');
            Route::post('/checkvalueassociate', 'AdminProductsController@checkvalueassociate');
            Route::post('/deletevalue', 'AdminProductsController@deletevalue');

            //manageAppLabel
            Route::get('/listingAppLabels', 'AdminAppLabelsController@listingAppLabels');
            Route::get('/addappkey', 'AdminAppLabelsController@addappkey');
            Route::post('/addNewAppLabel', 'AdminAppLabelsController@addNewAppLabel');
            Route::get('/editAppLabel/{id}', 'AdminAppLabelsController@editAppLabel');
            Route::post('/updateAppLabel/', 'AdminAppLabelsController@updateAppLabel');
            Route::get('/applabel', 'AdminAppLabelsController@manageAppLabel');

            //customers
            Route::get('/customers', 'AdminCustomersController@customers');
            Route::get('/addcustomers', 'AdminCustomersController@addcustomers');
            Route::post('/addnewcustomers', 'AdminCustomersController@addnewcustomers');

            //add adddresses against customers
            Route::get('/addaddress/{id}/', 'AdminCustomersController@addaddress');
            Route::post('/addNewCustomerAddress', 'AdminCustomersController@addNewCustomerAddress');
            Route::post('/editAddress', 'AdminCustomersController@editAddress');
            Route::post('/updateAddress', 'AdminCustomersController@updateAddress');
            Route::post('/deleteAddress', 'AdminCustomersController@deleteAddress');
            Route::post('/getZones', 'AddressController@getZones');
            //edit customer
            Route::get('/editcustomers/{id}', 'AdminCustomersController@editcustomers');
            Route::post('/updatecustomers', 'AdminCustomersController@updatecustomers');
            Route::post('/deletecustomers', 'AdminCustomersController@deletecustomers');

            //orders
            Route::get('/orders', 'AdminOrdersController@orders');
            Route::get('/vieworder/{id}', 'AdminOrdersController@vieworder');
            Route::post('/updateOrder', 'AdminOrdersController@updateOrder');
            Route::post('/deleteOrder', 'AdminOrdersController@deleteOrder');
            Route::get('/invoiceprint/{id}', 'AdminOrdersController@invoiceprint');

            //alert setting
            Route::get('/alertsetting', 'AdminSiteSettingController@alertSetting');
            Route::post('/updateAlertSetting', 'AdminSiteSettingController@updateAlertSetting');

            //generate application key
            Route::get('/generateKey', 'AdminSiteSettingController@generateKey');

            //countries
            Route::get('/countries', 'AdminTaxController@countries');
            Route::get('/addcountry', 'AdminTaxController@addcountry');
            Route::post('/addnewcountry', 'AdminTaxController@addnewcountry');
            Route::get('/editcountry/{id}', 'AdminTaxController@editcountry');
            Route::post('/updatecountry', 'AdminTaxController@updatecountry');
            Route::post('/deletecountry', 'AdminTaxController@deletecountry');

            //zones
            Route::get('/listingZones', 'AdminTaxController@listingZones');
            Route::get('/addZone', 'AdminTaxController@addZone');
            Route::post('/addNewZone', 'AdminTaxController@addNewZone');
            Route::get('/editZone/{id}', 'AdminTaxController@editZone');
            Route::post('/updateZone', 'AdminTaxController@updateZone');
            Route::post('/deleteZone', 'AdminTaxController@deleteZone');

            //tax class
            Route::get('/taxclass', 'AdminTaxController@taxclass');
            Route::get('/addtaxclass', 'AdminTaxController@addtaxclass');
            Route::post('/addnewtaxclass', 'AdminTaxController@addnewtaxclass');
            Route::get('/edittaxclass/{id}', 'AdminTaxController@edittaxclass');
            Route::post('/updatetaxclass', 'AdminTaxController@updatetaxclass');
            Route::post('/deletetaxclass', 'AdminTaxController@deletetaxclass');

            //tax rate
            Route::get('/taxrates', 'AdminTaxController@taxrates');
            Route::get('/addtaxrate', 'AdminTaxController@addtaxrate');
            Route::post('/addnewtaxrate', 'AdminTaxController@addnewtaxrate');
            Route::get('/edittaxrate/{id}', 'AdminTaxController@edittaxrate');
            Route::post('/updatetaxrate', 'AdminTaxController@updatetaxrate');
            Route::post('/deletetaxrate', 'AdminTaxController@deletetaxrate');

            //shipping setting
            Route::get('/shippingmethods', 'AdminShippingController@shippingmethods');
            Route::get('/upsShipping', 'AdminShippingController@upsShipping');
            Route::post('/updateupsshipping', 'AdminShippingController@updateupsshipping');
            Route::get('/flateRate', 'AdminShippingController@flateRate');
            Route::post('/updateflaterate', 'AdminShippingController@updateflaterate');
            Route::post('/defaultShippingMethod', 'AdminShippingController@defaultShippingMethod');
            Route::get('/shippingDetail/{table_name}', 'AdminShippingController@shippingDetail');
            Route::post('/updateShipping', 'AdminShippingController@updateShipping');

            //shppingbyprice
            Route::get('/shppingbyweight', 'AdminShippingByWeightController@shppingbyweight');
            Route::post('/updateShppingWeightPrice', 'AdminShippingByWeightController@updateShppingWeightPrice');

            //Payment setting
            Route::get('/paymentsetting', 'AdminPaymentController@paymentsetting');
            Route::post('/updatePaymentSetting', 'AdminPaymentController@updatePaymentSetting');

            //orders
            Route::get('/orderstatus', 'AdminSiteSettingController@orderstatus');
            Route::get('/addorderstatus', 'AdminSiteSettingController@addorderstatus');
            Route::post('/addNewOrderStatus', 'AdminSiteSettingController@addNewOrderStatus');
            Route::get('/editorderstatus/{id}', 'AdminSiteSettingController@editorderstatus');
            Route::post('/updateOrderStatus', 'AdminSiteSettingController@updateOrderStatus');
            Route::post('/deleteOrderStatus', 'AdminSiteSettingController@deleteOrderStatus');

            //units
            Route::get('/units', 'AdminSiteSettingController@units');
            Route::get('/addunit', 'AdminSiteSettingController@addunit');
            Route::post('/addnewunit', 'AdminSiteSettingController@addnewunit');
            Route::get('/editunit/{id}', 'AdminSiteSettingController@editunit');
            Route::post('/updateunit', 'AdminSiteSettingController@updateunit');
            Route::post('/deleteunit', 'AdminSiteSettingController@deleteunit');

            //setting page
            Route::get('/setting', 'AdminSiteSettingController@setting');
            Route::post('/updateSetting', 'AdminSiteSettingController@updateSetting');

            Route::get('/websettings', 'AdminSiteSettingController@webSettings');
            Route::get('/themeSettings', 'AdminSiteSettingController@themeSettings');
            Route::get('/appsettings', 'AdminSiteSettingController@appSettings');
            Route::get('/admobSettings', 'AdminSiteSettingController@admobSettings');
            Route::get('/facebooksettings', 'AdminSiteSettingController@facebookSettings');
            Route::get('/googlesettings', 'AdminSiteSettingController@googleSettings');
            Route::get('/applicationapi', 'AdminSiteSettingController@applicationApi');
            Route::get('/webthemes', 'AdminSiteSettingController@webThemes');
            Route::get('/seo', 'AdminSiteSettingController@seo');
            Route::get('/customstyle', 'AdminSiteSettingController@customstyle');
            Route::post('/updateWebTheme', 'AdminSiteSettingController@updateWebTheme');
            Route::get('/mailchimp', 'AdminSiteSettingController@mailchimp');

            //pushNotification
            Route::get('/pushnotification', 'AdminSiteSettingController@pushNotification');

            //language setting
            Route::get('/getlanguages', 'AdminSiteSettingController@getlanguages');
            Route::get('/languages', 'AdminSiteSettingController@languages');
            Route::post('/defaultlanguage', 'AdminSiteSettingController@defaultlanguage');
            Route::get('/addlanguages', 'AdminSiteSettingController@addlanguages');
            Route::post('/addnewlanguages', 'AdminSiteSettingController@addnewlanguages');
            Route::get('/editlanguages/{id}', 'AdminSiteSettingController@editlanguages');
            Route::post('/updatelanguages', 'AdminSiteSettingController@updatelanguages');
            Route::post('/deletelanguage', 'AdminSiteSettingController@deletelanguage');

            //banners
            Route::get('/banners', 'AdminBannersController@banners');
            Route::get('/addbanner', 'AdminBannersController@addbanner');
            Route::post('/addNewBanner', 'AdminBannersController@addNewBanner');
            Route::get('/editbanner/{id}', 'AdminBannersController@editbanner');
            Route::post('/updateBanner', 'AdminBannersController@updateBanner');
            Route::post('/deleteBanner/', 'AdminBannersController@deleteBanner');

            //sliders
            Route::get('/sliders', 'AdminSlidersController@sliders');
            Route::get('/addsliderimage', 'AdminSlidersController@addsliderimage');
            Route::post('/addNewSlide', 'AdminSlidersController@addNewSlide');
            Route::get('/editslide/{id}', 'AdminSlidersController@editslide');
            Route::post('/updateSlide', 'AdminSlidersController@updateSlide');
            Route::post('/deleteSlider/', 'AdminSlidersController@deleteSlider');

            //constant banners
            Route::get('/constantbanners', 'AdminConstantController@constantBanners');
            Route::get('/addconstantbanner', 'AdminConstantController@addconstantBanner');
            Route::post('/addNewConstantBanner', 'AdminConstantController@addNewconstantBanner');
            Route::get('/editconstantbanner/{id}', 'AdminConstantController@editconstantbanner');
            Route::post('/updateconstantBanner', 'AdminConstantController@updateconstantBanner');
            Route::post('/deleteconstantBanner/', 'AdminConstantController@deleteconstantBanner');

            //profile setting
            Route::get('/profile', 'AdminController@adminProfile');
            Route::post('/updateProfile', 'AdminController@updateProfile');
            Route::post('/updateAdminPassword', 'AdminController@updateAdminPassword');

            //reports
            Route::get('/statscustomers', 'AdminReportsController@statsCustomers');
            Route::get('/statsproductspurchased', 'AdminReportsController@statsProductsPurchased');
            Route::get('/statsproductsliked', 'AdminReportsController@statsProductsLiked');
            Route::get('/outofstock', 'AdminReportsController@outofstock');
            Route::get('/lowinstock', 'AdminReportsController@lowinstock');
            Route::get('/stockin', 'AdminReportsController@stockin');
            Route::post('/productSaleReport', 'AdminReportsController@productSaleReport');

            //Devices and send notification
            Route::get('/devices', 'AdminNotificationController@devices');
            Route::get('/viewdevices/{id}', 'AdminNotificationController@viewdevices');
            Route::get('/listingDevices', 'AdminNotificationController@devices');
            Route::post('/notifyUser/', 'AdminNotificationController@notifyUser');
            Route::get('/notifications/', 'AdminNotificationController@notifications');
            Route::post('/sendNotifications/', 'AdminNotificationController@sendNotifications');
            Route::post('/customerNotification/', 'AdminNotificationController@customerNotification');
            Route::post('/singleUserNotification/', 'AdminNotificationController@singleUserNotification');
            Route::post('/deletedevice/', 'AdminNotificationController@deletedevice');

            //coupons
            Route::get('/coupons', 'AdminCouponsController@coupons');
            Route::get('/addcoupons', 'AdminCouponsController@addcoupons');
            Route::post('/addnewcoupons', 'AdminCouponsController@addnewcoupons');
            Route::get('/editcoupons/{id}', 'AdminCouponsController@editcoupons');
            Route::post('/updatecoupons', 'AdminCouponsController@updatecoupons');
            Route::post('/deletecoupon', 'AdminCouponsController@deletecoupon');
            Route::post('/couponProducts', 'AdminCouponsController@couponProducts');

            //news categories
            Route::get('/newscategories', 'AdminNewsCategoriesController@newscategories');
            Route::get('/addnewscategory', 'AdminNewsCategoriesController@addnewscategory');
            Route::post('/addnewsnewcategory', 'AdminNewsCategoriesController@addnewsnewcategory');
            Route::get('/editnewscategory/{id}', 'AdminNewsCategoriesController@editnewscategory');
            Route::post('/updatenewscategory', 'AdminNewsCategoriesController@updatenewscategory');
            Route::post('/deletenewscategory', 'AdminNewsCategoriesController@deletenewscategory');

            //news
            Route::get('/news', 'AdminNewsController@news');
            Route::get('/addnews', 'AdminNewsController@addnews');
            Route::post('/addnewnews', 'AdminNewsController@addnewnews');
            Route::get('/editnews/{id}', 'AdminNewsController@editnews');
            Route::post('/updatenews', 'AdminNewsController@updatenews');
            Route::post('/deletenews', 'AdminNewsController@deletenews');

            //app pages controller
            Route::get('/pages', 'AdminPagesController@pages');
            Route::get('/addpage', 'AdminPagesController@addpage');
            Route::post('/addnewpage', 'AdminPagesController@addnewpage');
            Route::get('/editpage/{id}', 'AdminPagesController@editpage');
            Route::post('/updatepage', 'AdminPagesController@updatepage');
            Route::get('/pageStatus', 'AdminPagesController@pageStatus');

            //site pages controller
            Route::get('/webpages', 'AdminPagesController@webpages');
            Route::get('/addwebpage', 'AdminPagesController@addwebpage');
            Route::post('/addnewwebpage', 'AdminPagesController@addnewwebpage');
            Route::get('/editwebpage/{id}', 'AdminPagesController@editwebpage');
            Route::post('/updatewebpage', 'AdminPagesController@updatewebpage');
            Route::get('/pageWebStatus', 'AdminPagesController@pageWebStatus');

            //admin managements
            Route::get('/admins', 'AdminController@admins');
            Route::get('/addadmins', 'AdminController@addadmins');
            Route::post('/addnewadmin', 'AdminController@addnewadmin');
            Route::get('/editadmin/{id}', 'AdminController@editadmin');
            Route::post('/updateadmin', 'AdminController@updateadmin');
            Route::post('/deleteadmin', 'AdminController@deleteadmin');

            //admin managements
            Route::get('/manageroles', 'AdminController@manageroles');
            Route::get('/addadmintype', 'AdminController@addadmintype');
            Route::post('/addnewtype', 'AdminController@addnewtype');
            Route::get('/editadmintype/{id}', 'AdminController@editadmintype');
            Route::post('/updatetype', 'AdminController@updatetype');
            Route::post('/deleteadmintype', 'AdminController@deleteadmintype');
            Route::get('/addrole/{id}', 'AdminController@addrole');
            Route::post('/addnewroles', 'AdminController@addnewroles');

            //extra roles
            Route::get('/categoriesroles', 'AdminController@categoriesRoles');
            Route::get('/addcategoriesroles', 'AdminController@addCategoriesRoles');
            Route::post('/addnewcategoriesroles', 'AdminController@addNewCategoriesRoles');
            Route::get('/editcategoriesroles/{id}', 'AdminController@editCategoriesRoles');
            Route::post('/updatecategoriesroles', 'AdminController@updateCategoriesRoles');
            Route::get('/deletecategoriesroles/{id}', 'AdminController@deleteCategoriesRoles');

            // Messages
            Route::get('/messages', 'AdminController@messages');

        });

        //log in
        Route::get('/login', 'AdminController@login');
        Route::post('/checkLogin', 'AdminController@checkLogin');

        //log out
        Route::get('/logout', 'AdminController@logout');
    });

});

/*
|--------------------------------------------------------------------------
| front-end Controller Routes
|--------------------------------------------------------------------------
|
| This section contains all Routes of front-end content
|
|
 */

/********* setting themes dynamically *********/
// default setting
$routeSetting = DB::table('settings')->get();
Theme::set($routeSetting[48]->value);

Route::get('welcome/{locale}', function ($locale) {
    App::setLocale($locale);
    print $locale;
    //
});

Route::group(['namespace' => 'Web'], function () {

//language route
    Route::post('/language-chooser', 'WebSettingController@changeLanguage');
    Route::post('/language/', array(
        //'before' => 'csrf',
        'as' => 'language-chooser',
        'uses' => 'WebSettingController@changeLanguage',
    ));

    Route::get('/setStyle', 'DefaultController@setStyle');
    Route::get('/settheme', 'DefaultController@settheme');
    Route::get('/page', 'DefaultController@page');
    Route::post('/subscribeNotification/', 'CustomersController@subscribeNotification');

    Route::get('/', 'DefaultController@index');
    Route::get('/index', 'DefaultController@index');

    Route::get('/contact-us', 'DefaultController@ContactUs');
    Route::post('/processContactUs', 'DefaultController@processContactUs');

    //news section
    Route::get('/news', 'NewsController@news');
    Route::get('/news-detail/{slug}', 'NewsController@newsDetail');
    Route::post('/loadMoreNews', 'NewsController@loadMoreNews');

    Route::get('/clear-cache', function () {
        $exitCode = Artisan::call('cache:clear');
    });

    /*
    |--------------------------------------------------------------------------
    | categories / products Controller Routes
    |--------------------------------------------------------------------------
    |
    | This section contains all Routes of categories page, products/shop page, product detail.
    |
    |
     */

    Route::get('/shop', 'ProductsController@shop');
    Route::post('/shop', 'ProductsController@shop');
    Route::get('/product-detail/{slug}', 'ProductsController@productDetail');
    Route::post('/filterProducts', 'ProductsController@filterProducts');

    /*
    |--------------------------------------------------------------------------
    | Cart Controller Routes
    |--------------------------------------------------------------------------
    |
    | This section contains customer cart products
    |
     */

    Route::get('/getCart', 'DataController@getCart');

    //getquantity
    Route::post('/getquantity', 'ProductsController@getquantity');

    Route::post('/addToCart', 'CartController@addToCart');
    Route::post('/updatesinglecart', 'CartController@updatesinglecart');
    Route::get('/cartButton', 'CartController@cartButton');

    Route::get('/viewcart', 'CartController@viewcart');
    Route::get('/editcart', 'CartController@editcart');

    Route::post('/updateCart', 'CartController@updateCart');
    Route::get('/deleteCart', 'CartController@deleteCart');
    Route::post('/apply_coupon', 'CartController@apply_coupon');
    Route::get('/removeCoupon/{id}', 'CartController@removeCoupon');

    /*
    |--------------------------------------------------------------------------
    | customer registrations Controller Routes
    |--------------------------------------------------------------------------
    |
    | This section contains all Routes of signup page, login page, forgot password
    | facebook login , google login, shipping address etc.
    |
     */

    Route::get('/login', 'CustomersController@login');
    Route::get('/signup', 'CustomersController@signup');
    Route::post('/process-login', 'CustomersController@processLogin');
    Route::get('/logout', 'CustomersController@logout');
    Route::post('/signupProcess', 'CustomersController@signupProcess');
    Route::get('/forgotPassword', 'CustomersController@forgotPassword');
    Route::get('/recoverPassword', 'CustomersController@recoverPassword');
    Route::post('/processPassword', 'CustomersController@processPassword');

    Route::get('login/{social}', 'CustomersController@socialLogin');
    Route::get('login/{social}/callback', 'CustomersController@handleSocialLoginCallback');
    Route::post('/commentsOrder', 'OrdersController@commentsOrder');

    //zones
    Route::post('/ajaxZones', 'ShippingAddressController@ajaxZones');

    //likeMyProduct
    Route::post('likeMyProduct', 'CustomersController@likeMyProduct');

    //subscribe mailchimp
    Route::get('subscribe', 'WebSettingController@subscribe');
    /*
    |--------------------------------------------------------------------------
    | WEbiste auth path Controller Routes
    |--------------------------------------------------------------------------
    |
    | This section contains all Routes of After login
    |
    |
     */

    Route::group(['middleware' => 'Customer'], function () {
        Route::get('/wishlist', 'CustomersController@wishlist');
        Route::post('/loadMoreWishlist', 'CustomersController@loadMoreWishlist');
        Route::get('/profile', 'CustomersController@profile');
        Route::post('/updateMyProfile', 'CustomersController@updateMyProfile');
        Route::post('/updateMyPassword', 'CustomersController@updateMyPassword');

        Route::get('/shipping-address', 'ShippingAddressController@shippingAddress');
        Route::post('/addMyAddress', 'ShippingAddressController@addMyAddress');
        Route::post('/myDefaultAddress', 'ShippingAddressController@myDefaultAddress');

        Route::post('/update-address', 'ShippingAddressController@updateAddress');
        Route::post('/delete-address', 'ShippingAddressController@deleteAddress');

        Route::get('/checkout', 'OrdersController@checkout');
        Route::post('/checkout_shipping_address', 'OrdersController@checkout_shipping_address');
        Route::post('/checkout_billing_address', 'OrdersController@checkout_billing_address');
        Route::post('/checkout_payment_method', 'OrdersController@checkout_payment_method');
        Route::post('/paymentComponent', 'OrdersController@paymentComponent');
        Route::post('/place_order', 'OrdersController@place_order');
        Route::get('/orders', 'OrdersController@orders');
        Route::post('/updatestatus/', 'OrdersController@updatestatus');
        Route::post('/myorders', 'OrdersController@myorders');
        Route::get('/stripeForm', 'OrdersController@stripeForm');
        Route::get('/view-order/{id}', 'OrdersController@viewOrder');
        Route::post('/pay-instamojo', 'OrdersController@payIinstamojo');

        Route::get('/checkout/hyperpay', 'OrdersController@hyperpay');
        Route::get('/checkout/hyperpay/checkpayment', 'OrdersController@checkpayment');
        Route::post('/checkout/payment/changeresponsestatus', 'OrdersController@changeresponsestatus');

    });

});

/*
|--------------------------------------------------------------------------
| App Controller Routes
|--------------------------------------------------------------------------
|
| This section contains all Routes of application
|
|
 */

Route::group(['namespace' => 'App', 'prefix' => 'app'], function () {

    Route::post('/getcategories', 'CategoriesController@getcategories');

    //registration url

    Route::post('/registerdevices', 'CustomersController@registerdevices');

    //registration url

    Route::post('/processregistration', 'CustomersController@processregistration');

    //update customer info url

    Route::post('/updatecustomerinfo', 'CustomersController@updatecustomerinfo');

    // login url

    Route::post('/processlogin', 'CustomersController@processlogin');

    //social login

    Route::post('/facebookregistration', 'CustomersController@facebookregistration');

    Route::post('/googleregistration', 'CustomersController@googleregistration');

    //push notification setting

    Route::post('/notify_me', 'CustomersController@notify_me');

    // forgot password url

    Route::post('/processforgotpassword', 'CustomersController@processforgotpassword');

    /*

    |--------------------------------------------------------------------------

    | Location Controller Routes

    |--------------------------------------------------------------------------

    |

    | This section contains countries shipping detail

    | This section contains links of affiliated to address

    |

     */

    //get country url

    Route::post('/getcountries', 'LocationController@getcountries');

    //get zone url

    Route::post('/getzones', 'LocationController@getzones');

    //get all address url

    Route::post('/getalladdress', 'LocationController@getalladdress');

    //address url

    Route::post('/addshippingaddress', 'LocationController@addshippingaddress');

    //update address url

    Route::post('/updateshippingaddress', 'LocationController@updateshippingaddress');

    //update default address url

    Route::post('/updatedefaultaddress', 'LocationController@updatedefaultaddress');

    //delete address url

    Route::post('/deleteshippingaddress', 'LocationController@deleteshippingaddress');

    /*

    |--------------------------------------------------------------------------

    | Product Controller Routes

    |--------------------------------------------------------------------------

    |

    | This section contains product data

    | Such as:

    | top seller, Deals, Liked, categroy wise or category individually and detail of every product.

     */

    //get categories

    Route::post('/allcategories', 'MyProductController@allcategories');

    //getAllProducts

    Route::post('/getallproducts', 'MyProductController@getallproducts');

    //like products

    Route::post('/likeproduct', 'MyProductController@likeproduct');

    //unlike products

    Route::post('/unlikeproduct', 'MyProductController@unlikeproduct');

    //get filters

    Route::post('/getfilters', 'MyProductController@getfilters');

    //get getFilterproducts

    Route::post('/getfilterproducts', 'MyProductController@getfilterproducts');

    //get getWishList

    Route::post('/getsearchdata', 'MyProductController@getsearchdata');

    //getquantity

    Route::post('/getquantity', 'MyProductController@getquantity');

    /*

    |--------------------------------------------------------------------------

    | News Controller Routes

    |--------------------------------------------------------------------------

    |

    | This section contains news data

    | Such as:

    | top news or category individually and detail of every news.

     */

    //get categories

    Route::post('/allnewscategories', 'NewsController@allnewscategories');

    //getAllProducts

    Route::post('/getallnews', 'NewsController@getallnews');

    /*

    |--------------------------------------------------------------------------

    | Cart Controller Routes

    |--------------------------------------------------------------------------

    |

    | This section contains customer orders

    |

     */

    //hyperpaytoken

    Route::post('/hyperpaytoken', 'OrderController@hyperpaytoken');

    //hyperpaytoken

    Route::get('/hyperpaypaymentstatus', 'OrderController@hyperpaypaymentstatus');

    //paymentsuccess

    Route::get('/paymentsuccess', 'OrderController@paymentsuccess');

    //paymenterror

    Route::post('/paymenterror', 'OrderController@paymenterror');

    //generateBraintreeToken

    Route::get('/generatebraintreetoken', 'OrderController@generatebraintreetoken');

    //generateBraintreeToken

    Route::get('/instamojotoken', 'OrderController@instamojotoken');

    //add To order

    Route::post('/addtoorder', 'OrderController@addtoorder');

    //updatestatus

    Route::post('/updatestatus/', 'OrderController@updatestatus');

    //get all orders

    Route::post('/getorders', 'OrderController@getorders');

    //get default payment method

    Route::post('/getpaymentmethods', 'OrderController@getpaymentmethods');

    //get shipping / tax Rate

    Route::post('/getrate', 'OrderController@getrate');

    //get Coupon

    Route::post('/getcoupon', 'OrderController@getcoupon');

    /*

    |--------------------------------------------------------------------------

    | Banner Controller Routes

    |--------------------------------------------------------------------------

    |

    | This section contains banners, banner history

    |

     */

    //get banners

    Route::get('/getbanners', 'BannersController@getbanners');

    //banners history

    Route::post('/bannerhistory', 'BannersController@bannerhistory');

    /*

    |--------------------------------------------------------------------------

    | App setting Controller Routes

    |--------------------------------------------------------------------------

    |

    | This section contains app  languages

    |

     */

    Route::get('/sitesetting', 'AppSettingController@sitesetting');

    //old app label

    Route::post('/applabels', 'AppSettingController@applabels');

    //new app label

    Route::get('/applabels3', 'AppSettingController@applabels3');

    Route::post('/contactus', 'AppSettingController@contactus');

    Route::get('/getlanguages', 'AppSettingController@getlanguages');

    /*

    |--------------------------------------------------------------------------

    | Page Controller Routes

    |--------------------------------------------------------------------------

    |

    | This section contains news data

    | Such as:

    | top Page individually and detail of every Page.

     */

    //getAllPages

    Route::post('/getallpages', 'PagesController@getallpages');

    //Chat
    Route::post('/send_message', 'ChatController@sendMessage');
    Route::post('/get_all_messages', 'ChatController@getAllMessages');
    Route::post('/get_chat_rooms', 'ChatController@getChatRooms');

});
