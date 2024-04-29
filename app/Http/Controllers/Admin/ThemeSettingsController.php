<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Models\Unit;
use App\Http\Models\Leads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\ProductCategory;
use App\Http\Models\UserRequirement;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\NotificationEmail;
use App\Http\Models\Setting;
use App\Http\Models\UserQuizResultStats;
use App\Http\Models\Region;
use App\Http\Models\Blogs;
use App\Http\Models\UserCourseTransactions;
use App\Http\Models\QuizOptions_Drag_Drop;
use App\Http\Models\Category;
use App\Http\Models\QuizOptions_Multiple_Choices;
use App\Http\Models\Files;
use App\Http\Models\Feature;
use App\Http\Models\UserQuizResults;
use App\Http\Models\SubCategory;
use App\Http\Models\BlogTypes;
use App\Http\Models\Courses;
use App\Http\Models\ThemeSettings;
use App\Http\Models\Config;
use App\Http\Models\UserCourses;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ThemeSettingsController extends Controller
{
    public function Smtp(){
        $smtp_from_email = DB::table('settings')
        ->where('perimeter', 'smtp_from_email')
        ->first();
        $smtp_from_name = DB::table('settings')
            ->where('perimeter', 'smtp_from_name')
            ->first();
        $smtp_encryption = DB::table('settings')
            ->where('perimeter', 'smtp_encryption')
            ->first();
        $smtp_user_name = DB::table('settings')
            ->where('perimeter', 'smtp_email')
            ->first();
        $smtp_host = DB::table('settings')
            ->where('perimeter', 'smtp_host')
            ->first();
        $smtp_password = DB::table('settings')->where('perimeter','smtp_password')->first();
        $smtp_port = DB::table('settings')
            ->where('perimeter', 'smtp_port')
            ->first();
        return view('admin.smtp',compact('smtp_from_email','smtp_from_name','smtp_encryption','smtp_user_name','smtp_host','smtp_password','smtp_port'));
    }
    public function addSMTP(Request $request){
        $updateFields = [
            'email' => 'smtp_from_email',
            'formname' => 'smtp_from_name',
            'encryption' => 'smtp_encryption',
            'username' => 'smtp_email',
            'smtphost' => 'smtp_host',
            'password' => 'smtp_password',
            'port' => 'smtp_port',
        ];

        foreach ($updateFields as $field => $perimeter) {
            $value = $request->$field;

            DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $value]);
        }

        Session::flash('success', 'SMTP Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'SMTP Updated Successfully');
    }

    public function Stripe(){
        $getStripeKeys = Setting::where('perimeter','Stripe_Key')->first()['value'];
        $getStripeSecret = Setting::where('perimeter','Stripe_Secret')->first()['value'];
        return view('admin.stripekeys',compact('getStripeKeys','getStripeSecret'));
    }
    public function updateStripe(Request $request){
        $getStripeKeys = Setting::where('perimeter','Stripe_Key')->update(['value'=>$request->Stripe_Key]);
        $getStripeSecret = Setting::where('perimeter','Stripe_Secret')->update(['value'=>$request->Stripe_Secret]);
        if($getStripeSecret){
            Session::flash('success', 'Stripe Settings Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Stripe Settings Updated Successfully');
        }
        else{
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function saveAppearance(Request $request){
        $validate = Validator::make($request->all(), [
            'theme_name' => 'required',
            'BTN_BG_COLOR' => 'required',
            'BTN_COLOR' => 'required',
            'BTN_SHADOW_COLOR' => 'required',
            'HEADING_COLOR' => 'required',
            'TEXT_COLOR' => 'required',
            'SUB_HEADING_COLOR' => 'required',
            'BG_COLOR' => 'required',
            'ICON_COLOR' => 'required',
            'RADIO_BG_COLOR' => 'required',

        ]);
        if ($validate->fails()) {
            Session::flash('error', $validate->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
       if($request->has('theme_id') && $request->theme_id !=null){

            $addTheme = ThemeSettings::find($request->theme_id);
       }
       else{
            $addTheme = new ThemeSettings();
       }
       $hexColor = $request->BTN_SHADOW_COLOR;

        // Remove the '#' symbol if it exists
        if (strpos($hexColor, '#') === 0) {
            $hexColor = substr($hexColor, 1);
        }

        // Convert the hexadecimal color to RGB values
        $red = hexdec(substr($hexColor, 0, 2));
        $green = hexdec(substr($hexColor, 2, 2));
        $blue = hexdec(substr($hexColor, 4, 2));
        $addTheme->theme_name = $request->theme_name;
        $addTheme->BTN_BG_COLOR = $request->BTN_BG_COLOR;
        $addTheme->BTN_COLOR = $request->BTN_COLOR;
        $addTheme->BTN_SHADOW_COLOR = $red.",".$green.",".$blue;
        $addTheme->TEXT_COLOR = $request->TEXT_COLOR;
        $addTheme->BG_COLOR = $request->BG_COLOR;
        $addTheme->ICON_COLOR = $request->ICON_COLOR;
        $addTheme->SUB_HEADING_COLOR = $request->SUB_HEADING_COLOR;
        $addTheme->HEADING_COLOR = $request->HEADING_COLOR;
        $addTheme->RADIO_BG_COLOR = $request->RADIO_BG_COLOR;
        // return $addTheme;
        $addTheme->save();
        if($request->has('apply')){
            $setActiveTheme = Setting::where('perimeter','Active_Theme_Id')->update(['value'=>$addTheme->id]);
            $updateBtn = Config::where('param','BTN_COLOR')->update(['value'=>$request->BTN_COLOR]);
            $updateBtnClr = Config::where('param','BTN_BG_COLOR')->update(['value'=>$request->BTN_BG_COLOR]);
            $updateBtnShd = Config::where('param','BTN_SHADOW_COLOR')->update(['value'=>$addTheme->BTN_SHADOW_COLOR]);
            $updateTxtClr = Config::where('param','TEXT_COLOR')->update(['value'=>$request->TEXT_COLOR]);
            $updateBGClr = Config::where('param','BG_COLOR')->update(['value'=>$request->BG_COLOR]);
            $updateIClr = Config::where('param','ICON_COLOR')->update(['value'=>$request->ICON_COLOR]);
            $updateHClr = Config::where('param','HEADING_COLOR')->update(['value'=>$request->HEADING_COLOR]);
            $updateBdrClr = Config::where('param','SUB_HEADING_COLOR')->update(['value'=>$request->SUB_HEADING_COLOR]);
            $updateRDBgClr = Config::where('param','RADIO_BG_COLOR')->update(['value'=>$request->RADIO_BG_COLOR]);
            if($updateRDBgClr && $request->has('theme_id')){
                Session::flash('success', 'Theme Appearance Updated and Applied Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('appearance.setting')->with('Success', 'Theme Appearance Updated and Applied Successfully!');
            }
            else{
                Session::flash('success', 'Theme Appearance Added and Applied Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('appearance.setting')->with('Success', 'Theme Appearance Added and Applied Successfully!');
            }
        }
        if($addTheme){
            if($request->has('theme_id')){
                Session::flash('success', 'Theme Appearance Updated Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('appearance.setting')->with('Success', 'Theme Appearance Added Successfully!');
            }
            else{
            Session::flash('success', 'Theme Appearance Added Successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('appearance.setting')->with('Success', 'Theme Appearance Added Successfully!');
            }
        }
        else{
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('appearance.setting')->with('Error', 'An Error occured! Please try again later');
        }

    }

    public function AppearanceSetting(){
        $getAllThemes = ThemeSettings::where('is_delete','0')->get();
        $getActiveTheme = Setting::where('perimeter','Active_Theme_Id')->first()['value'];
        return view('admin.appearance-setting',compact('getAllThemes','getActiveTheme'));
    }
    public function updateAppearance($id){
        $getThemeData = ThemeSettings::find($id);
        $rgb = str_replace(['(', ')', ' '], '', $getThemeData->BTN_SHADOW_COLOR);
        $getColors = explode(',',$rgb);
        $hexColor = '#' . sprintf('%02x%02x%02x', $getColors[0], $getColors[1], $getColors[2]);
        $getThemeData->BTN_SHADOW_COLOR = $rgb = $hexColor;
        return view('admin.new-appearance-setting',compact('getThemeData'));
    }
    public function setDefaultTheme($id){
        $setActiveTheme = Setting::where('perimeter','Active_Theme_Id')->update(['value'=>$id]);
        $getThemeData = ThemeSettings::find($id);
        $updateBtn = Config::where('param','BTN_COLOR')->update(['value'=>$getThemeData->BTN_COLOR]);
        $updateBtnClr = Config::where('param','BTN_BG_COLOR')->update(['value'=>$getThemeData->BTN_BG_COLOR]);
        $updateBtnShd = Config::where('param','BTN_SHADOW_COLOR')->update(['value'=>$getThemeData->BTN_SHADOW_COLOR]);
        $updateTxtClr = Config::where('param','TEXT_COLOR')->update(['value'=>$getThemeData->TEXT_COLOR]);
        $updateBGClr  = Config::where('param','BG_COLOR')->update(['value'=>$getThemeData->BG_COLOR]);
        $updateIClr   = Config::where('param','ICON_COLOR')->update(['value'=>$getThemeData->ICON_COLOR]);
        $updateHClr   = Config::where('param','HEADING_COLOR')->update(['value'=>$getThemeData->HEADING_COLOR]);
        $updateBdrClr = Config::where('param','SUB_HEADING_COLOR')->update(['value'=>$getThemeData->SUB_HEADING_COLOR]);
        $updateRDBgClr = Config::where('param','RADIO_BG_COLOR')->update(['value'=>$getThemeData->RADIO_BG_COLOR]);
        if($setActiveTheme){
            Session::flash('success', 'Theme Activated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Theme Activated Successfully');
        }
        else{
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }

    }
    public function deleteTheme($id){
        $deleteTheme = ThemeSettings::where('id',$id)->update(['is_delete'=>'1']);
        if($deleteTheme){
            Session::flash('success', 'Theme Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Theme Deleted Successfully');
        }
        else{
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
    public function NewAppearanceSetting(){
        $getThemeData = null;
        return view('admin.new-appearance-setting',compact('getThemeData'));
    }
    public function ThemeSetting(){
        $facebookURL = DB::table('settings')->where('perimeter','facebook_url')->first();
        $instagramURL = DB::table('settings')->where('perimeter','instagram_url')->first();
        $twitterURL = DB::table('settings')->where('perimeter','twitter_url')->first();
        $linkedInURL = DB::table('settings')->where('perimeter','linkedIn_url')->first();
        $whatsappURL = DB::table('settings')->where('perimeter','whatsapp_url')->first();
        $AppFaviconL = DB::table('settings')->where('perimeter','App_Favicon')->first();
        $WebsiteHeroBanner = DB::table('settings')->where('perimeter','Website_Hero_Banner')->first();
        $AppLogo = DB::table('settings')->where('perimeter','App_Logo')->first();
        $WebNavbarLogo = DB::table('settings')->where('perimeter','Web_Navbar_Logo')->first();
        $FooterLogo = DB::table('settings')->where('perimeter','Footer_Logo')->first();
        $FooterBanner = DB::table('settings')->where('perimeter','Footer_Banner')->first();
        $mail = DB::table('settings')->where('perimeter','company_email')->first();
        $appName = DB::table('settings')->where('perimeter','App_Name')->first();
        return view('admin.theme-setting',compact('facebookURL','instagramURL','twitterURL','linkedInURL','whatsappURL','AppFaviconL','WebsiteHeroBanner','AppLogo','WebNavbarLogo','FooterLogo','FooterBanner','mail','appName'));
    }
    public function updatetheme(Request $request){
        $fields = [
            'favicon' => 'App_Favicon',
            'applogo' => 'App_Logo',
            'herobanner' => 'Website_Hero_Banner',
            // 'navlogo' => 'Web_Navbar_Logo',
            'footerbanner' => 'Footer_Banner',
            'footerlogo' => 'Footer_Logo',
        ];

        $path = public_path('assets/images/');

        foreach ($fields as $field => $perimeter) {
            // return [$perimeter,$field];
            $value = $request->file($field);

            if ($request->hasFile($field)) {
                $setting = DB::table('settings')->where('perimeter', $perimeter)->first();

                if ($setting->value != '' && $setting->value != null) {
                    $file_old = $path . $setting->value;
                    if(file_exists(public_path().'assets/images/'.$setting->value)){
                        unlink($file_old);
                    }
                }

                $filename = $value->getClientOriginalName();
                $time = intval(microtime(true) * 1000);
                $filename = $time.'_'.$filename;
                $value->move($path, $filename);

                DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $filename]);
            }
        }

        $updateFields = [
            'appname' => 'App_Name',
            'facebooklink' => 'facebook_url',
            'emaillink' => 'company_email',
            'twitterlink' => 'twitter_url',
            'instangramlink' => 'instagram_url',
            'whatsapplink' => 'whatsapp_url',
            'linkedin' => 'linkedIn_url',
            'footerText' => 'footer_text',
            'footerDescription' => 'footer_description',
            'base_currency' => 'base_currency',
            'company_address' => 'company_address',
            'phoneNumber' => 'company_phone',
        ];

        foreach ($updateFields as $field => $perimeter) {
            $value = $request->$field;
            DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $value]);
        }
            Session::flash('success', 'Theme Settings Updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Theme Settings Updated Successfully');
    }
    //Region
    public function regions(){
        $region = Region::where('is_deleted', 'N')->orderBy('region_name', 'asc')->get();
        return view('admin.region',compact('region'));
    }
    public function SaveRegion(Request $request){
        $regionId = $request->edit_resion_id;
           $validate = Validator::make($request->all(), [
               'region_name' => 'required|unique:regions,region_name,' . $regionId,
           ]);
           if ($validate->fails()) {
               Session::flash('error', $validate->errors()->first());
               Session::flash('alert-class', 'alert-danger');
               return redirect()->back()->withInput();
           }
       if($regionId && $regionId != null){
           $updateregion = Region::find($regionId);
           $updateregion->region_name = $request->region_name;
           $updateregion->sortname = $request->sortname;
           $updateregion->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
           $updateregion->update();
           if($updateregion){
               Session::flash('success', 'Resion Updated Successfully');
               Session::flash('alert-class', 'alert-success');
               return redirect()->back()->with('Success', 'Region Updated Successfully');
           }
           else{
               Session::flash('error', 'An Error occured! Please try again later');
               Session::flash('alert-class', 'alert-error');
               return redirect()->back()->with('Error', 'An Error occured! Please try again later');
           }
       }
       else{
           $region = new Region();
           $region->region_name = $request->region_name;
           $region->sortname = $request->sortname;
           $region->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
           $region->save();
           if($region){
               Session::flash('success', 'Region Added Successfully');
               Session::flash('alert-class', 'alert-success');
               return redirect()->back()->with('Success', 'Region Added Successfully');
           }
           else{
               Session::flash('error', 'An Error occured! Please try again later');
               Session::flash('alert-class', 'alert-error');
               return redirect()->back()->with('Error', 'An Error occured! Please try again later');
           }
       }
   }
   public function EditRegion(Request $request){
        $getregion = Region::where('is_deleted','N')->where('id',$request->region_id)->first();
        if($getregion){
            return response()->json(['success'=> '1', 'data' => $getregion]);
        }
        else{
            return response()->json(['error'=> '1', 'data' => '']);
        }
    }
    public function DeleteRegion($id){
        $getRegion = Region::find($id);
        $getRegion->is_deleted = 'Y';
        $getRegion->update();
        if($getRegion){
            Session::flash('success', 'Region Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back()->with('Success', 'Region Deleted Successfully');
        }
        else{
            Session::flash('error', 'An Error occured! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occured! Please try again later');
        }
    }
}
