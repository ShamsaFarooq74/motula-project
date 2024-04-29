<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Admin\CommunicationController;
use App\User;
use Illuminate\Http\Request;
use App\Http\Models\Cities;
use App\Http\Models\Setting;
use App\Http\Models\NotificationEmail;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function loginScreen()
    {
        return view('web.login');
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->with('error', $validate->errors()->first());
        }
        if (!User::where('email', $request['email'])->exists()) {
            Session::flash('error', 'Invalid Email Address');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->with('error', 'Invalid Email Address');
        } else {
            $request['email'] = User::where('email', $request['email'])->first()['email'];
        }
        $is_deleted = User::where('email', $request['email'])->first();
        // dd($is_deleted);
        if($is_deleted->is_delete == "1"){
            Session::flash('error', 'Your Account was deleted previously');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->with('error', 'Your Account was deleted previously');
        }
        if($is_deleted->is_active == "0"){
            Session::flash('error', 'Your Account is Inactive');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->with('error', 'Your Account is Inactive');
        }
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            Session::flash('error', 'Invalid credentials! Please try again');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->with('error', 'Invalid credentials! Please try again');
        }

        $user = $request->user();
//        $user['token'] = $user->createToken('token')->accessToken;
        if ($user) {
                if ($user->role == 1) {
                    Auth::attempt($credentials);
                    Session::flash('success', 'You are logged in');
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('lesson.bank'); 
                }
                elseif($user->role == 2){
                    Auth::attempt($credentials);
                    Session::flash('success', 'You are logged in');
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('index');
                } elseif($user->role == 3){
                    Auth::attempt($credentials);
                    Session::flash('success', 'You are logged in');
                    Session::flash('alert-class', 'alert-success');
                    return redirect()->route('lesson.topic'); 
                }

        } else {
            Session::flash('error', 'Login Unsuccessful');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('web.login')->with('error', 'Login Unsuccessful');
        }
    }

    public function welcomeScreen()
    {

        return view('web.welcome_screen');
    }

    public function selectRole(Request $request)
    {
        Session::put('role', $request->role);
        return redirect()->route('web.signup');
    }

    public function signupscreen(Request $request)
    {
        // $allSellersType = DB::table('sellers_types')->get();
        // $cities = Cities::all();
        return view('web.signup');
    }

    public function signupverify(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'This Email is already taken!!']);
        }
        if($request->email != "")
        {
            return response()->json(['success'=> "1", 'email'=>$request->email]);
        }
    }
    public function signupEmailverify(Request $request)
    {
        $email = $request->email;
        $otp = rand ( 10000 , 99999 );
        $body = $this->getOtpEmail($otp);
        $mail = Setting::where('perimeter', 'communication_email')->first()['value'];

        $noti_email = new NotificationEmail();
        $noti_email->to_email = $email;
        $noti_email->from_email = $mail;
        $noti_email->email_subject = 'Email Verification';
        $noti_email->email_body = $body;
        $noti_email->schedule_date = \Carbon\Carbon::now();
        $noti_email->email_sent_status = 'N';
        $noti_email->campaign_entry = '0';
        $noti_email->save();
        $SendNotification = new CommunicationController();
        $SendNotification->send_comm_email();
            return response()->json(['success'=> '1', 'otp' => $otp]);

    }
    public function getOtpEmail($otp){
        $otp = $otp;
        return view('auth.passwords.otpemail', compact("otp"));
    }
    public function signup(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->error()]);
        } else {
            $user = new User;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->full_name = $request->full_name;
            $user->role = 2;
            // $user->full_name = $request->full_name;
            $user->password = Hash::make($request->password);
            $result = $user->save();
            if($result){
            $data = [
                'email' => $request->email,
                'password' => $request->password,
                'is_delete' => '0',
            ];
            Auth::attempt($data);
            Session::flash('success', 'Your account has been Registered');
            Session::flash('alert-class', 'alert-success');
            // return redirect()->route('web.login');
            return response()->json(['success' => 'you have been registered']);
            }
            else{
                return response()->json(['error' => 'There was some issue in registering your account']);
            }


        }

    }
    public function switchUser(Request $request){
        // return $request->all();
        $validate = Validator::make($request->all(), [
            'seller_type' => 'required',
            'user_type' => 'required',
        ]);
        if ($validate->fails()) {
            // Session::flash('error', 'Password and Confirm Password are not matching ');
            return redirect()->back()->with('error', 'Please Fill in all the Fields');
        }
        else{
            $user = User::where('id', Auth::user()->id)->first();
            $user->type = $request->user_type;
            $user->address = $request->address;
            $user->ntn = $request->ntn;
            if($request->ntn !=""){
                $user->is_verified = "Y";
            }
            $seller_type = DB::table('sellers_types')->where('id',$request->seller_type)->first();
            $user->seller_type = $seller_type->seller_type;
            $user->update();
            if($user){
                return redirect()->route('switch.role');
            }

        }
    }
}

