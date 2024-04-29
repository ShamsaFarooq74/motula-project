<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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
Route::get('send-app-notification', 'Admin\CommunicationController@send_comm_app_notification')->name('admin.communication.send-app-notification');
Route::get('send-email', 'Admin\CommunicationController@send_comm_email')->name('admin.communication.send-email');
Route::get('/dump', function(){
    exec('composer dump-autoload');
    echo 'composer dump-autoload complete';
});

 Route::get('/delete-all-images', function () {
     $file = new Illuminate\Filesystem\Filesystem;
     $file->cleanDirectory(public_path().'/images/ads');
     $file->cleanDirectory(public_path().'/images/chat');
     $file->cleanDirectory(public_path().'/images/product-attachments');
     $file->cleanDirectory(public_path().'/images/temporary-product');
 });
Route::get('/home','Web\WebsiteController@home');
Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
});
Route::get('/db-backup', function() {
    Artisan::call('backup:run --only-db --disable-notifications');
    return 'database backup successfully';
});

Route::get('/migrate', function() {
    Artisan::call('migrate');
});
Route::get('/pdf-to-image', 'Web\WebsiteController@pdfToImage');
Route::post('/reset-password', 'Web\WebsiteController@resetpass')->name('reset.password');
    Auth::routes();
    Route::get('/index', 'Admin\ClientController@welcome');
    Route::group(['middleware' => 'is_loggin'], function () {
        //Route::get()
        Route::get('/','Web\AuthController@loginScreen')->name('web.login');
        Route::post('/login','Web\AuthController@login')->name('login.client');
        Route::get('/signup','Web\AuthController@signupscreen')->name('web.signup');
        Route::post('/signup','Web\AuthController@signup')->name('signup.client');
    });
Route::get('/home','Web\WebsiteController@index')->name('index');
Route::get('/all-courses','Web\WebsiteController@allCourses')->name('all.courses');
Route::get('/contact-us','Web\WebsiteController@contactUs')->name('contact.us');
Route::get('/privacy-policy', function () {
    return view('web.privacy-policy');
})->name('privacy.policy');
Route::get('/course-detail/{id}','Web\WebsiteController@courseDetails')->name('course.detail');
Route::get('/join-course/{id}','Web\WebsiteController@joinCourse')->name('join.course');
Route::get('/course-session/{id}','Web\WebsiteController@courseSession')->name('course.sessions');
Route::POST('/check-paid-quiz','Web\WebsiteController@checkPaidQuiz')->name('check.paid.quiz');
Route::post('/session-detail','Web\WebsiteController@sessionDetail')->name('session.detail');
Route::post('/session-description','Web\WebsiteController@courseDesDetail')->name('course.des.detail');
Route::post('/session-quiz','Web\WebsiteController@sessionQuizDetail')->name('session.quiz.detail');
Route::get('/all-blogs','Web\WebsiteController@allBlogs')->name('all.blogs');
Route::get('/blog-detail/{id}','Web\WebsiteController@blogDetail')->name('blog.detail');
Route::get('/welcome','Web\AuthController@welcomeScreen')->name('web.welcome');
Route::post('/welcome','Web\AuthController@selectRole')->name('web.selectRole');
Route::post('/signupverify','Web\AuthController@signupverify')->name('signup.verify');
Route::post('/signupEmailVerify','Web\AuthController@signupEmailverify')->name('signup.email.verify');
Route::post('/sendcontactdetail','Web\WebsiteController@contactusdetail')->name('contact.detail');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', function(){
    Auth::logout();
    return redirect('/');
    })->name('logout');
    Route::get('/website/feedback', function () {
        return view('web.feedback');
    });
    // main routes
    Route::post('/check-attempted-quiz','Web\WebsiteController@checkAttemptedQuiz')->name('check.attempted.quiz');
    Route::post('/get-quiz-question','Web\WebsiteController@getQuizQuestion')->name('get.quiz.question');
    Route::post('/save-user-option','Web\WebsiteController@saveUserOption')->name('save.user.option');
    Route::get('/profile','Web\WebsiteController@userProfile')->name('user.profile');
    Route::get('/profileView/{id}','Web\WebsiteController@userProfileView')->name('user.profile.view');
    Route::get('/update-profile','Web\WebsiteController@updateProfile')->name('update.profile');
    Route::get('/delete-profile-image/{id}', 'Web\WebsiteController@deleteProfileImage')->name('delete.profile.image');
    Route::post('/update-profile-data','Web\WebsiteController@saveProfile')->name('save.profile');
    Route::post('/current-password','Web\WebsiteController@currentPasssword')->name('current.password');
    Route::post('/quiz-result','Web\WebsiteController@quizResult')->name('show.results');
    Route::post('/complete-quiz-result','Web\WebsiteController@completeQuizResult')->name('load.complete.quiz');

    Route::get('/switch','Web\WebsiteController@switchrole')->name('switch.role');

    Route::get('/product/{id?}','Web\WebsiteController@productDetail')->name('product.detail');
    Route::get('/seller-profile/{sellerId?}','Web\WebsiteController@sellerDetails')->name('seller.detail');
    Route::get('/change-password', function () {
        return view('web.change-password');
    });
    Route::get('/all-category','Web\WebsiteController@allcategory')->name('all.category');
    Route::post('/edit-profile', 'Web\WebsiteController@editprofile')->name('edit.profile');
    Route::get('/stripe/{id}', 'Web\StripePaymentController@stripe')->name('stripe');
    Route::post('/stripe', 'Web\StripePaymentController@stripePost')->name('stripe.post');

});
// Route::get('/category','Admin\DashboardController@lessonTopic')->name('lesson.topic');

