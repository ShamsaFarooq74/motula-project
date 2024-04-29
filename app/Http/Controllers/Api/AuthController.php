<?php


namespace App\Http\Controllers\Api;

use App\Http\Models\Notification;
use App\Http\Models\Setting;
use App\ResetPassword;
use App\User;
use Exception;
use App\Country;
use App\Http\Models\Unit;
use App\Http\Models\Cities;
use Illuminate\Http\Request;
use App\Http\Models\UserDevice;
use App\Rules\OldPasswordMatch;
use App\Rules\PhoneNumberExist;
use App\Http\Models\SubCategory;
use App\Http\Models\Configuration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Models\ProductCategory;
use App\Http\Models\SubCategoryUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends ResponseController
{
    public function test()
    {
        return "hy";
    }
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(0, $validator->errors()->first(), '');
        }
        if ($request->role == 5) {
            if (empty($request->location)) {
                return $this->sendError(0, "Location cannot be empty!");
            }
            if (empty($request->seller_type)) {
                return $this->sendError(0, "seller_type cannot be empty!");
            }
        }
        $request['password'] = Hash::make($request['password']);
        $user = new User();
        $user->region_id = $request->region_id;
        $user->role = $request->role;
        $user->full_name = $request->full_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->is_active = $request->status;
        $user->is_delete = $request->is_delete;
        $user->save();
        if ($user) {
            $message = "Registration successful";
            return $this->sendResponse(1, $message, $user);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }

    }
    public function addUserDeviceInfo(Request $request, $userId)
    {
        $userDeviceInfo = UserDevice::updateOrCreate(["serial" => $request['serial']], array_merge($request->except(['name', 'phone', 'password', 'login_with']), ['status' => 'A', 'user_id' => $userId]));
        // dd($userDeviceInfo);
        return $userDeviceInfo;
    }
    //login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(0, $validator->errors()->first(), '');
        }
        $user = User::where('is_active','1')->where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError(0, 'Unauthorized. Invalid email or password.', 401);
        }
        if (Hash::check($request->password, $user->password)) {
            if($user->is_active == '0' || $user->is_delete == '1'){
                return $this->sendError(0, 'Unauthorized. Your account is deleted or Inactive Please contact with admin for more detail!.', 200);
            }
            $user['token'] = $user->createToken('MyApp')->accessToken;
            if(file_exists(public_path('assets/images/'. $user->image))) {
                $user['image'] = asset('assets/images/' . $user->image);
            }else{
                $user['image'] = null;
            }
            $message = "Login successful";
            return $this->sendResponse(1, $message, $user);
        } else {
            return $this->sendError(0, 'Unauthorized. Invalid email or password.', 401);
        }
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens()->delete();
            return $this->sendResponse(1, 'Logged out Successfully!', null);
        } else {
            return $this->sendError(0, "Something went wrong!", null);
        }
    }



    //     public function login(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             // 'phone' => 'required|string',
//             'password' => 'required',
//             'platform' => 'required',
//             'serial' => 'required'
//         ]);

    // //        $request['phone'] = strip_tags($request['phone']);
// //        $request['phone'] = substr($request['phone'], -10);

    //         $platform = $request['platform'];

    //         if ($platform == 'iOS') {
//             $validator = Validator::make($request->all(), [
//                 'app_version' => 'required',
//             ]);
//         } elseif ($platform == 'android') {
//             $validator = Validator::make($request->all(), [
//                 'app_version' => 'required',
//             ]);
//         }

    //         // dd($request->all());
//         if ($validator->fails()) {
//             return $this->sendError(0, "Sorry! Might be required fields are not found or empty.", $validator->errors()->all());
//         }

    //         $status = $this->check_version($request->all());
//         $request['phone'] = strip_tags($request['phone']);
//         $request['phone'] = substr($request['phone'], -10);
//         if (strlen($request['phone']) != 10) {
//             return $this->sendError(0, 'invalid mobile number');
//         }

    //         if (!User::where('phone', 'LIKE', '%' . $request['phone'])->exists()) {
//             $error = "Invalid mobile number";
//             return $this->sendResponse(0, $error, null);
//         } else {
//             $request['phone'] = User::where('phone', 'LIKE', '%' . $request['phone'])->first()['phone'];
//         }
//         $is_deleted = User::where('phone', 'LIKE', '%' . $request['phone'])->first();
//         if($is_deleted->is_deleted == "Y"){
//             $error = "Your Account was deleted previously";
//             return $this->sendResponse(0, $error, null);
//         }
//         $credentials = request(['phone', 'password']);
//         if (!Auth::attempt($credentials)) {

    //             $error = "Invalid credentials! Please try again";
//             return $this->sendResponse(0, $error, null);
//         }

    //         $user = $request->user();
