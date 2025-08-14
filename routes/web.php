<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CollegeAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\CourseDetailController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CollegeDashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AlgorithmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NearestAlgorithmController;

Auth::routes();

// Admin authentication routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.loginFrom');
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.registerFrom');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});
// College authentication routes
Route::prefix('college')->group(function () {
    Route::get('/login', [CollegeAuthController::class, 'showLoginForm'])->name('college.loginFrom');
    Route::get('/register', [CollegeAuthController::class, 'showRegisterForm'])->name('college.registerFrom');
    Route::post('/register', [CollegeAuthController::class, 'register'])->name('college.register');
    Route::post('/college/login', [CollegeAuthController::class, 'login'])->name('college.login');
    Route::get('/logout', [CollegeAuthController::class, 'logout'])->name('college.logout');
});
// Student authentication routes
Route::prefix('student')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('student.loginFrom');
    Route::get('/register', [StudentAuthController::class, 'showRegisterForm'])->name('student.registerFrom');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('student.register');
    Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login');
    Route::get('/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
});


Route::get('/myprofile-edit', [StudentController::class, 'edit'])->name('student.editForm');
Route::post('/myprofile-update/{id}', [StudentController::class, 'update'])->name('students.update');

// Route::put('/myprofile-edit',[StudentController::class, 'update'])->name('students')
//     return view('home.myprofileedit');
// });
Route::get('storage/logo/{filename}', [ImageController::class, 'show'])->name('logo.show');

//open link
// Route::get('/', function () {
//     return view('home.index');
// });
Route::get('/', [StudentAuthController::class, 'getHome'])->name('home');


//change change-password routes
Route::post('/student/update-password', [StudentAuthController::class, 'updatePassword'])->name('student.updatePassword');
// Route::post('/coursedetail/store', [CourseDetailController::class, 'store'])->name('coursedetail.store');
// Route::get('/', [HomeController::class, 'home'])->name('home');

// Route::get('/recommend', function () {
//     return view('home.recommend');
// });

Route::get('/college', [CollegeController::class, 'showForStudent'])->name('college.showForStudent');
Route::post('/college/update-password', [CollegeAuthController::class, 'updatePassword'])->name('college.updatePassword');
Route::get('/college/change-password', [CollegeAuthController::class, 'getChangeView'])->name('college.change-password');

Route::post('/admin/update-password', [AdminAuthController::class, 'updatePassword'])->name('admin.updatePassword');
Route::get('/admin/change-password', [AdminAuthController::class, 'getChangeView'])->name('admin.change-password');

Route::get('/aboutus', function () {
    return view('home.aboutus');
});
Route::get('/contact', function () {
    return view('home.contact');
});
Route::get('/college-signup', function () {
    return view('auth.college-register');
});
Route::get('/view/colleges', function () {
    return view('home.viewCourse');
});
Route::get('/view/courses/colleges', function () {
    return view('home.viewCourseCollege');
});
Route::get('/view/course-detail', function () {
    return view('home.viewCollegeCourseDetail');
});


Route::get('/myprofile', [StudentController::class, 'getById'])->name('student.getById');
// Route::post('/myprofile/update', [StudentController::class, 'update'])->name('students.update');


Route::get('/changepassword', function () {
    return view('home.change');
});




Route::get('/view/colleges', function () {
    return view('home.viewCourse');
});

// Route::get('/courses', function () {
//     return view('home.courses');
// });

//course controller
Route::get('/admin/course', [CourseController::class, 'index'])->name('admin.course.index');
Route::get('/admin/course-show', [CourseController::class, 'courseShow'])->name('admin.course.show');
Route::post('/admin/course-store', [CourseController::class, 'courseStore'])->name('admin.course.store');

Route::get('/courses', [CourseController::class, 'showForStudent'])->name('course.showForStudent');
Route::get('/view/course/description/{id}', [CourseController::class, 'getByIdforStudent'])->name('course.getByIdforStudent');
Route::get('/view/course/description/college/{id}', [CourseDetailController::class, 'getCollegeByCourseId'])->name('course.getCollegeByCourseId');

Route::get('/college/detail/course/description/{id}', [CourseDetailController::class, 'getById'])->name('coursedetail.getById');
// Route::get('/inquiry/create/{id}', [InquiryController::class, 'makeInquiry'])->name('inquity.createForStudent');


