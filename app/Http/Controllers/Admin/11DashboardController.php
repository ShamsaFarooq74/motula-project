<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Unit;
use App\Http\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\NotificationEmail;
use App\Http\Models\Setting;
use App\Http\Models\SubCatChild;
use App\Http\Models\SubCatSubChild;
use App\Http\Models\Blogs;
use App\Http\Models\Category;
use App\Http\Models\Files;
use App\Http\Models\Feature;
use App\Http\Models\SubCategory;
use App\Http\Models\FileContent;
use App\Http\Models\FileType;
use App\Http\Models\BlogTypes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;



class DashboardController extends Controller
{
    public function lessonBank()
    {
        $getLessons = Feature::where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        return view('admin.lesson-bank', compact('getLessons'));
    }
    public function deleteLesson($id)
    {
        $getLesson = Feature::find($id);
        if ($getLesson) {
            $getLesson->is_delete = '1';
            $getLesson->save();
            if ($getLesson) {
                Session::flash('success', 'Company Deleted Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Company', 'Lesson Deleted Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    public function saveLesson(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'feature' => 'required',
        ]);
    
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $existingCompany = Feature::where('company_name', $request->feature)->first();
        if($existingCompany !=null && $existingCompany->is_delete === "1"){
            $existingCompany->is_delete = "0";
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move(public_path('assets/company'), $filename);
                if ($existingCompany->image) {
                    $existingImagePath = public_path('assets/company/') . $existingCompany->image;
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }
    
                $existingCompany->image = $filename;
            } else {
                $existingCompany->image;
            }
            $existingCompany->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $existingCompany->update();
            Session::flash('success', 'Feature Added Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Feature Added Successfully');
        } elseif ($existingCompany) {
            Session::flash('error', 'The Feature Already Exists!');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'The Feature Already Exists!');
        } else {
            $company = new Feature();
            $company->company_name = $request->feature;
            $company->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            if ($request->has('image')) {
                $files = $request->image;
                $companyImage = date("dmyHis.") . '_' . $files->getClientOriginalName();
                // return public_path();
                $files->move(public_path() . '/assets/company/', $companyImage);
                $company->image = $companyImage;
            }
            $company->save();
            if ($company) {
                Session::flash('success', 'Feature Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'Feature Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    public function updateFeature(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'feature' => 'required|unique:companies,company_name,' . $request->company_id,
        ]);
    
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $updateCompany = Feature::find($request->company_id);
        $updateCompany->company_name = $request->feature;
        $uploadPath = public_path('assets/company/');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/company'), $filename);

            // Unlink existing image
            if ($updateCompany->image) {
                $existingImagePath = public_path('assets/company/') . $updateCompany->image;
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }

            $updateCompany->image = $filename;
        } else {
            $updateCompany->image;
        }
        $updateCompany->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $updateCompany->save();
        if ($updateCompany) {
            Session::flash('success', 'Feature Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Feature Updated Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function editLesson(Request $request)
    {
        $getLesson = Feature::where('is_delete', '0')->where('id', $request->company_id)->first();
        if ($getLesson) {
            return response()->json(['success' => '1', 'data' => $getLesson]);
        } else {
            return response()->json(['error' => '1', 'data' => '']);
        }
    }
    public function lessonTopic()
    {
        $getTopics = DB::table("categories")->where('categories.is_delete', '0')
        ->leftJoin('companies', 'categories.company_id', '=', 'companies.id')
        ->where('companies.is_delete','0')->where('companies.is_active','1')
        ->select('categories.*','companies.company_name')
        ->orderBy('categories.priority', 'asc')->get();
        $getCompany = Feature::where('is_delete', '0')->where('is_active','1')->orderBy('created_at', 'DESC')->get();
        return view('admin.lesson-topic', compact('getTopics', 'getCompany'));
    }

    public function saveTopic(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_name' => 'required',
            'company_id' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $existingCategory = Category::where('company_id',$request->company_id)->where('category_name', $request->category_name)->first();
        if($existingCategory !=null && $existingCategory->is_delete === "1"){
            $existingCategory->is_delete = "0";
            $existingCategory->company_id = $request->company_id;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move(public_path('assets/category'), $filename);
                if ($existingCategory->image) {
                    $existingImagePath = public_path('assets/category/') . $existingCategory->image;
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }
                $existingCategory->image = $filename;
            } else {
                $existingCategory->image;
            }
            $existingCategory->priority = $request->priority;
            $existingCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $existingCategory->update();
            Session::flash('success', 'Category Added Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Category Added Successfully');
        } elseif ($existingCategory) {
            Session::flash('error', 'The Category Already Exists!');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'The Category Already Exists!');
        } else {
            $saveCategory = new Category();
            $saveCategory->category_name = $request->category_name;
            $saveCategory->company_id = $request->company_id;
            if ($request->has('image')) {
                $files = $request->image;
                $categoryImage = date("dmyHis.") . '_' . $files->getClientOriginalName();
                // return public_path();
                $files->move(public_path() . '/assets/category/', $categoryImage);
                $saveCategory->image = $categoryImage;
            }
            $saveCategory->priority = $request->priority;
            $saveCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $saveCategory->save();
            if ($saveCategory) {
                Session::flash('success', 'Category Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'Category Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    public function updateCategory(Request $request){
        $validate = Validator::make($request->all(), [
            'category_name' => 'required',
            'company_id' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $existingCategory = Category::where('id', '!=', $request->category_id)->where('company_id',$request->company_id)->where('category_name', $request->category_name)->first();
        if($existingCategory){
            Session::flash('error', "Category Already added.!");
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $updateCategory = Category::find($request->category_id);
        $updateCategory->category_name = $request->category_name;
        $updateCategory->company_id = $request->company_id;
        $uploadPath = public_path('assets/category/');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/category'), $filename);

            // Unlink existing image
            if ($updateCategory->image) {
                $existingImagePath = public_path('assets/category/') . $updateCategory->image;
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
            $updateCategory->image = $filename;
        } else {
            $updateCategory->image;
        }
        $updateCategory->priority = $request->priority;
        $updateCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $updateCategory->update();
        if ($updateCategory) {
            Session::flash('success', 'Category Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Category Updated Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function deleteTopic($id)
    {
        $deleteTopic = Category::find($id);
        $deleteTopic->is_delete = '1';
        $deleteTopic->save();
        if ($deleteTopic) {
            Session::flash('success', 'Category Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Category Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function editTopic(Request $request)
    {
        $getTopic = Category::find($request->category_id);
        $getLessons = Feature::where('is_delete', '0')->where('is_active','1')->get();
        if ($getTopic) {
            return response()->json(['success' => '1', 'data' => $getTopic, 'company' => $getLessons]);
        } else {
            return response()->json(['error' => '1', 'data' => '']);
        }
    }
    public function fetchTopics(Request $request)
    {
        $getAllTopics = Category::where('is_active', '1')->where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        $allTopics = array();
        foreach ($getAllTopics as $topics) {
            if (SubCategory::where('quiz_type', 'PAID')->where('topic_id', $topics->id)->where('quiz_test_type', $request->quiz_type)->where('is_active', '1')->where('is_delete', '0')->exists()) {
                continue;
            } else {
                $allTopics[] = $topics;
            }
        }
        return response()->json(['success' => '1', 'data' => $allTopics]);
    }
    public function fetchCourses(Request $request)
    {
        $getAllCourses = Courses::where('is_active', '1')->where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        $allCourses = array();
        foreach ($getAllCourses as $course) {
            if (SubCategory::where('quiz_type', 'MOCK')->where('course_id', $course->id)->where('quiz_test_type', $request->quiz_type)->where('is_active', '1')->where('is_delete', '0')->exists()) {
                continue;
            } else {
                $allCourses[] = $course;
            }
        }
        return response()->json(['success' => '1', 'data' => $allCourses]);
    }
    public function deleteQuiz($id)
    {
        $getQuiz = SubCategory::find($id);
        $getQuiz->is_delete = '1';
        $getQuiz->update();
        if ($getQuiz) {
            Session::flash('success', 'Sub Category Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Category Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function editFile(Request $request)
    {
        $getSubCategory = SubCategory::where('is_delete', '0')->where('is_active','1')->orderBy('created_at', 'DESC')->get();
        $getfiles = Files::where('is_delete', '0')->where('id', $request->file_id)->first();
        $country = Country::where('is_deleted', 'N')->get();
        if ($getfiles) {
            return response()->json(['success' => '1', 'data' => $getfiles, 'subCat' => $getSubCategory, 'country' => $country]);
        } else {
            return response()->json(['error' => '1', 'data' => '']);
        }
    }
    public function coursePlans()
    {
        $currencySymbol = Setting::where('perimeter', 'base_currency')->first()['value'];

        $getCourses = Courses::where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        return view('admin.course-plans', compact('getCourses', 'currencySymbol'));
    }

    public function saveCourse(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'course_title' => 'required',
            'subtitle' => 'required',
            'course_type' => 'required',
            'duration' => 'required',
            'price' => 'required',
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        if ($request->course_id != null) {
            $addCourse = Courses::find($request->course_id);
        } else {
            $addCourse = new Courses();
        }
        $content = $request->description;
        $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
        $dom = new \DomDocument('1.0', 'utf-8');
        @$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imageFile = $dom->getElementsByTagName('imageFile');
        foreach ($imageFile as $item => $image) {
            $data = $image->getAttribute('src');
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $imgeData = base64_decode($data);
            $image_name = "/upload/" . time() . $item . '.png';
            $path = public_path() . '/assets/course-attachments/' . $image_name;
            file_put_contents($path, $imgeData);
            $image->removeAttribute('src');
            $image->setAttribute('src', $image_name);
        }
        $content = $dom->saveHTML();
        $filename = null;
        if ($request->has('blog_id') && $request->blog_id != null) {
            $CreateBlog = Blogs::find($request->blog_id);
            $filename = $CreateBlog->image;
        } else {
            $CreateBlog = new Blogs;
        }

        $CreateBlog->blog_title = $request->blog_title;
        $CreateBlog->description = $content;


        $addCourse->course_title = $request->course_title;
        $addCourse->sub_title = $request->subtitle;
        $addCourse->course_type = $request->course_type;
        $addCourse->duration = $request->duration;
        $addCourse->description = $request->description;
        $addCourse->price = $request->price;
        $addCourse->added_by = Auth::user()->id;
        $addCourse->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        if ($request->has('image')) {
            $files = $request->image;
            $courseImage = date("dmyHis.") . '_' . $files->getClientOriginalName();
            // return public_path();
            $files->move(public_path() . '/assets/course-attachments/', $courseImage);
            $addCourse->image = $courseImage;
        }
        $addCourse->save();
        if ($addCourse) {
            if ($request->course_id != null) {
                Session::flash('success', 'Course updated Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'Course updated Successfully');
            } else {
                Session::flash('success', 'Course Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'Course Added Successfully');
            }
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');

        }
    }
    public function deleteCourse($id)
    {
        $getCourse = Courses::find($id);
        $getCourse->is_delete = '1';
        $getCourse->update();
        if ($getCourse) {
            Session::flash('success', 'Course Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Course Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function editCourse(Request $request)
    {
        $getCourse = Courses::find($request->course_id);
        if ($getCourse) {
            return response()->json(['success' => '1', 'data' => $getCourse]);
        } else {
            return response()->json(['error' => '1', 'data' => '']);
        }
    }
    public function updateCourse(Request $request)
    {
        return $request->all();
    }
    //Admin
    public function Admin()
    {
        $regions = Region::where('is_deleted', 'N')->get();
        $allUsers = User::where('is_delete', '0')->where('role', '1')->get();
        foreach ($allUsers as $key => $value) {
            $value->regions = Region::where('id', $value->region_id)->where('is_deleted', 'N')->select('region_name')->first();
        }
        return view('admin.admin-list', compact('allUsers', 'regions'));
    }
    public function editusers(Request $request)
    {
        $regions = Region::where('is_deleted', 'N')->get();
        $user_detail = User::where('id', $request->user_id)->first();
        $hashedPassword = bcrypt($user_detail->password);

        return response()->json(['success' => '1', 'data' => $user_detail, 'region' => $regions]);
    }
    public function saveAdmis(Request $request)
    {
        if ($request->has('edit_user_id') && $request->edit_user_id != null) {
            $validate = Validator::make($request->all(), [
                'full_name' => 'required',
                'user_name' => 'required',
                'region_id' => 'required',
                'user_email' => 'required|unique:users,email,' . $request->edit_user_id,
                'user_role' => 'required'
            ]);
            if ($validate->fails()) {
                Session::flash('error', $validate->errors()->first());
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back()->withInput();
            }
            $updateuser = User::find($request->edit_user_id);
            $updateuser->full_name = $request->full_name;
            $updateuser->username = $request->user_name;
            $updateuser->email = $request->user_email;
            $updateuser->password = Hash::make($request->password);
            $updateuser->region_id = $request->region_id;
            $uploadPath = public_path('assets/images/');
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move(public_path('assets/images'), $filename);

                // Unlink existing image
                if ($updateuser->image) {
                    $existingImagePath = public_path('assets/images/') . $updateuser->image;
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }

                $updateuser->image = $filename;
            } else {
                $updateuser->image;
            }
            $updateuser->role = $request->user_role;
            $updateuser->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $updateuser->save();
            if ($updateuser) {
                Session::flash('success', 'User Updated Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        } else {
            $validate = Validator::make($request->all(), [
                'full_name' => 'required',
                'user_name' => 'required',
                'region_id' => 'required',
                'user_email' => 'required|unique:users,email,' . $request->edit_user_id,
                'password' => 'required|min:6',
                'user_role' => 'required'
            ]);
            if ($validate->fails()) {
                Session::flash('error', $validate->errors()->first());
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back()->withInput();
            }
            $saveuser = new User;
            $saveuser->full_name = $request->full_name;
            $saveuser->username = $request->user_name;
            $saveuser->email = $request->user_email;
            $saveuser->region_id = $request->region_id;
            $saveuser->password = Hash::make($request->password);
            $uploadPath = 'assets/images';

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('assets/images', $filename);
                $saveuser->image = $filename;
            } else {
                $saveuser->image = null;
            }

            $saveuser->role = $request->user_role;
            $saveuser->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $saveuser->save();
            if ($saveuser) {
                Session::flash('success', 'User Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'User Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    //Users
    public function Users()
    {
        $allUsers = User::where('is_delete', '0')->where('role', '2')->get();
        foreach ($allUsers as $key => $value) {
            $value->regions = Region::where('id', $value->region_id)->where('is_deleted', 'N')->select('region_name')->first();
        }
        return view('admin.users', compact('allUsers'));
    }
    public function userProfile($id)
    {
        $userDetail = User::find($id);
        $userCourses = DB::table('user_courses')
            ->join('courses', 'user_courses.course_id', '=', 'courses.id')
            ->select('user_courses.*')
            ->where('user_courses.user_id', $id)
            ->where('courses.is_active', '1')->where('courses.is_delete', '0')
            ->get();
        $userCertificates = UserCourses::where('user_id', $id)->where('is_completed', '1')->count();
        $points = 0;
        foreach ($userCourses as $course) {
            if (Courses::where('id', $course->course_id)->where('is_active', '1')->where('is_delete', '0')->exists()) {
                $course->courseDetail = Courses::where('id', $course->course_id)->where('is_active', '1')->where('is_delete', '0')->first();
                $courseSessions = Feature::where('course_id', $course->course_id)->pluck('id');
                $getTopics = Category::whereIn('session_id', $courseSessions)->pluck('id');
                $course->getQuizes = SubCategory::whereIn('topic_id', $getTopics)->where('quiz_type', 'PAID')->pluck('id');
                //count total number of quized to make the progress bar; getquizzes can be used as array count
                $getAttemptedQuiz = UserQuizResults::whereIn('quiz_id', $course->getQuizes)->where('user_id', $id)->groupBy('quiz_id')->pluck('quiz_id');
                $course->userAttemptedQuizes = SubCategory::whereIn('id', $getAttemptedQuiz)->where('quiz_type', 'PAID')->get();

                foreach ($course->userAttemptedQuizes as $quiz) {
                    if (UserQuizResultStats::where('quiz_id', $quiz->id)->where('user_id', $id)->exists()) {
                        $quiz->stats = UserQuizResultStats::where('quiz_id', $quiz->id)->where('user_id', $id)->first()['result'];
                        $points += $quiz->stats;
                    }
                }
            }
        }
        $course_points = $points;
        return view('web.profile', compact('userDetail', 'userCourses', 'userCertificates', 'course_points'));
    }
    public function userProfileDetail($id, $user_id)
    {
        $userDetail = User::find($user_id);
        $userCourses = UserCourses::where('user_id', $user_id)->get();
        $userCertificates = UserCourses::where('user_id', $user_id)->where('is_completed', '1')->count();
        $courses = Courses::find($id);
        $courseSessions = Feature::where('course_id', $id)->pluck('id');
        $getTopics = Category::whereIn('session_id', $courseSessions)->pluck('id');
        $getQuizes = SubCategory::whereIn('topic_id', $getTopics)->pluck('id');
        //count total number of quized to make the progress bar; getquizzes can be used as array count
        $getAttemptedQuiz = UserQuizResults::whereIn('quiz_id', $getQuizes)->where('user_id', $user_id)->groupBy('quiz_id')->pluck('quiz_id');
        $userAttemptedQuizes = SubCategory::whereIn('id', $getAttemptedQuiz)->where('quiz_type', 'PAID')->get();
        $points = 0;
        foreach ($userAttemptedQuizes as $quiz) {
            if (UserQuizResultStats::where('quiz_id', $quiz->id)->where('user_id', $user_id)->exists()) {
                $quiz->stats = UserQuizResultStats::where('quiz_id', $quiz->id)->where('user_id', $user_id)->first();
                $points += UserQuizResultStats::where('quiz_id', $quiz->id)->where('user_id', $user_id)->first()['result'];
            }
        }
        $course_points = UserQuizResultStats::where('user_id', $user_id)->sum('result');
        // return $userAttemptedQuizes;
        return view('web.profile-view', compact('userDetail', 'courses', 'userAttemptedQuizes', 'userCourses', 'userCertificates', 'course_points'));
    }
    public function UpdateUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_full_name' => 'required',
            'user_name' => 'required',
            'region_id' => 'required',
            'user_email' => 'required|unique:users,email,' . $request->edit_user_id
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->with('error', $validate->errors()->first());
        }
        $updateuser = User::find($request->edit_user_id);
        $updateuser->full_name = $request->user_full_name;
        $updateuser->username = $request->user_name;
        $updateuser->email = $request->user_email;
        $updateuser->password = Hash::make($request->password);
        $updateuser->region_id = $request->region_id;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/images'), $filename);
            if ($updateuser->image) {
                $existingImagePath = public_path('assets/images/') . $updateuser->image;
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }

            $updateuser->image = $filename;
        } else {
            $updateuser->image;
        }
        $updateuser->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $updateuser->update();
        if ($updateuser) {
            Session::flash('success', 'User Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }


    }

    public function edituser(Request $request)
    {
        $regions = Region::where('is_deleted', 'N')->get();
        $user_detail = User::where('id', $request->user_id)->first();
        return response()->json(['success' => '1', 'data' => $user_detail, 'region' => $regions]);
    }


    public function deleteuser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_delete = '1';
            $user->save();
            if ($user) {
                Session::flash('success', 'User Deleted Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    public function Questions()
    {
        $getQuestions = Files::where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        foreach ($getQuestions as $question) {
            if (SubCategory::where('id', $question->quiz_id)->exists()) {
                $question->quizName = SubCategory::where('id', $question->quiz_id)->first()['quiz_title'];
            } else {
                $question->quizName = "";
            }
        }
        return view('admin.questions', compact('getQuestions'));
    }
    public function MultipleQuestions()
    {
        $getQuiz = SubCategory::where('quiz_test_type', 'multiple')->where('is_active', '1')->where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        $question_id = null;
        $getQuestion = null;
        $getOptions = null;
        return view('admin.Multiple-questions', compact('getQuiz', 'question_id', 'getQuestion', 'getOptions'));
    }
    public function saveMultipleQuestions(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'quiz_id' => 'required',
            'points' => 'required',
            'quiz_query' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'radio' => 'required',
        ], [
            'quiz_id.required' => 'The quiz field is required.',
            'points.required' => 'The points field is required.',
            'quiz_query.required' => 'The question field is required.',
            'option1.required' => 'The option field is required.',
            'option2.required' => 'The option field is required.',
            'radio.required' => 'The correct answer radio check is required.',
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            // return redirect()->back()->with('error', $validate->errors()->first());
            return redirect()->back()->withInput();
        }
        foreach ($request->all() as $index => $value) {
            if (strpos($index, 'option') === 0) {
                $optionsIndex[] = $value;
            }
        }
        if ($request->question_id != null) {
            $addQuestion = Files::find($request->question_id);
            $addQuestion->quiz_id = $request->quiz_id;
            $addQuestion->quiz_type = "multiple_choice";
            $addQuestion->quiz_query = $request->quiz_query;
            $addQuestion->points = $request->points;
            $addQuestion->update();

            $deleteOptions = QuizOptions_Multiple_Choices::where('question_id', $request->question_id)->delete();
        } else {
            //add question
            $addQuestion = new Files();
            $addQuestion->quiz_id = $request->quiz_id;
            $addQuestion->quiz_type = "multiple_choice";
            $addQuestion->quiz_query = $request->quiz_query;
            $addQuestion->points = $request->points;
            $addQuestion->save();
        }
        //add options
        // return $optionsIndex;
        for ($i = 0; $i < count($optionsIndex); $i++) {
            if ($optionsIndex[$i] != null) {
                $addOptions = new QuizOptions_Multiple_Choices();
                $addOptions->quiz_id = $request->quiz_id;
                $addOptions->question_id = $addQuestion->id;
                $addOptions->quiz_options = $optionsIndex[$i];
                $addOptions->save();
            }
        }
        //update correct answer
        $correct_Answer = $request->radio;
        $optionValue = $request->{$correct_Answer};
        $getCorrectId = QuizOptions_Multiple_Choices::where('quiz_id', $request->quiz_id)->where('question_id', $addQuestion->id)->where('quiz_options', $optionValue)->first();
        $updateCorrectAnswer = Files::find($addQuestion->id);
        $updateCorrectAnswer->correct_answer = $getCorrectId->id;
        $updateCorrectAnswer->update();
        if ($request->question_id == null) {
            if ($updateCorrectAnswer) {
                Session::flash('success', 'Question Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('questions')->with('Success', 'Question Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->route('questions')->with('Error', 'An Error occured! Please try again later');
            }
        } else {
            if ($updateCorrectAnswer) {
                Session::flash('success', 'Question Updated Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('questions')->with('Success', 'Question Updated Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->route('questions')->with('Error', 'An Error occured! Please try again later');
            }
        }

    }
    public function editMultipleQuestions($id)
    {
        $question_id = $id;
        $getQuestion = Files::find($id);
        $getOptions = QuizOptions_Multiple_Choices::where('question_id', $id)->get();
        $getQuiz = SubCategory::where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        if ($getQuestion->quiz_type == 'multiple_choice') {
            return view('admin.Multiple-questions', compact('question_id', 'getQuiz', 'getQuestion', 'getOptions'));
        } else {
            $getStatements = QuizOptions_Drag_Drop::where('question_id', $question_id)->get();
            foreach ($getStatements as $statements) {
                $statements->correct_answer = QuizOptions_Multiple_Choices::where('id', $statements->correct_answer)->first()['quiz_options'];
                $statements->updatedStatement = str_replace("##", "##" . $statements->correct_answer . "##", $statements->statement);
            }
            $getCorrectIds = QuizOptions_Drag_Drop::where('question_id', $question_id)->pluck('correct_answer');
            $getIncorrectOptions = QuizOptions_Multiple_Choices::whereNotIn('id', $getCorrectIds)->where('question_id', $question_id)->pluck('quiz_options');
            $getIncorrectOptions = json_decode($getIncorrectOptions);
            $getIncorrectOptions = implode(",", $getIncorrectOptions);
            return view('admin.drag-and-drop', compact('question_id', 'getQuiz', 'getQuestion', 'getStatements', 'getOptions', 'getIncorrectOptions'));
        }
    }
    public function deleteMultipleQuestions($id)
    {
        $deleteQuestion = Files::where('id', $id)->first();
        $deleteQuestion->is_delete = '1';
        $deleteQuestion->update();
        if ($deleteQuestion) {
            Session::flash('success', 'Question Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Question Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function DragDrop()
    {
        $getQuiz = SubCategory::where('quiz_test_type', 'drag_drop')->where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        $question_id = null;
        $getQuestion = null;
        $getStatements = null;
        $getIncorrectOptions = null;
        return view('admin.drag-and-drop', compact('getQuiz', 'question_id'));
    }
    public function saveDragDropQuestion(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'quiz_id' => 'required',
            'points' => 'required',
            'question1' => 'required',
            'incorrect_words' => 'required',
        ], [
            'quiz_id.required' => 'Please select the Quiz from the drop down',
            'points.required' => 'The points field is required.',
            'question1.required' => 'Please add atleast one question',
            'incorrect_words.required' => 'The Incorrect Words Field is required',
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        //get statements
        foreach ($request->all() as $index => $value) {
            if (strpos($index, 'question') === 0) {
                $optionsIndex[] = strip_tags($value);
            }
        }
        foreach ($optionsIndex as $key => $question) {
            if ($question != null) {
                $questionPattern = '/##(.*?)##/';
                if (!(preg_match($questionPattern, $question))) {
                    // $errorMessage = "The correnct word's Hashes are missing in". $key + 1 . "statement";
                    Session::flash('error', "The correct word's Hashes are missing in Question Line " . ($key + 1));
                    Session::flash('alert-class', 'alert-danger');
                    return redirect()->back()->withInput();
                }
            }

        }
        if ($request->has('statement_id') && $request->statement_id != null) {
            $addQuestion = Files::find($request->statement_id);
            $addQuestion->quiz_id = $request->quiz_id;
            $addQuestion->points = $request->points;
            // $addQuestion->update();
            $deleteStatements = QuizOptions_Drag_Drop::where('question_id', $request->statement_id)->delete();
            $deleteOptions = QuizOptions_Multiple_Choices::where('question_id', $request->statement_id)->delete();
        } else {
            $addQuestion = new Files();
            $addQuestion->quiz_id = $request->quiz_id;
            $addQuestion->quiz_type = "drag_drop";
            $addQuestion->quiz_query = "Choose from the words below and drag the correct words to complete the sentence. YOU MUST drag any unused words into the pink space provided.";
            $addQuestion->points = $request->points;
            $addQuestion->save();
        }
        foreach ($optionsIndex as $question) {
            if ($question != null) {
                $addStatement = new QuizOptions_Drag_Drop();
                $addStatement->quiz_id = $request->quiz_id;
                $addStatement->question_id = $addQuestion->id;
                $questionPattern = '/##(.*?)##/';
                if (preg_match($questionPattern, $question)) {
                    $questionReplacement = '##';
                    $addStatement->statement = preg_replace($questionPattern, $questionReplacement, $question);
                    $addStatement->correct_answer = 0;
                    $addStatement->save();

                    $addOptions = new QuizOptions_Multiple_Choices();
                    $addOptions->quiz_id = $request->quiz_id;
                    $addOptions->question_id = $addQuestion->id;
                    preg_match($questionPattern, $question, $matches);

                    if (isset($matches[1])) {
                        $extractedWord = $matches[1];
                        $addOptions->quiz_options = $extractedWord;
                    }
                    $addOptions->save();
                    //code to update correct answers
                    $updateCorrectAns = QuizOptions_Drag_Drop::find($addStatement->id);
                    $updateCorrectAns->correct_answer = $addOptions->id;
                    $updateCorrectAns->update();
                }
            }

        }
        if ($request->has('incorrect_words') && $request->incorrect_words != null) {
            //add incorrect word to multiple choice table
            $inccorectOptions = explode(",", $request->incorrect_words);
            foreach ($inccorectOptions as $option) {
                $addOptions = new QuizOptions_Multiple_Choices();
                $addOptions->quiz_id = $request->quiz_id;
                $addOptions->question_id = $addQuestion->id;
                $addOptions->quiz_options = $option;
                $addOptions->save();

            }
        }
        if ($request->statement_id == null) {
            if ($addQuestion) {
                Session::flash('success', 'Question Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('questions')->with('Success', 'Question Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->route('questions')->with('Error', 'An Error occured! Please try again later');
            }
        } else {
            if ($addQuestion) {
                Session::flash('success', 'Question Updated Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('questions')->with('Success', 'Question Updated Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->route('questions')->with('Error', 'An Error occured! Please try again later');
            }
        }

    }
    public function Blog()
    {
        $getBlogs = Blogs::where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        return view('admin.blog', compact('getBlogs'));
    }
    public function AddBlog()
    {
        $getBlogs = null;
        return view('admin.add-blog', compact('getBlogs'));
    }
    public function editBlog($id)
    {
        $getBlogs = Blogs::find($id);
        return view('admin.add-blog', compact('getBlogs'));
    }
    public function deleteBlog($id)
    {
        $deleteBlog = Blogs::where('id', $id)->update(['is_delete' => '1']);
        if ($deleteBlog) {
            Session::flash('success', 'Blog Deleted Successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Blog Deleted Successfully!');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function saveBlog(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'blog_title' => 'required',
            'content' => 'required',
            // 'blog_image' => 'required',
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $content = $request->content;
        $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
        $dom = new \DomDocument('1.0', 'utf-8');
        @$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imageFile = $dom->getElementsByTagName('imageFile');
        foreach ($imageFile as $item => $image) {
            $data = $image->getAttribute('src');
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $imgeData = base64_decode($data);
            $image_name = "/upload/" . time() . $item . '.png';
            $path = public_path() . '/assets/blogs-attachments/' . $image_name;
            file_put_contents($path, $imgeData);
            $image->removeAttribute('src');
            $image->setAttribute('src', $image_name);
        }
        $content = $dom->saveHTML();
        $filename = null;
        if ($request->has('blog_id') && $request->blog_id != null) {
            $CreateBlog = Blogs::find($request->blog_id);
            $filename = $CreateBlog->image;
        } else {
            $CreateBlog = new Blogs;
        }

        $CreateBlog->blog_title = $request->blog_title;
        $CreateBlog->description = $content;
        $CreateBlog->user_id = auth()->user()->id;
        // $name = $request->file('images')->getClientOriginalName();
        // $image =  $request->file('images')->move(public_path('images'), $name);
        if ($request->has('blog_image')) {
            $file = $request->file('blog_image');
            $exten = $file->getClientOriginalName();
            $filename = $exten;
            $file->move(public_path() . '/assets/blogs-attachments/', $filename);
        }
        $CreateBlog->image = $filename;
        $CreateBlog->excerpt = "ooooo";
        $CreateBlog->blog_type = $request->blog_type;
        $CreateBlog->is_active = $request->has('status') ? '1' : '0';
        // return $CreateBlog;
        $CreateBlog->save();
        if ($CreateBlog) {
            if ($request->has('blog_id')) {
                Session::flash('success', 'Blog Updated Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('blog')->with('Success', 'Blog Updated Successfully!');
            } else {
                Session::flash('success', 'Blog Added Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('blog')->with('Success', 'Blog Added Successfully!');
            }
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('blog')->with('Error', 'An Error occured! Please try again later');
        }

    }
    public function dashboard(Request $request)
    {

        $products = Products::where('is_deleted', 'N')->where('is_approved', 'N')->where('is_active', 'Y')->latest()->take(10)->get();
        $products = $products->map(function ($product) {
            //            $product['attachments'] = ProductAttachment::where('products_id', $product->id)->get();
            if (ProductAttachment::where('products_id', $product->id)->exists()) {
                $product['attachments'] = ProductAttachment::where('products_id', $product->id)->first()['image'];
            } else {
                $product['attachments'] = 'xyz';
            }
            $file = public_path() . '/images/product-attachments/' . $product['attachments'];
            if ($product['attachments'] && file_exists($file)) {
                $product['attachments'] = getImageUrl($product['attachments'], 'product-attachments');
            } else {
                $product['attachments'] = getImageUrl('default_product.png', 'images123');
            }
            if (Unit::where('id', $product->unit)->exists()) {

                $product['unit'] = Unit::where('id', $product->unit)->first()['unit'];
            }
            $product['currency'] = Currency::where('id', $product->currency_id)->first()['currency'];
            if (User::where('id', $product->user_id)->where('is_active', 'Y')->exists()) {
                $product['sellerName'] = User::where('id', $product->user_id)->where('is_active', 'Y')->first()['username'];
            }
            if (ProductRating::where('product_id', $product->id)->exists()) {
                $productRating = ProductRating::where('product_id', $product->id)->get();
                $rating = 0;
                for ($k = 0; $k < count($productRating); $k++) {
                    $rating += (double) $productRating[$k]['rating'];
                }
                $count = count($productRating);
                $product['rating'] = $rating / $count;

            } else {
                $product['rating'] = 0;
            }
            return $product;
        });
        $userRequirements = Leads::where('is_approved', 'N')->where('is_deleted', 'N')->orderBy('id', 'DESC')->paginate(10, ["*"], "active_sourcing_leads_page");
        if ($userRequirements) {
            for ($k = 0; $k < count($userRequirements); $k++) {
                if (User::where('id', $userRequirements[$k]->user_id)->where('is_active', 'Y')->exists()) {

                    $userRequirements[$k]['buyerName'] = User::where('id', $userRequirements[$k]->user_id)->where('is_active', 'Y')->first()['username'];
                }
                $userRequirements[$k]['categories'] = ProductCategory::where('id', $userRequirements[$k]->category_id)->first()['category'];
                if (Unit::where('id', $userRequirements[$k]->unit_id)->exists()) {
                    $userRequirements[$k]['units'] = Unit::where('id', $userRequirements[$k]->unit_id)->first()['unit'];
                }
                $userRequirements[$k]['product'] = Products::where('id', $userRequirements[$k]->product_id)->where('is_deleted', 'N')->first();
                if ($userRequirements[$k]['product']) {
                    if (Unit::where('id', $userRequirements[$k]['product']->unit)->exists()) {
                        $userRequirements[$k]['unit'] = Unit::where('id', $userRequirements[$k]['product']->unit)->first()['unit'];
                    }
                    $userRequirements[$k]['currency'] = Currency::first()['currency'];
                }
            }
        }
        //        $userRequirements = $userRequirements->map(function ($userRequirement) {
        //            $userRequirement['buyerName'] = User::where('id', $userRequirement->created_by)->first()['username'];
        //            $userRequirement['product'] = Products::where('id', $userRequirement->product_id)->where('is_deleted','N')->first();
        //            if( $userRequirement['product']) {
        //                $userRequirement['unit'] = Unit::where('id', $userRequirement['product']->unit)->where('status', 'active')->first()['unit'];
        //                $userRequirement['currency'] = Currency::first()['currency'];
        //            }
        //            return $userRequirement;
        //        });
        $activeProducts = Leads::where('is_approved', 'Y')->where('is_deleted', 'N')->orderBy('id', 'DESC')->paginate(10, ["*"], "pending_sourcing_leads_page");
        if ($activeProducts) {
            for ($i = 0; $i < count($activeProducts); $i++) {
                if (User::where('id', $activeProducts[$i]->user_id)->where('is_active', 'Y')->exists()) {

                    $activeProducts[$i]['buyerName'] = User::where('id', $activeProducts[$i]->user_id)->where('is_active', 'Y')->first()['username'];
                }
                $activeProducts[$i]['categories'] = ProductCategory::where('id', $activeProducts[$i]->category_id)->first();
                if (isset($activeProducts[$i]['categories']->category)) {
                    $activeProducts[$i]['categories'] = $activeProducts[$i]['categories']->category;
                }
                if (Unit::where('id', $activeProducts[$i]->unit_id)->exists()) {
                    $activeProducts[$i]['units'] = Unit::where('id', $activeProducts[$i]->unit_id)->first()['unit'];
                }
                $activeProducts[$i]['product'] = Products::where('id', $activeProducts[$i]->product_id)->where('is_deleted', 'N')->first();
                if ($activeProducts[$i]['product']) {
                    if (Unit::where('id', $activeProducts[$i]['product']->unit)->exists()) {
                        $activeProducts[$i]['unit'] = Unit::where('id', $activeProducts[$i]['product']->unit)->first()['unit'];
                    } else {

                    }
                    $activeProducts[$i]['currency'] = Currency::first()['currency'];
                }
            }
        }

        $userProducts = User::where('is_active', 'Y')->pluck('id');
        $pendingProducts = Products::where('is_approved', 'N')->where('is_active', 'Y')->where('is_deleted', 'N')->whereIn('user_id', $userProducts)->count();
        $activeProduct = Products::where('is_approved', 'Y')->where('is_active', 'Y')->where('is_deleted', 'N')->count();
        $buyers = User::where('role', '4')->where('is_deleted', 'N')->where('is_active', 'Y')->count();
        $sellers = User::where('role', '5')->where('is_deleted', 'N')->where('is_active', 'Y')->count();
        $ios = UserDevice::where('platform', 'iOS')->count();
        $android = UserDevice::where('platform', 'android')->count();
        $install = UserDevice::count();
        $data =
            [
                'products' => $products,
                'sourcingLeads' => $userRequirements,
                'activeProducts' => $activeProducts,
                'totalProducts' => $pendingProducts,
                'buyers' => $buyers,
                'sellers' => $sellers,
                'activeProduct' => $activeProduct,
                'ios' => $ios,
                'android' => $android,
                'install' => $install

            ];

        return view('admin.dashboard', $data);
    }
    public function approveLeads(Request $request)
    {
        if (Auth::user()->role != '1') {

            return redirect('/home');

        }

        $id = $request->id;

        $user = Leads::findOrFail($id);
        if ($user) {
            $user->is_approved = 'Y';
            $user->update();
            $notification = new Notification();
            $notification->user_id = $user->user_id;
            $notification->type_id = $user->id;
            $notification->schedule_date = \Carbon\Carbon::now();
            $notification->is_msg_app = 'Y';
            $notification->notification_type = 'Lead';
            $notification->title = 'Lead Approved';
            $notification->description = 'Congratulations! Your lead has been approved';
            $notification->save();

            $notification = new Notification();
            $notification->user_id = $user->seller_id;
            $notification->type_id = $user->id;
            $notification->schedule_date = \Carbon\Carbon::now();
            $notification->is_msg_app = 'Y';
            $notification->notification_type = 'Lead';
            $notification->title = 'Lead Received';
            $notification->description = 'You have received a new Lead against Your Product';
            $notification->save();
            $this->send_comm_app_notification();
        }

        if ($user) {

            return ['status' => true];

        } else {

            return ['status' => false];

        }
    }
    public function deleteLeads(Request $request)
    {
        if (Auth::user()->role != '1') {

            return redirect('/home');

        }

        $id = $request->id;

        $user = Leads::findOrFail($id);
        if ($user) {
            $user->is_deleted = 'Y';
            $user->update();
        }

        if ($user) {

            return ['status' => true];

        } else {

            return ['status' => false];

        }
    }
    public function deletePendingProducts(Request $request)
    {
        if (Auth::user()->role != '1') {

            return redirect('/home');

        }

        $id = $request->id;

        $user = Products::findOrFail($id);
        if ($user) {
            $user->is_deleted = 'Y';
            $user->update();
        }

        if ($user) {

            return ['status' => true];

        } else {

            return ['status' => false];

        }
    }
    public function searchLeads(Request $request)
    {
        if ($request->type == 'active') {
            $activeProducts = Leads::where('is_approved', 'Y')->where('is_deleted', 'N')->orderBy('id', 'DESC')->get();
            if ($activeProducts) {
                for ($i = 0; $i < count($activeProducts); $i++) {
                    $date = date('Y-m-d', strtotime($activeProducts[$i]['created_at']));
                    $activeProducts[$i]['buyerName'] = User::where('id', $activeProducts[$i]->created_by)->first()['username'];
                    $activeProducts[$i]['date'] = $date;
                    $activeProducts[$i]['product'] = Products::where('id', 'LIKE', '%' . $request->value)->where('is_deleted', 'N')->first();
                    if ($activeProducts[$i]['product']) {
                        if (Unit::where('id', $activeProducts[$i]['product']->unit)->where('status', 'active')->exists()) {
                            $activeProducts[$i]['unit'] = Unit::where('id', $activeProducts[$i]['product']->unit)->where('status', 'active')->first()['unit'];
                        } else {
                            $activeProducts[$i]['unit'] = '';
                        }
                        //                        $activeProducts[$i]['unit'] = Unit::where('id', $activeProducts[$i]['product']->unit)->where('status', 'active')->first()['unit'];
                        $activeProducts[$i]['currency'] = Currency::first()['currency'];
                    }
                }
                return ['status' => true, 'data' => $activeProducts];
            }
        } else if ($request->type == 'pending') {
            $activeProducts = Leads::where('is_approved', 'N')->where('is_deleted', 'N')->orderBy('id', 'DESC')->get();
            if ($activeProducts) {
                for ($i = 0; $i < count($activeProducts); $i++) {
                    $date = date('Y-m-d', strtotime($activeProducts[$i]['created_at']));
                    $activeProducts[$i]['buyerName'] = User::where('id', $activeProducts[$i]->created_by)->first()['username'];
                    $activeProducts[$i]['date'] = $date;
                    $activeProducts[$i]['product'] = Products::where('id', 'LIKE', '%' . $request->value)->where('is_deleted', 'N')->first();
                    if ($activeProducts[$i]['product']) {
                        if (Unit::where('id', $activeProducts[$i]['product']->unit)->where('status', 'active')->exists()) {
                            $activeProducts[$i]['unit'] = Unit::where('id', $activeProducts[$i]['product']->unit)->where('status', 'active')->first()['unit'];
                        } else {
                            $activeProducts[$i]['unit'] = '';
                        }
                        //                        $activeProducts[$i]['unit'] = Unit::where('id', $activeProducts[$i]['product']->unit)->where('status', 'active')->first()['unit'];
                        $activeProducts[$i]['currency'] = Currency::first()['currency'];
                    }
                }
                return ['status' => true, 'data' => $activeProducts];
            }
        }
        return ['status' => false];

    }
    public function testerQuizzes()
    {
        $getCategories = Category::where('is_delete', '0')->where('is_active','1')->orderBy('created_at', 'DESC')->get();
        $testerQuizzes = DB::table('categories')
        ->join('sub_categories','sub_categories.category_id', '=', 'categories.id')
        ->where('categories.is_delete', '0')->where('categories.is_active','1')
        ->where('sub_categories.is_delete', '0')
        ->select('sub_categories.*','categories.category_name as getCategoryName')
        ->orderBy('sub_categories.priority', 'asc')->get();

        return view('admin.tester-quiz', compact('testerQuizzes', 'getCategories'));
    }
    public function getSubCategory(Request $request)
    {
        $categories = SubCategory::where('is_delete','0')->where('is_active','1')
        ->where('category_id', $request->category_id)
            ->select('id', 'sub_category_name')
            ->orderBy('priority', 'asc')->get();
        return response()->json(["success" => true, "data" => $categories]);
    }
    public function saveTesterQuizzes(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'sub_category_name' => 'required',
            'category_id' => 'required',
            'priority' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $existingSubCategory = SubCategory::where('category_id',$request->category_id)->where('sub_category_name', $request->sub_category_name)->first();
        if($existingSubCategory !=null && $existingSubCategory->is_delete === "1"){
            $existingSubCategory->is_delete = "0";
            $existingSubCategory->sub_category_name = $request->sub_category_name;
            $existingSubCategory->category_id = $request->category_id;
            $existingSubCategory->priority = $request->priority;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move(public_path('assets/subcategory'), $filename);
                if ($existingSubCategory->image) {
                    $existingImagePath = public_path('assets/subcategory/') . $existingSubCategory->image;
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }
                $existingSubCategory->image = $filename;
            } else {
                $existingSubCategory->image;
            }
            $existingSubCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $existingSubCategory->update();
            Session::flash('success', 'Sub Category Added Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Category Added Successfully');
        } elseif ($existingSubCategory) {
            Session::flash('error', 'The Sub Category Already Exists!');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'The Category Already Exists!');
        } else {
            $saveSubCategory = new SubCategory();
            $saveSubCategory->category_id = $request->category_id;
            $saveSubCategory->priority = $request->priority;
            $saveSubCategory->sub_category_name = $request->sub_category_name;
            if ($request->has('image')) {
                $files = $request->image;
                $categoryImage = date("dmyHis.") . '_' . $files->getClientOriginalName();
                // return public_path();
                $files->move(public_path() . '/assets/subcategory/', $categoryImage);
                $saveSubCategory->image = $categoryImage;
            }
            $saveSubCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $saveSubCategory->save();
            if ($saveSubCategory) {
                Session::flash('success', 'Sub Category Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'Sub Category Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    public function updateSubCategory(Request $request){
        $validate = Validator::make($request->all(), [
            'sub_category_name' => 'required',
            'category_id' => 'required',
            'priority' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $existingSubCategory = SubCategory::where('id','!=',$request->sub_category_id)->where('category_id',$request->category_id)->where('sub_category_name', $request->sub_category_name)->first();
        if($existingSubCategory){
            Session::flash('error', 'Sub Category Already Added.!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $updateSubCategory = SubCategory::find($request->sub_category_id);
        $updateSubCategory->sub_category_name = $request->sub_category_name;
        $updateSubCategory->category_id = $request->category_id;
        $updateSubCategory->priority = $request->priority;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/subcategory'), $filename);

            // Unlink existing image
            if ($updateSubCategory->image) {
                $existingImagePath = public_path('assets/subcategory/') . $updateSubCategory->image;
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
            $updateSubCategory->image = $filename;
        } else {
            $updateSubCategory->image;
        }
        $updateSubCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $updateSubCategory->update();
        if ($updateSubCategory) {
            Session::flash('success', 'Sub Category Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Category Updated Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function editTesterQuizzes(Request $request)
    {
        $getCategories = Category::where('is_delete', '0')->where('is_active','1')->orderBy('created_at', 'DESC')->get();
        $getQuizData = SubCategory::find($request->sub_category_id);
        return response()->json(['success' => '1', 'data' => $getQuizData, 'category' => $getCategories]);
    }
    public function send_comm_app_notification()
    {
        $number_noti = 1000;
        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
        $server_key = 'AAAAB8kX-H4:APA91bEI_W9gLV043qjuvD8SrRw5Tmb1TKIUiaHrYf_PUF5LD7qt1yF6x19vncMdTdelKsNbLnLO1OTVH3illOOgiXE82ugDxeU_IZTEugtslHrqM6GtxSacNl-5QO21wtrIUAvophZ6';
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
        $currentDate = date('Y-m-d H:i:s');

        $noti = Notification::where(('sent_status'), '=', "N")->where("schedule_date", "<=", $currentDate)->where("is_notification_required", "Y")->orderBy('schedule_date', 'DESC')->limit($number_noti)->get();

        if (count($noti) > 0) {
            foreach ($noti as $element) {
                #send App notification
                if (($element->is_msg_app) == 'Y') {
                    $title = $element->title;
                    $description = $element->description;
                    $user_id = $element->user_id;
                    if ($element->device_type == 'all') {

                        $q = UserDevice::where('user_id', $user_id)->where('status', '=', 'A')->get();
                    } else {

                        $q = UserDevice::where('user_id', $user_id)->where('status', '=', 'A')->where('platform', $element->device_type)->get();
                    }
                    if (!empty($q) && count($q) > 0) {
                        foreach ($q as $row) {
                            // dd($row);

                            if (is_null($row->token)) {
                                DB::table('notification')
                                    ->where('id', '=', $element->id)
                                    ->update(array('message_error' => "Device token is null"));
                                continue;
                            }
                            $key = $row->token;
                            $headers = array(
                                'Authorization:key=' . $server_key,
                                'Content-Type:application/json'
                            );
                            $fields = array(
                                'to' => $key,
                                'notification' => array('title' => $title, 'body' => $description, 'sound' => 1, 'vibrate' => 1),
                                'data' => array('notification_type' => $element->notification_type, 'title' => $title, 'body' => $description)
                            );

                            $payload = json_encode($fields);
                            $curl_session = curl_init();
                            curl_setopt($curl_session, CURLOPT_URL, $url);
                            curl_setopt($curl_session, CURLOPT_POST, true);
                            curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                            curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);
                            $curlResult = curl_exec($curl_session);

                            if ($curlResult === FALSE) {
                                die('FCM Send Error: ' . curl_error($curl_session));
                            }
                            curl_close($curl_session);


                            $res = json_decode($curlResult, true);

                            //   dd($res);
                            if ($res["failure"]) {
                                $array = $res['results'];
                                $error = $array[0]['error'];
                                DB::table('notification')
                                    ->where('id', '=', $element->id)
                                    ->update(array('message_error' => $error));
                            } else {
                                DB::table('notification')
                                    ->where('id', '=', $element->id)
                                    ->update(array('message_error' => '', 'sent_status' => 'Y', 'app_sent_date' => $currentDate));
                            }
                        }
                    }
                }
            }
            //return response(['success' => 1, 'message' => 'Sending all notifications', 'result' =>true], 200);
            // return true;
        }
    }
    public function subscriptions()
    {
        $users = DB::table('user_course_transaction')
            ->join('users', 'user_course_transaction.user_id', '=', 'users.id')
            ->join('courses', 'user_course_transaction.course_id', '=', 'courses.id')
            ->select('users.username', 'user_course_transaction.account_number', 'user_course_transaction.created_at', 'courses.course_title')
            ->orderBy('user_course_transaction.created_at', 'DESC')
            ->get();
        // dd($users);
        return view('admin.subscriptions', compact('users'));
    }
    public function viewFile(Request $request, $category = null, $sub_category = null, $child = null,$sub_child = null)
    {
        $file_type = FileType::where('is_deleted', '0')->where('is_active', '1')->get();
        $selectedRegion = 'all';
        $selectedFileType = isset($file_type[0]['id']) ? $file_type[0]['id']:"";
        $getCatName = Category::where('id', $category)->first();
        $subCatName = SubCategory::where('id', $sub_category)->first();
        $sub_cat_child = SubCatChild::where('id', $child)->first();
        $sub_cat_sub_child = SubCatSubChild::where('id', $sub_child)->first();
        $region = Region::where('is_deleted', 'N')->get();
        if($request->has('file_type_id')){
            $selectedFileType = $request->file_type_id;
        }
        $selectedRegion = $request->region_id;
        $getfiles = Files::where('is_delete', '0')
            ->where(function ($query) use ($sub_child,$category, $sub_category, $child ,$selectedFileType, $selectedRegion) {
                if ($child && $child != 0) {
                    $query->Where('child_id', $child);
                }
                if ($category) {
                    $query->Where('category_id', $category);
                }
                if ($sub_category) {
                    $query->Where('sub_category_id', $sub_category);
                }
                if ($sub_child && $sub_child != 0) {
                    $query->where('sub_child_id', $sub_child);
                }
            })->pluck('id');
            if($selectedRegion != 'all' && $selectedRegion != ""){
                $files = FileContent::where('is_deleted', '0')->where('file_type_id', $selectedFileType)->where('region_id', $selectedRegion)->whereIn('file_id', $getfiles)->get();
            }else{
                $files = FileContent::where('is_deleted', '0')->where('file_type_id', $selectedFileType)->whereIn('file_id', $getfiles)->get();
            }

        // return [$selectedFileType,$selectedRegion];
        return view('admin.view-file', compact('files', 'file_type', 'subCatName', 'getfiles', 'region', 'selectedRegion', 'getCatName', 'sub_cat_child', 'sub_cat_sub_child','selectedFileType'));
    }
    public function saveFile(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'region_id' => 'required',
            'file_type_id' => 'required',
            'image.*' => 'required|file|mimes:mp4,jpg,png,pdf|max:250000',
            'child_id' => 'nullable|integer',
            'sub_child_id' => 'nullable|integer',
        ]);
        
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        
        // if (is_null($request->child_id) && is_null($request->sub_child_id)) {
        //     $validate->errors()->add('child_id', 'Please provide either child_id or sub_child_id.');
        //     Session::flash('error', $validate->errors()->first());
        //     Session::flash('alert-class', 'alert-danger');
        //     return redirect()->back()->withInput();
        // }
        if (is_null($request->image)) {
            $validate->errors()->add('child_id', 'Please Upload Content.');
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $newFile = new Files();
        $newFile->category_id = $request->category_id;
        $newFile->sub_category_id = $request->sub_category_id;
        $newFile->child_id = $request->child_id;
        $newFile->sub_child_id = $request->sub_child_id;
        $newFile->save();
        foreach ($request->file('image') as $file) {
            $originalFileName = $file->getClientOriginalName();
            $file->move(public_path() . '/assets/subcategory/', $originalFileName);
            $newFileCon = new FileContent();
            $newFileCon->file_id = $newFile->id;
            $newFileCon->file_path = $originalFileName;
            $newFileCon->file_type_id = $request->file_type_id;
            $newFileCon->region_id = $request->region_id;
            $newFileCon->save();
        }
        if ($newFile) {
            Session::flash('success', 'Files Added Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Files Added Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function deleteFile($id)
    {
        $getFile = FileContent::findOrFail($id);
        $getFile->is_deleted = '1';
        $getFile->update();
        if ($getFile) {
            Session::flash('success', 'File Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'File Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    //Editor
    public function Editor()
    {
        $regions = Region::where('is_deleted', 'N')->get();
        $allUsers = User::where('is_delete', '0')->where('role', '3')->get();
        foreach ($allUsers as $key => $value) {
            $value->regions = Region::where('id', $value->region_id)->where('is_deleted', 'N')->select('region_name')->first();
        }
        return view('admin.editor-list', compact('allUsers', 'regions'));
    }
    public function UpdateEditor(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'full_name' => 'required',
            'user_name' => 'required',
            'user_email' => 'required|unique:users,email,' . $request->edit_user_id,
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $updateuser = User::find($request->edit_user_id);
        $updateuser->full_name = $request->full_name;
        $updateuser->username = $request->user_name;
        $updateuser->email = $request->user_email;
        $updateuser->password = Hash::make($request->password);
        $updateuser->region_id = $request->region_id;
        $uploadPath = public_path('assets/images/');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/images'), $filename);

            // Unlink existing image
            if ($updateuser->image) {
                $existingImagePath = public_path('assets/images/') . $updateuser->image;
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }

            $updateuser->image = $filename;
        } else {
            $updateuser->image;
        }
        $updateuser->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $updateuser->save();
        if ($updateuser) {
            Session::flash('success', 'User Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function subCatChild()
    {
        $getCategories = Category::where('is_delete', '0')->where('is_active', '1')->orderBy('created_at', 'DESC')->get();
        $testerQuizzes = SubCategory::where('is_delete', '0')->where('is_active', '1')->get();

        $sub_cat_child = DB::table('sub_cat_childs')->where('sub_cat_childs.is_deleted', '0')
        ->leftJoin('categories','sub_cat_childs.category_id', '=', 'categories.id')
        ->leftjoin('sub_categories','sub_cat_childs.sub_category_id', '=', 'sub_categories.id')
        ->select('sub_cat_childs.*','categories.category_name as getCategoryName','sub_categories.sub_category_name as subCategoryName')
        ->where('categories.is_active', '1')->where('categories.is_delete', '0')
        ->where('sub_categories.is_active', '1')->where('sub_categories.is_delete', '0')
        ->orderBy('sub_cat_childs.priority', 'asc')
        ->get();
        return view('admin.child', compact('testerQuizzes', 'getCategories', 'sub_cat_child'));
    }
    public function saveSubCatChild(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'child_name' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'priority' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $existingChild = SubCatChild::where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->where('child_name', $request->child_name)->first();
        if($existingChild !=null && $existingChild->is_deleted === "1"){
            $existingChild->is_deleted = "0";
            $existingChild->category_id = $request->category_id;
            $existingChild->sub_category_id = $request->sub_category_id;
            $existingChild->child_name = $request->child_name;
            $existingChild->priority = $request->priority;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move(public_path('assets/subcategory'), $filename);
                if ($existingChild->image) {
                    $existingImagePath = public_path('assets/subcategory/') . $existingChild->image;
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }
                $existingChild->image = $filename;
            } else {
                $existingChild->image;
            }
            $existingChild->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $existingChild->update();
            Session::flash('success', 'Sub Category Child Added Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Category Child Added Successfully');
        } elseif ($existingChild) {
            Session::flash('error', 'The Child Already Exists!');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'The Child Already Exists!');
        } else {
            $saveSubCategory = new SubCatChild();
            $saveSubCategory->category_id = $request->category_id;
            $saveSubCategory->sub_category_id = $request->sub_category_id;
            $saveSubCategory->child_name = $request->child_name;
            $saveSubCategory->priority = $request->priority;
            if ($request->has('image')) {
                $files = $request->image;
                $categoryImage = date("dmyHis.") . '_' . $files->getClientOriginalName();
                // return public_path();
                $files->move(public_path() . '/assets/subcategory/', $categoryImage);
                $saveSubCategory->image = $categoryImage;
            }
            $saveSubCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $saveSubCategory->save();
            if ($saveSubCategory) {
                Session::flash('success', 'Sub Category Child Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'Sub Category Child Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    public function updateSubCatChild(Request $request){
        $validate = Validator::make($request->all(), [
            'child_name' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'priority' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $existingChild = SubCatChild::where('id','!=',$request->child_id)->where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->where('child_name', $request->child_name)->first();
        if ($existingChild) {
            Session::flash('error', "Child Already Added.!");
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $updateSubCategory = SubCatChild::find($request->child_id);
        $updateSubCategory->category_id = $request->category_id;
        $updateSubCategory->sub_category_id = $request->sub_category_id;
        $updateSubCategory->child_name = $request->child_name;
        $updateSubCategory->priority = $request->priority;
        $uploadPath = public_path('assets/subcategory/');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/subcategory'), $filename);

            // Unlink existing image
            if ($updateSubCategory->image) {
                $existingImagePath = public_path('assets/subcategory/') . $updateSubCategory->image;
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
            $updateSubCategory->image = $filename;
        } else {
            $updateSubCategory->image;
        }
        $updateSubCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $updateSubCategory->update();
        if ($updateSubCategory) {
            Session::flash('success', 'Sub Category Child Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Category Child Updated Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function getChild(Request $request)
    {
        $child = SubCatChild::where('is_deleted','0')->where('is_active','1')
        ->where('sub_category_id', $request->sub_category_id)
            ->select('id', 'child_name')
            ->orderBy('priority', 'asc')->get();
        return response()->json(["success" => true, "data" => $child]);
    }
    public function editChild(Request $request)
    {
        $getQuizData = SubCatChild::find($request->child_id);
        $getCategories = Category::where('is_delete', '0')->where('is_active', '1')->orderBy('created_at', 'DESC')->get();
        $getSubCategories = SubCategory::where('is_delete', '0')->where('is_active', '1')->where('category_id', $getQuizData->category_id)->orderBy('created_at', 'DESC')->get();
        return response()->json(['success' => '1', 'data' => $getQuizData, 'category' => $getCategories, 'sub_category' => $getSubCategories]);
    }
    public function deleteChild($id)
    {
        $getQuiz = SubCatChild::find($id);
        $getQuiz->is_deleted = '1';
        $getQuiz->update();
        if ($getQuiz) {
            Session::flash('success', 'Sub Category Child Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Category Child Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function subCatSubChild()
    {
        $getCategories = Category::where('is_delete', '0')->where('is_active', '1')->get();
        $testerQuizzes = SubCategory::where('is_delete', '0')->where('is_active', '1')->get();
        $sub_cat_child = SubCatChild::where('is_deleted', '0')->where('is_active', '1')->get();

        $sub_cat_sub_child = DB::table('sub_cat_sub_childs')
        ->leftJoin('categories', 'sub_cat_sub_childs.category_id', '=', 'categories.id')
        ->leftjoin('sub_categories', 'sub_cat_sub_childs.sub_category_id', '=', 'sub_categories.id')
        ->leftjoin('sub_cat_childs', 'sub_cat_sub_childs.child_id', '=', 'sub_cat_childs.id')
        ->select(
            'sub_cat_sub_childs.*',
            'categories.category_name as getCategoryName',
            'sub_categories.sub_category_name as subCategoryName',
            'sub_cat_childs.child_name as child_name',
        )
        ->where('categories.is_active', '1')
        ->where('categories.is_delete', '0')
        ->where('sub_categories.is_active', '1')
        ->where('sub_categories.is_delete', '0')
        ->where('sub_cat_sub_childs.is_deleted', '0')
        ->where(function ($query) {
            $query->where(function ($query) {
                $query->where('sub_cat_childs.is_active', '1')
                    ->where('sub_cat_childs.is_deleted', '0');
            })
                ->orWhereNull('sub_cat_childs.id');
        })
        ->orderBy('sub_cat_sub_childs.priority', 'asc')
        ->get();
        return view('admin.sub_child', compact('testerQuizzes', 'getCategories', 'sub_cat_child', 'sub_cat_sub_child'));
    }
    public function saveSubCatSubChild(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'sub_child_name' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'priority' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        if($request->has('child_id')){
            $existingSubChild = SubCatSubChild::where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->where('child_id',$request->child_id)->where('sub_child_name', $request->sub_child_name)->first();
        }else{
            $existingSubChild = SubCatSubChild::where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->where('sub_child_name', $request->sub_child_name)->first();
        }
        if($existingSubChild !=null && $existingSubChild->is_deleted === "1"){
            $existingSubChild->is_deleted = "0";
            $existingSubChild->category_id = $request->category_id;
            $existingSubChild->sub_category_id = $request->sub_category_id;
            $existingSubChild->child_id = $request->child_id;
            $existingSubChild->sub_child_name = $request->sub_child_name;
            $existingSubChild->priority = $request->priority;
            $uploadPath = public_path('assets/subcategory/');
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move(public_path('assets/subcategory'), $filename);

                // Unlink existing image
                if ($existingSubChild->image) {
                    $existingImagePath = public_path('assets/subcategory/') . $existingSubChild->image;
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }
                $existingSubChild->image = $filename;
            } else {
                $existingSubChild->image;
            }
            $existingSubChild->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $existingSubChild->update();
            Session::flash('success', 'Sub Child Added Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Child Added Successfully');
        } elseif ($existingSubChild) {
            Session::flash('error', 'The Sub Child Already Exists!');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'The Sub Child Already Exists!');
        } else {
            $saveSubCategory = new SubCatSubChild();
            $saveSubCategory->category_id = $request->category_id;
            $saveSubCategory->sub_category_id = $request->sub_category_id;
            $saveSubCategory->child_id = $request->child_id;
            $saveSubCategory->sub_child_name = $request->sub_child_name;
            $saveSubCategory->priority = $request->priority;
            if ($request->has('image')) {
                $files = $request->image;
                $categoryImage = date("dmyHis.") . '_' . $files->getClientOriginalName();
                // return public_path();
                $files->move(public_path() . '/assets/subcategory/', $categoryImage);
                $saveSubCategory->image = $categoryImage;
            }
            $saveSubCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $saveSubCategory->save();
            if ($saveSubCategory) {
                Session::flash('success', 'Sub Child Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'Sub Child Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    public function updateSubCatSubChild(Request $request){
        $validate = Validator::make($request->all(), [
            'sub_child_name' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'priority' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        if($request->has('child_id')){
            $existingSubChild = SubCatSubChild::where('id','!=',$request->sub_child_id)->where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->where('child_id',$request->child_id)->where('sub_child_name', $request->sub_child_name)->first();
        }else{
            $existingSubChild = SubCatSubChild::where('id','!=',$request->sub_child_id)->where('category_id',$request->category_id)->where('sub_category_id',$request->sub_category_id)->where('sub_child_name', $request->sub_child_name)->first();
        }
        if ($existingSubChild) {
            Session::flash('error', "SubChild Already Added.!");
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $updateSubChild = SubCatSubChild::find($request->sub_child_id);
        $updateSubChild->category_id = $request->category_id;
        $updateSubChild->sub_category_id = $request->sub_category_id;
        $updateSubChild->child_id = $request->child_id;
        $updateSubChild->sub_child_name = $request->sub_child_name;
        $updateSubChild->priority = $request->priority;
        $uploadPath = public_path('assets/subcategory/');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/subcategory'), $filename);
            if ($updateSubChild->image) {
                $existingImagePath = public_path('assets/subcategory/') . $updateSubChild->image;
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
            $updateSubChild->image = $filename;
        } else {
            $updateSubChild->image;
        }
        $updateSubChild->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $updateSubChild->update();
        if ($updateSubChild) {
            Session::flash('success', 'Sub Child Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Child Updated Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function editSubChild(Request $request)
    {
        $getCategories = Category::where('is_delete', '0')->where('is_active', '1')->orderBy('created_at', 'DESC')->get();
        $getSubCategories = SubCategory::where('category_id',$request->category_id)->where('is_delete', '0')->where('is_active', '1')->orderBy('created_at', 'DESC')->get();
        $childs = SubCatChild::where('sub_category_id',$request->subCategory_id)->where('is_deleted', '0')->where('is_active', '1')->orderBy('created_at', 'DESC')->get();
        $getQuizData = SubCatSubChild::find($request->sub_child_id);
        return response()->json(['success' => '1', 'data' => $getQuizData, 'category' => $getCategories, 'sub_category' => $getSubCategories, 'child' => $childs]);
    }
    public function deleteSubChild($id)
    {
        $getQuiz = SubCatSubChild::find($id);
        $getQuiz->is_deleted = '1';
        $getQuiz->update();
        if ($getQuiz) {
            Session::flash('success', 'Sub Child Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Sub Child Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function Files()
    {
        //new working 
        $categories = DB::table('categories')
        ->join('sub_categories','sub_categories.category_id', '=', 'categories.id')
        ->where('categories.is_delete', '0')->where('categories.is_active','1')
        ->where('sub_categories.is_delete', '0')
        ->select('sub_categories.*','categories.category_name as getCategoryName')
        ->orderBy('sub_categories.priority', 'asc')->get();


        foreach($categories as $cat){

            $subcategory = DB::table('sub_cat_childs')
            ->leftJoin('categories', 'sub_cat_childs.category_id', '=', 'categories.id')
            ->leftjoin('sub_categories', 'sub_cat_childs.sub_category_id', '=', 'sub_categories.id')
            ->leftjoin('sub_cat_sub_childs', 'sub_cat_sub_childs.child_id', '=', 'sub_cat_childs.id')
            ->select('categories.id as category_id','categories.category_name','sub_categories.id as sub_category_id','sub_categories.sub_category_name','sub_cat_childs.id as child_id','sub_cat_childs.child_name','sub_cat_sub_childs.id as sub_child_id','sub_cat_sub_childs.sub_child_name')
            ->where('categories.is_active', '1')
            ->where('categories.is_delete', '0')
            ->where('sub_categories.is_active', '1')
            ->where('sub_categories.is_delete', '0')
            ->where('sub_cat_childs.is_deleted', '0')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('sub_cat_sub_childs.is_active', '1')
                        ->where('sub_cat_sub_childs.is_deleted', '0');
                })
                    ->orWhereNull('sub_cat_sub_childs.id');
            })
            ->get();
            $is_exsist = 0;
            foreach($subcategory as $subCat){
                if($subCat->category_id == $cat->category_id && $cat->id == $subCat->sub_category_id){
                    $is_exsist = 1;
                }
            }
            if($is_exsist == 0){
                $newObject = (object)[
                    'category_id' => $cat->category_id,
                    'category_name' => $cat->getCategoryName,
                    'sub_category_id' => $cat->id,
                    'sub_category_name' => $cat->sub_category_name,
                    'child_id' => null,
                    'child_name' => null,
                    'sub_child_id' => null,
                    'sub_child_name' => null,
                ];
                
                // Assuming $yourArray is your existing array
                $allFiles[] = $newObject;
            }
        }


        $subcategory = collect($subcategory)->merge($allFiles)->all();

        foreach($subcategory as $data){
            $category = $data->category_id;
            $sub_category = $data->sub_category_id;
            $child = $data->child_id;
            $sub_child = $data->sub_child_id;
            $getfiles = Files::where('is_delete', '0')
            ->where(function ($query) use ($sub_child,$category, $sub_category, $child) {
                if ($child && $child != 0) {
                    $query->Where('child_id', $child);
                }
                if ($category) {
                    $query->Where('category_id', $category);
                }
                if ($sub_category) {
                    $query->Where('sub_category_id', $sub_category);
                }
                if ($sub_child && $sub_child != 0) {
                    $query->where('sub_child_id', $sub_child);
                }
            })->pluck('id');

            if($getfiles){
                $data->fileCount = FileContent::whereIn('file_id',$getfiles)->where('is_deleted','0')->count();
            }else{
                $data->fileCount = 0;
            }
        }

        return view('admin.files', compact('subcategory'));
    }
    // public function Files()
    // {
    //     $categories = Category::where('is_delete', '0')->where('is_active', '1')->get();
    //     foreach ($categories as $key => $category) {
    //         $category->subCategory = SubCategory::where('is_delete', '0')->where('is_active', '1')
    //             ->where('category_id', $category->id)
    //             ->select('sub_category_name', 'id')
    //             ->first();
    //         if ($category->subCategory) {
    //             $category->subCategoryChild = SubCatChild::where('is_deleted', '0')->where('is_active', '1')
    //                 ->where('sub_category_id', $category->subCategory->id)
    //                 ->select('child_name', 'id')
    //                 ->first();
    //             if ($category->subCategoryChild) {
    //                 $category->subCategorySubChild = SubCatSubChild::where('is_deleted', '0')->where('is_active', '1')
    //                     ->where('child_id', $category->subCategoryChild->id)
    //                     ->select('sub_child_name', 'id')
    //                     ->first();
    //             } else {
    //                 $category->subCategorySubChild =SubCatSubChild::where('is_deleted', '0')->where('is_active', '1')
    //                 ->where('sub_category_id', $category->subCategory->id)
    //                 ->select('sub_child_name', 'id')
    //                 ->first();
    //             }
    //         } else {
    //             $category->subCategoryChild = null;
    //             $category->subCategorySubChild = null;
    //         }
    //         $category->File = Files::where('is_delete', '0')
    //             ->where(function ($query) use ($category) {
    //                 $query->where('category_id', $category->id);
    //                 if ($category->subCategory) {
    //                     $query->orWhere('sub_category_id', $category->subCategory->id);
    //                     if ($category->subCategoryChild) {
    //                         $query->orWhere('child_id', $category->subCategoryChild->id);
    //                         if ($category->subCategorySubChild) {
    //                             $query->orWhere('sub_child_id', $category->subCategorySubChild->id);
    //                         }
    //                     }
    //                 }
    //             })
    //             ->first();
    //         if ($category->File) {
    //             $category->fileCount = FileContent::where('file_id', $category->File->id)->count();
    //         } else {
    //             $category->fileCount = null;
    //         }
    //     }
    //     return view('admin.files', compact('categories'));
    // }
    public function fileList()
    {
        $getSubCategory = SubCategory::where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        $getfiles = Files::where('is_delete', '0')->orderBy('created_at', 'DESC')->get();
        foreach ($getfiles as $key => $item) {
            $item->subCatName = SubCategory::where('is_delete', '0')->where('id', $item->sub_category_id)->select('sub_category_name')->first();
            $item->countryName = Region::where('is_deleted', 'N')->where('id', $item->region_id)->select('region_name')->first();
        }
        $processedData = [];
        $counts = [];

        foreach ($getfiles as $item) {
            $subCategoryId = $item->sub_category_id;
            if (!array_key_exists($subCategoryId, $counts)) {
                $counts[$subCategoryId] = 0;
            }
            if ($item->files != null) {
                $counts[$subCategoryId]++;
            }
            if (!array_key_exists($subCategoryId, $processedData)) {
                $processedData[$subCategoryId] = $item;
            }
        }
        // return $getfiles;
        $country = Country::where('is_deleted', 'N')->get();
        return view('admin.quiz', compact('getSubCategory', 'country', 'getfiles', 'counts'));
    }
    public function fileType()
    {
        $file_type = FileType::where('is_deleted', '0')->orderBy('priority', 'asc')->get();
        return view('admin.file-type', compact('file_type'));
    }
    public function saveFileType(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'file_type' => 'required',
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $existingFileType = FileType::where('file_type', $request->file_type)->first();
        if($existingFileType !=null && $existingFileType->is_deleted === "1"){
            $existingFileType->is_deleted = "0";
            $existingFileType->file_type = $request->file_type;
            $existingFileType->priority = $request->priority;
            $uploadPath = public_path('assets/subcategory/');
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move(public_path('assets/subcategory'), $filename);

                // Unlink existing image
                if ($existingFileType->image) {
                    $existingImagePath = public_path('assets/subcategory/') . $existingFileType->image;
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }
                $existingFileType->image = $filename;
            } else {
                $existingFileType->image;
            }
            $existingFileType->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $existingFileType->update();
            Session::flash('success', 'File Type Added Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'File Type Added Successfully');
        } elseif ($existingFileType) {
            Session::flash('error', 'The File Type Already Exists!');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'The File Type Already Exists!');
        } else {
            $saveFileType = new FileType();
            $saveFileType->file_type = $request->file_type;
            $saveFileType->priority = $request->priority;
            if ($request->has('image')) {
                $files = $request->image;
                $fileImage = date("dmyHis.") . '_' . $files->getClientOriginalName();
                // return public_path();
                $files->move(public_path() . '/assets/subcategory/', $fileImage);
                $saveFileType->image = $fileImage;
            }
            $saveFileType->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
            $saveFileType->save();
            if ($saveFileType) {
                Session::flash('success', 'File Type Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with('Success', 'File Type Added Successfully');
            } else {
                Session::flash('error', 'An Error occured! Please try again later');
                Session::flash('alert-class', 'alert-error');
                return redirect()->back()->with('Error', 'An Error occured! Please try again later');
            }
        }
    }
    public function updateFileType(Request $request){
        $validate = Validator::make($request->all(), [
            'file_type' => 'required|unique:file_types,file_type,' . $request->file_type_id,
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $updateFile = FileType::find($request->file_type_id);
        $updateFile->file_type = $request->file_type;
        $updateFile->priority = $request->priority;
        $uploadPath = public_path('assets/subcategory/');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move(public_path('assets/subcategory'), $filename);
            if ($updateFile->image) {
                $existingImagePath = public_path('assets/subcategory/') . $updateFile->image;
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
            $updateFile->image = $filename;
        } else {
            $updateFile->image;
        }
        $updateFile->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $updateFile->update();
        if ($updateFile) {
            Session::flash('success', 'File Type Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'File Type Updated Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function editFyleType(Request $request)
    {
        $file_type = FileType::findOrFail($request->file_type_id);
        return response()->json(['success' => '1', 'data' => $file_type,]);
    }
    public function deleteFileType($id)
    {
        $getQuiz = FileType::find($id);
        $getQuiz->is_deleted = '1';
        $getQuiz->update();
        if ($getQuiz) {
            Session::flash('success', 'File Type Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'File Type Deleted Successfully');
        } else {
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
}