//         $user['token'] = $user->createToken('token')->accessToken;
//         $this->addUserDeviceInfo($request, $user->id);
//         if ($user) {
//             // dd($user);
//             if ($user->role == 1 || $user->role == 2 || $user->role == 3) {

    //                 Auth::logout();
//                 $status = -1;
//                 $message = "Sorry! You cant logged in.";
// //                return $this->sendError($status,$message);
//                 return $this->sendResponse($status, $message, null);
//             } else {
//                 if ($status != 1) {
//                     $message = "New features and services are added, please update the app";
//                 } else {
//                     $message = "Login successful";
//                 }
//            //     $user['assets_count'] = 0;
//                 $user['notificationCount'] = Notification::where('user_id',Auth::user()->id)->where('read_status','N')->count();
//                 if ($user->image != null) {
//                     $user['image'] = getImageUrl($user->image, 'images');
//                 }else{
//                     $user['image'] = getImageUrl('profile.png', 'images12');
//                 }
//                 return $this->sendResponse($status, $message, $user);
//             }

    //         } else {
//             $message = "Login unsuccessful";
//             return $this->sendResponse(0, $message, null);
// //            return $this->sendError(0,$message);
//         }
//     }
    private function check_version($request)
    {
        $platform = $request['platform'];
        $version = '';
        if ($platform == 'iOS') {
            $version = $request['app_version'];
        } elseif ($platform == 'android') {
            $version = $request['app_version'];
        }
        // dd($platform,$version);
        $get_data = DB::select("SELECT * FROM platform_version WHERE platform = '" . $platform . "'");
        $from_version = $get_data[0]->from_version;
        $to_version = $get_data[0]->to_version;

        if ($version < $from_version && $version >= $to_version) {
            return -4;
        } else if ($version < $to_version) {
            return -5;
        } else {
            return 1;
        }
    }
    public function updateProfile(Request $request)
    {
        $userID = $request->user()->id;
        $user = User::find($userID);
        if ($userID) {
            // get user details
            $user = User::where('id', $userID)->first();

            $data = array();
            // checks on each param
            if ($request['new_password']) {
                if (Hash::check($request['password'], $user->password)) {
                    //password is correct use your logic here
                    $data = array();
                    $data['password'] = Hash::make($request['new_password']);
                } else {
                    return $this->sendResponse(0, 'Password does not match!', null);

                }
            }
            if ($request['name']) {
                $data['name'] = $request['name'];
            }
            if ($request['phone']) {
                $data['phone'] = $request['phone'];
            }

            // updating data
            $user->update($data);

            // pic updation
            if ($request->has('profile_pic')) {
                $format = '.png';
                $entityBody = $request->file('profile_pic'); // file_get_contents('php://input');

                $imageName = $user->id . time() . $format;
                $directory = "/user_photo/";
                $path = base_path() . "/public" . $directory;

                $entityBody->move($path, $imageName);

                $response = $directory . $imageName;

                $user->profile_pic = $response;
                $user->save();
            }

            $message = "Profile updated successfully";
            return $this->sendResponse(1, $message, $user);

        } else {
            return $this->sendError(0, "User Id not found!", null);
        }
    }
    //logout
    // public function logout(Request $request)
    // {

    //     $isUser = $request->user()->token()->revoke();
    //     if ($isUser) {
    //         if ($request->has("serial")) {
    //             UserDevice::where("serial", $request["serial"])->update(["status" => "D", 'manufacturer' => $request->manufacturer, 'model' => $request->model, 'platform' => $request->platform, 'app_version' => $request->app_version, 'os_version' => $request->os_version]);
    //         }
    //         return $this->sendResponse(1, 'Successfully logged out', null);
    //     } else {
    //         return $this->sendError(0, "Something went wrong!", null);
    //     }


    // }
    public function phoneVerify(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => ['required', new PhoneNumberExist],
        ]);

        if ($validator->fails()) {

            return $this->sendError(0, $validator->errors()->first());
        } else {

            $message = 'This phone number is already taken.';
            return $this->sendResponse(1, $message, null);
        }
    }
    public function forgetPassword(Request $request)

    {

        $request->validate([
            'email' => 'required|email|exists:users',
        ],[
            'email.exists' => "We couldn't find an account associated with that email address."
        ]);
        $min = 1000; 
        $max = 9999; 
        $otp =
       random_int($min,$max);
        DB::table('password_resets')->where('email',$request->email)->delete();
        $expires_at = now()->addMinutes(15);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $otp,
            'created_at' => Carbon::now(),
            'expires_at' => $expires_at,
        ]);
        $user = User::where('email',$request->email)->first();
        // Mail::send('email.forgetPassword', ['name'=>$user->full_name,'otp' => $otp], function ($message) use ($request) {

        //     $emailServicesFromName = Setting::where('perimeter', 'smtp_from_name')->first();

        //     $emailServicesFromEmail = Setting::where('perimeter', 'smtp_from_email')->first();
        //     $message->from($emailServicesFromEmail->value, $emailServicesFromName->value);
        //     $message->to($request->email);

        //     $message->subject('Reset Password');

        // });
        if ($user) {
            $message = "An OTP to reset your password has been emailed to you.";
            return $this->sendResponse(1, $message, $user);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function verifyPasswordOTP(Request $request)
    {

        $validator = Validator::make($request->all(),[

            'verify_otp' => 'required',

        ]);

        if ($validator->fails()){

            return $this->sendError('Something went wrong!', $validator->errors());

        }

        $otp = $request->verify_otp;

        DB::table('password_resets')->where('expires_at', '<', now())->delete();

        $reset = DB::table('password_resets')->where(['token' => $otp])->first();



        if ($reset && $reset->email && now() <= $reset->expires_at) {

            // Token is valid and not expired, allow password reset

            $email = '';

            $email = $reset->email;

            $user_id = User::where('email',$email)->select('id')->first();



            $response['email'] =$email;
            $response['OTP'] =$otp;



            return $this->sendResponse($response,'OTP matched successfully!');

        } else {
            return $this->sendError([],'You entered wrong OTP!');
        }

    }
    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validator->fails())
        {
            return $this->sendError('Something went wrong!', $validator->errors());
        }
        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        if ($user) {
            return $this->sendResponse([],'Password Reset Successfully!');
        } else {
            return $this->sendError([],'Password Not Reset!');
        }

    }
    public function changePassword(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'old_password' => ['required', new OldPasswordMatch],
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);

            if ($validator->fails()) {

                return $this->sendError(0, $validator->errors()->first());
            }

            $request['password'] = Hash::make($request['new_password']);

            $user = User::where('id', $request->user()->id)->update(['password' => $request['password']]);

            $message = 'Password changed successfully';
            return $this->sendResponse(1, $message, null);
        } catch (Exception $e) {

            return $this->sendError(0, $e->getMessage());
        }
    }

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->sendError(0, "Something went wrong! Please try again", $validator->errors()->all());
        }

        $request['password'] = Hash::make($request['password']);
        $request['role'] = 1;
        $user = User::create($request->except(["confirm_password"]));
        if ($user) {
            $user['token'] = $user->createToken('token')->accessToken;
            $message = "Registration successful";
            $user = User::find($user->id);
            //inserting user device record
            UserDevice::updateOrCreate(["serial" => $request['serial']], array_merge($request->except(['name', 'phone', 'password', 'login_with']), ['status' => 'A', 'user_id' => $user->id]));
            return $this->sendResponse(1, $message, $user);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function adminList()
    {
        try {
            return ['data' => User::where('role', '1')->get()];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => false, "message" => $exception->getMessage()]);
        }
    }
    public function getCountries()
    {
        $countries = Country::select('id', 'name')->get();
        return $this->sendResponse(1, 'Success', $countries);
    }
    public function signUpSettings()
    {

        $cities = Cities::Select('id as city_id', 'name as city_name')->where('region_id', '166')->get();
        $units = Unit::where('is_deleted', 'N')->get();

        $category = ProductCategory::where('is_deleted', 'N')->get();
        for ($k = 0; $k < count($category); $k++) {
            $category[$k]['sub-category'] = SubCategory::where('category_id', $category[$k]['id'])->where('is_deleted', 'N')->get();
            for ($l = 0; $l < count($category[$k]['sub-category']); $l++) {
                if (SubCategoryUnit::where('sub_category_id', $category[$k]['sub-category'][$l]['id'])->exists()) {
                    $unitId = SubCategoryUnit::where('sub_category_id', $category[$k]['sub-category'][$l]['id'])->pluck('unit_id');
                    $category[$k]['sub-category'][$l]['unit'] = Unit::whereIn('id', $unitId)->where('is_deleted', 'N')->get();
                } else {
                    $category[$k]['sub-category'][$l]['unit'] = [];
                }
            }
        }
        $pairCount = Configuration::where('key', 'pair_count')->first()['value'];
        $data = ['cities' => $cities, 'unit' => $units, 'category' => $category, 'pairCount' => (int) $pairCount];
        return $this->sendResponse(1, 'success', $data);
    }
    function deleteUser()
    {
        $userDetail = User::where('id', Auth::user()->id)->first();
        $userDetail->is_deleted = "Y";
        $user = $userDetail->save();
        if ($user) {
            return response()->json(['status' => 1, 'message' => 'User Deleted Successfully!']);
        } else {
            return response()->json(['status' => 0, 'message' => "User Didn't Deleted "]);
        }
    }
}