Route::post('/register/student', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/show', [StudentController::class, 'show'])->name('students.show');
// Route::get('/', function () {
//     return view('home.index');
// })->name('home');

Route::get('/college/create', [CollegeController::class, 'create'])->name('college.create');
Route::post('/college/store', [CollegeController::class, 'store'])->name('college.store');
Route::get('/college/show', [CollegeController::class, 'show'])->name('college.show');
Route::get('/college/course/view/{id}', [CollegeController::class, 'getById'])->name('college.getById');



Route::get('/collegesignupshow', function(){
    return view('home.collegeSignUP');
});



//CourseDetail
Route::get('/coursedetail', function(){
    return view('home.coursedetail');
});
Route::get('/college/create/course-detail', [CourseDetailController::class, 'courseDetailCreateForm'])->name('coursedetail.courseDetailCreateForm');
Route::post('/coursedetail/store', [CourseDetailController::class, 'store'])->name('coursedetail.store');
Route::get('/coursedetail/show', [CourseDetailController::class, 'show'])->name('coursedetail.show');
Route::get('/coursedetail/delete/{id}', [CourseDetailController::class, 'destroy'])->name('coursedetail.destroy');
Route::get('/admin/coursedetail/delete/{id}', [CourseDetailController::class, 'destroyForAdmin'])->name('admin.coursedetail.destroy');
Route::get('/coursedetail/edit/{id}',[CourseDetailController::class,'edit']);
Route::post('/coursedetail/update/{id}', [CourseDetailController::class, 'update'])->name('coursedetail.update');




// Route::get('/admin/student/show', function(){
//     return view('admin.studentshow');
// });
// Route::get('/admin/course/show', function(){
//     return view('admin.courseshow');
// });


//contact routes
Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/update-status/{id}', [ContactController::class, 'updateStatus'])->name('contact.updateStatus');
Route::get('/contact/delete/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'count'])->name('adminController.count');
    Route::get('/admin/edit-profile', function(){
        return view('admin.editProfile');
    });
    Route::get('/admin/course-detail/show', [CourseDetailController::class, 'show'])->name('coursedetail.show');
    Route::get('/admin/coursedetail/approve/{id}', [CourseDetailController::class, 'approve'])->name('admin.coursedetail.approve');
    Route::get('/admin/contact/show', [ContactController::class, 'show'])->name('contact.show');
    Route::get('/admin/college/show', [CollegeController::class, 'show'])->name('college.show');
    Route::get('/admin/student/show', [StudentController::class, 'show'])->name('students.show');
    Route::get('/admin/course/show', [CourseController::class, 'show'])->name('course.show');
    Route::get('/admin/course/view/{id}', [CourseController::class, 'getById'])->name('course.getById');
    Route::get('/admin/inquiry/show', [InquiryController::class, 'showForAdmin'])->name('inquiry.showForAdmin');
    Route::get('/admin/inquiry/edit/{id}', [InquiryController::class, 'editForAdmin'])->name('admin.inquiryedit');
    Route::get('/admin/student/detail/{id}', [StudentController::class, 'getByIdForAdmin'])->name('students.getByIdForAdmin');
    Route::get('/admin/college/detail/{id}', [CollegeController::class, 'getByIdForAdmin'])->name('college.getByIdForAdmin');
    Route::get('/admin/college/approve/{id}', [CollegeController::class, 'approve'])->name('admin.college.approve');
    Route::get('/admin/college/reject/{id}', [CollegeController::class, 'reject'])->name('admin.college.reject');

});


Route::middleware(['auth:student'])->group(function () {
});



Route::middleware(['auth:college'])->group(function () {


});

Route::get('/college/dashboard', [CollegeDashboardController::class, 'count'])->name('college.dashboard');
Route::get('/college/edit-profile/', [CollegeController::class, 'getEditForm'])->name('college.editForm');
Route::post('/college/update/', [CollegeController::class, 'update'])->name('college.update');
Route::get('/college/view-inquiry', [InquiryController::class, 'showForCollege'])->name('college.inquiryshow')->middleware('auth:college');
Route::get('/college/inquiry/edit/{id}', [InquiryController::class, 'editForCollege'])->name('college.inquiryedit')->middleware('auth:college');
Route::get('/college/course-detail', [CourseDetailController::class, 'showForCollege'])->name('college.coursedetail.show');
Route::get('/college/coursedetail/edit/{id}', [CourseDetailController::class, 'editCourseDetail'])->name('coursedetail.editCourseDetail');
Route::put('/coursedetail/update/{id}', [CourseDetailController::class, 'updateForCollege'])->name('college-coursedetail.update');

Route::get('/college/detail/{id}', [CollegeController::class, 'getByIdForStudent'])->name('college.getByIdForStudent');
Route::get('/college/inquiry/givedate', function(){
    return view('college.inquirygivedate');
});

Route::get('/college/course/view', function(){
    return view('college.viewcollegedes');
});
Route::get('/college/course', [CourseController::class, 'showForCollege'])->name('college.show-course');
Route::get('/college/course/view/{id}', [CourseController::class, 'getByIdForCollege'])->name('college.show-course-detail');

// //for admin
// Route::get('/admin/course', [CourseController::class, 'showForCollege'])->name('admin.show-course');
// Route::get('/admin/course/view/{id}', [CourseController::class, 'getByIdForCollege'])->name('admin.show-course-detail');


//recommend
Route::get('/recommend', [AlgorithmController::class, 'recommend'])->name('algorithm.recommend');

//nearest college
Route::get('/nearest-college', [NearestAlgorithmController::class, 'index'])->name('home.nearest');
Route::get('/nearest-college/store', [NearestAlgorithmController::class, 'findNearestCollege'])->name('algorithm.nearest');


//inquiry routes
Route::get('/inquiry', [StudentController::class, 'getInquiries'])->name('student.getInquiries')->middleware('auth:student');
Route::get('/inquiry/create', [InquiryController::class, 'create'])->name('inquiry.create');
Route::post('/inquiry/store', [InquiryController::class, 'store'])->name('inquiry.store');
Route::get('/inquiry/show', [InquiryController::class, 'show'])->name('inquiry.show');
Route::get('/inquiry/edit/{id}', [InquiryController::class, 'edit'])->name('inquiry.edit');
Route::post('/inquiry/update/{id}', [InquiryController::class, 'update'])->name('inquiry.update');
Route::get('/inquiry/delete/{id}', [InquiryController::class, 'destroy'])->name('inquiry.destroy');

Route::get('/college/delete/{id}', [CollegeController::class, 'destroy'])->name('college.destroy');
Route::get('/student/delete/{id}', [StudentController::class, 'destroy'])->name('student.destroy');



//inquiry new
Route::get('/inquiry-form/{coursedetail_id}', [InquiryController::class, 'inquiryForm'])->name('home.inquiry.form')->middleware('auth:student');
Route::post('/inquiry-form/store', [InquiryController::class, 'inquiryFormStore'])->name('home.inquiry.form.store')->middleware('auth:student');

Route::get('/colleges/{college_id}/inquiries', [InquiryController::class, 'getInquiriesByCollege'])->name('home.inquiry.college');