// Route::group(['middleware' => 'auth', 'is.admin'], function () {
    Route::middleware(['is_admin'])->group(function () {
    Route::get('/features','Admin\DashboardController@lessonBank')->name('lesson.bank');
    Route::POST('/save-lessons','Admin\DashboardController@saveLesson')->name('save.lesson');
    Route::POST('/update-feature','Admin\DashboardController@updateFeature')->name('update.feature');
    Route::POST('/edit-lesson','Admin\DashboardController@editLesson')->name('edit.lesson');
    Route::get('/delete-lesson/{id}','Admin\DashboardController@deleteLesson')->name('delete.lesson');
    Route::get('/category','Admin\DashboardController@lessonTopic')->name('lesson.topic');
    Route::POST('/save-topic','Admin\DashboardController@saveTopic')->name('save.topic');
    Route::POST('/update-category','Admin\DashboardController@updateCategory')->name('update.category');
    Route::get('/delete-topic/{id}','Admin\DashboardController@deleteTopic')->name('delete.topic');
    Route::POST('/edit-topic','Admin\DashboardController@editTopic')->name('edit.topic');
    Route::POST('/fetch-topic','Admin\DashboardController@fetchTopics')->name('fetch.topic');
    Route::POST('/fetch-courses','Admin\DashboardController@fetchCourses')->name('fetch.courses');
    Route::POST('/save-files','Admin\DashboardController@saveFile')->name('save.files');
    Route::get('/delete-quiz/{id}','Admin\DashboardController@deleteQuiz')->name('delete.quiz');
    Route::get('/delete-file/{id}','Admin\DashboardController@deleteFile')->name('delete.file');
    Route::POST('/edit-file','Admin\DashboardController@editFile')->name('edit.file');
    Route::get('/course-plans','Admin\DashboardController@coursePlans')->name('course.plans');
    Route::get('/appearance-settings','Admin\ThemeSettingsController@AppearanceSetting')->name('appearance.setting');
    Route::get('/new-appearance-setting','Admin\ThemeSettingsController@NewAppearanceSetting')->name('new.appearance.setting');
    Route::get('/set-default-theme/{id}','Admin\ThemeSettingsController@setDefaultTheme')->name('set.default.theme');
    Route::get('/delete-theme/{id}','Admin\ThemeSettingsController@deleteTheme')->name('delete.theme');
    Route::get('/theme-setting','Admin\ThemeSettingsController@ThemeSetting')->name('theme.setting');
    Route::get('/update-appearance/{id}','Admin\ThemeSettingsController@updateAppearance')->name('update.appearance');
    Route::PUT('/updatetheme','Admin\ThemeSettingsController@updatetheme')->name('update.theme');
    Route::get('/smtp','Admin\ThemeSettingsController@Smtp')->name('smtp');
    Route::PUT('/add-smtp','Admin\ThemeSettingsController@addSMTP')->name('addSMTP');
    Route::get('/stripe-settings','Admin\ThemeSettingsController@Stripe')->name('strip');
    Route::post('/update-stripe','Admin\ThemeSettingsController@updateStripe')->name('update.stripe');
    Route::get('/add-blog','Admin\DashboardController@AddBlog')->name('add.blog');
    Route::get('/blogs','Admin\DashboardController@Blog')->name('blog');
    Route::post('/save-blog','Admin\DashboardController@saveBlog')->name('save.blog');
    Route::get('/edit-blog/{id}','Admin\DashboardController@editBlog')->name('edit.blog');
    Route::get('/delete-blog/{id}','Admin\DashboardController@deleteBlog')->name('delete.blog');
    Route::post('/save-appearance','Admin\ThemeSettingsController@saveAppearance')->name('save.appearance');



    Route::post('/save-course','Admin\DashboardController@saveCourse')->name('save.course');
    Route::get('/delete-course/{id}','Admin\DashboardController@deleteCourse')->name('delete.course');
    Route::post('/edit-course','Admin\DashboardController@editCourse')->name('edit.course');
    //tester quizes
    Route::get('/sub-category','Admin\DashboardController@testerQuizzes')->name('show.tester.quiz');
    Route::get('/get-sub-category','Admin\DashboardController@getSubCategory')->name('get.sub_categories');
    Route::get('/get-sub-category-region','Admin\DashboardController@getSubCategoryRegion')->name('get.sub_categories.region');
    Route::POST('/save-tester-quizzes','Admin\DashboardController@saveTesterQuizzes')->name('save.tester.quiz');
    Route::POST('/update-sub-category','Admin\DashboardController@updateSubCategory')->name('update.sub.category');
    Route::POST('/edit-tester-quizzes','Admin\DashboardController@editTesterQuizzes')->name('edit.tester.quiz');
    Route::get('/view/file/{category}/{sub_category}/{child?}/{sub_child?}','Admin\DashboardController@viewFile')->name('view.file');
    Route::get('/subscriptions','Admin\DashboardController@subscriptions')->name('user.subscriptions');
    
    //Childs and Sub_Childs
    Route::get('/sub-catetogery-child','Admin\DashboardController@subCatChild')->name('sub_cate.child');
    Route::get('/get-sub-catetogery-child','Admin\DashboardController@getChild')->name('get.child');
    Route::POST('/save-sub-category-child','Admin\DashboardController@saveSubCatChild')->name('save.subcat.child');
    Route::POST('/update-sub-category-child','Admin\DashboardController@updateSubCatChild')->name('update.subcat.child');
    Route::POST('/edit-child','Admin\DashboardController@editChild')->name('edit.sub_cat.child');
    Route::get('/delete-child/{id}','Admin\DashboardController@deleteChild')->name('delete.child');
    //sub_childs
    Route::get('/sub-catetogery-sub-child','Admin\DashboardController@subCatSubChild')->name('sub_cate.sub_child');
    Route::POST('/save-sub-category-sub-child','Admin\DashboardController@saveSubCatSubChild')->name('save.subcat.subchild');
    Route::POST('/update-sub-category-sub-child','Admin\DashboardController@updateSubCatSubChild')->name('update.subcat.subchild');
    Route::POST('/edit-sub-child','Admin\DashboardController@editSubChild')->name('edit.sub.child');
    Route::get('/delete-sub-child/{id}','Admin\DashboardController@deleteSubChild')->name('delete.sub.child');
    //Files
    Route::get('/files','Admin\DashboardController@Files')->name('files');
    Route::get('/file-list','Admin\DashboardController@fileList')->name('quiz');
    Route::get('/files-type','Admin\DashboardController@fileType')->name('file.type');
    Route::POST('/save-files-type','Admin\DashboardController@saveFileType')->name('save.type');
    Route::POST('/update-files-type','Admin\DashboardController@updateFileType')->name('update.type');
    Route::POST('/edit-files-type','Admin\DashboardController@editFyleType')->name('edit.file.type');
    Route::get('/delete-files-type/{id}','Admin\DashboardController@deleteFileType')->name('delete.file.type');
    //Admin
    Route::get('/admins','Admin\DashboardController@Admin')->name('admins');
    Route::get('/edit-users','Admin\DashboardController@editusers')->name('edit.users');
    Route::POST('/save-user','Admin\DashboardController@saveAdmis')->name('save.users');
    //Editor
    Route::get('/editor','Admin\DashboardController@Editor')->name('editor');
    Route::POST('/update-editor','Admin\DashboardController@UpdateEditor')->name('Update.Editor');

    //Region
    Route::get('/regions','Admin\ThemeSettingsController@regions')->name('regions');
    Route::post('/save-region','Admin\ThemeSettingsController@SaveRegion')->name('save.region');
    Route::POST('/edit-region','Admin\ThemeSettingsController@EditRegion')->name('edit.region');
    Route::get('/delete-region/{id?}','Admin\ThemeSettingsController@DeleteRegion')->name('delete.region');

    Route::get('/users','Admin\DashboardController@Users')->name('users');
    Route::get('/users-profile/{id}','Admin\DashboardController@userProfile')->name('users.profile');
    Route::get('/users-profile-detail/{id}/{user_id}','Admin\DashboardController@userProfileDetail')->name('users.profile.detail');
    Route::get('/edit-user','Admin\DashboardController@edituser')->name('edit.user');
    Route::POST('/save-users','Admin\DashboardController@UpdateUser')->name('save.user');
    Route::get('/delete-user/{id}','Admin\DashboardController@deleteuser')->name('delete.user');





    Route::get('/questions','Admin\DashboardController@Questions')->name('questions');
    Route::get('/add-questions','Admin\DashboardController@MultipleQuestions')->name('Multiple.questions');
    Route::POST('/save-questions','Admin\DashboardController@saveMultipleQuestions')->name('save.multiple.question');
    Route::get('/delete-question/{id}','Admin\DashboardController@deleteMultipleQuestions')->name('delete.question');
    Route::get('/edit-question/{id}','Admin\DashboardController@editMultipleQuestions')->name('edit.question');
    Route::get('/drag-and-drop','Admin\DashboardController@DragDrop')->name('drag.drop');
    Route::POST('/save-drag-and-drop','Admin\DashboardController@saveDragDropQuestion')->name('save.drag.drop.question');




    Route::get('/dashboard', function() {

        return redirect()->route('dashboard');
        // return view('admin.dashboard');
    });


    // Route::prefix('communication')->group(function () {

    //     Route::get('index', 'Admin\CommunicationController@index')->name('admin.communication.index');
    //     Route::post('email/store', 'Admin\CommunicationController@storeEmail')->name('admin.communication.email.store');
    //     Route::post('sms/store', 'Admin\CommunicationController@storeSMS')->name('admin.communication.sms.store');
    //     Route::post('app-notification/store', 'Admin\CommunicationController@storeAppNotification')->name('admin.communication.app-notification.store');

    //     Route::get('send-sms', 'Admin\CommunicationController@send_comm_sms')->name('admin.communication.send-sms');
    // });

});
