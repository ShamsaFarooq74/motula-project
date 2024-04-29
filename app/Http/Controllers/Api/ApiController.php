<?php


namespace App\Http\Controllers\Api;

use App\RecentSearch;
use App\User;
use Carbon\Carbon;
use App\Http\Models\Ads;
use App\Http\Models\Chat;
use App\Http\Models\Team;
use App\Http\Models\Unit;
use App\Http\Models\Leads;
use App\Http\Models\AboutUs;
use App\Http\Models\Company;
use App\Http\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Models\Currency;
use App\Http\Models\Setting;
use App\Http\Models\Feedback;
use App\Http\Models\Products;
use App\Http\Models\Template;
use App\Http\Models\PhoneCall;
use App\Http\Models\Membership;
use App\Http\Models\UserDevice;
use Illuminate\Validation\Rule;
use App\Http\Models\ChatMessage;
use App\Http\Models\CompanyUser;
use App\Http\Models\SubCategory;
use App\Http\Models\AboutUsImage;
use App\Http\Models\AboutUsVideo;
use App\Http\Models\Notification;
use App\Http\Models\Configuration;
use App\Http\Models\ProductRating;
use App\Http\Models\ProductReview;
use Illuminate\Support\Facades\DB;
use App\Http\Models\ChatAttachment;
use App\Http\Models\Client_company;
use App\Http\Models\PopularProduct;
use App\Http\Models\Tracking_image;
use Illuminate\Support\Facades\Log;
use App\Http\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\FavouriteProduct;
use App\Http\Models\ProductPortfolio;
use App\Http\Models\ProductAttachment;
use Illuminate\Support\Facades\Validator;

class ApiController extends ResponseController
{

    public function update_tracker(Request $request)
    {

        // validation
        $validator = array(
            'tracker_id' => 'required',
            'name' => ['required', 'max:255'],
            'username' => ['required', 'max:255', Rule::unique('users')->ignore($request['tracker_id'])],
            'phone' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($request['tracker_id'])],

        );
        if (isset($request['password']) && !empty($request['password'])) {
            $validator['password'] = ['required', 'min:6', 'confirmed'];
        }
        $validation = Validator::make($request->all(), $validator);
        if ($validation->fails()) {

            return $this->sendResponse(0, 'Error! Some fields are empty!', 'error');
        }

        $data = array('name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'role' => 2);
        if (isset($request['password']) && !empty($request['password'])) {
            $data['password'] = Hash::make($request['password']);
        }


        if (isset($request['file']) && !empty($request['file'])) {

            $user_row = User::where('id', $request['tracker_id'])->first();

            if ($user_row->image) {
                $path = public_path() . '/images/profile-pic/' . $user_row->image;
                if ($path) {
                    File::delete($path);
                }
            }
            $image = $request->file('file');
            $name = date("dmyHis.") . gettimeofday()["usec"] . '_' . 'tracker' . $image->getClientOriginalName();
            $image->move(public_path() . '/images/profile-pic/', $name);

            $data['image'] = $name;
        }

        // update track
        $tracking_id = User::where('id', $request['tracker_id'])->update($data);

        // get track detial
        $tracker = User::where('id', $request['tracker_id'])->first();
        $tracker->user_image = ($tracker->image ? getImageUrl($tracker->image, 'images') : getImageUrl('profile.png', 'images12'));
        $tracker->assets_count = 0;
        if ($tracking_id > 0) {
            return $this->sendResponse(1, 'Record updated successfully!', $tracker);
        } else {
            return $this->sendResponse(0, 'Error! Record not created successfully!', 'error');

        }
    }
    public function update_tracker_profile_image(Request $request)
    {

        // validation
        $validator = array(
            'tracker_id' => 'required',
            'file' => 'required'

        );
        if (isset($request['password']) && !empty($request['password'])) {
            $validator['password'] = ['required', 'min:6', 'confirmed'];
        }
        $validation = Validator::make($request->all(), $validator);
        if ($validation->fails()) {

            return $this->sendResponse(0, 'Error! Some fields are empty!', 'error');
        }

        $data = array();


        if (isset($request['file']) && !empty($request['file'])) {

            $user_row = User::where('id', $request['tracker_id'])->first();

            if ($user_row->image) {
                $path = public_path() . '/images/profile-pic/' . $user_row->image;
                if ($path) {
                    File::delete($path);
                }
            }
            $image = $request->file('file');
            $name = date("dmyHis.") . gettimeofday()["usec"] . '_' . 'tracker' . $image->getClientOriginalName();
            $image->move(public_path() . '/images/profile-pic/', $name);

            $data['image'] = $name;
        }

        // update track
        $tracking_id = User::where('id', $request['tracker_id'])->update($data);

        // get track detial
        $tracker = User::where('id', $request['tracker_id'])->first();
        $trackers = new \stdClass();
        $trackers->user_image = ($tracker->image ? getImageUrl($tracker->image, 'images') : getImageUrl('profile.png', 'images12'));
        if ($tracking_id > 0) {
            return $this->sendResponse(1, 'Record updated successfully!', $trackers);
        } else {
            return $this->sendResponse(0, 'Error! Record not created successfully!', 'error');

        }
    }


    public function company(Request $request)
    {
        if (Company::where('admin_id', $request->user()->id)->exists()) {
            $companies = Company::get();
            for ($j = 0; $j < count($companies); $j++) {
                if ($companies[$j]['logo']) {
                    $companies[$j]['logo'] = getImageUrl($companies[$j]['logo'], 'company-logo');
                }
            }
            return $this->sendResponse(1, 'success', $companies);
        } else {
            return $this->sendResponse(0, 'Error! Record not created successfully!', '');
        }
    }
    public function products(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(0, $validator->errors()->first());
        }
        $users = User::where('is_deleted', 'N')->where('location', 'like', '%' . $request->location . '%')->pluck('id');
        //        $productIds = Products::whereIn('user_id',$users)->pluck('id');
        //        $users = User::where('is_deleted', 'N')->where('location', $request->location)->pluck('id');

        // $favoriteProducts = FavouriteProduct::whereIn('user_id', $users)->pluck('product_id');
        $favoriteProducts = FavouriteProduct::where('user_id',$request->user()->id)->pluck('product_id');
        // dd($favoriteProducts);
        // $favouriteProducts = Products::where('is_deleted', 'N')->whereIn('user_id', $users)->whereIn('id', $favoriteProducts)->where('is_approved','Y')->where('is_active','Y')->get();
        $favouriteProducts = Products::whereIn('id', $favoriteProducts)->whereIn('user_id', $users)->where('is_approved','Y')->where('is_deleted', 'N')->where('is_active','Y')->get();
        foreach($favouriteProducts as $favouriteProduct){
            $favouriteProduct['attachments'] = ProductAttachment::where('products_id', $favouriteProduct->id)->get();
            if($favouriteProduct->price != "" && $favouriteProduct->price !=null){
                if($favouriteProduct->currency_id == 1){
                    if($userCurrency == 1)
                    {
                        $favouriteProduct['price'] = strval($favouriteProduct->price);
                        $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                    }
                    else
                    {
                        $favouriteProduct['currency_id'] = $userCurrency;
                        $favouriteProduct['price']= strval(round(($favouriteProduct->price)/$globalCurrency,2));
                        $favouriteProduct['currency'] = "$";
                    }
                }
                else{
                    if($userCurrency == 2){
                        $favouriteProduct['price'] = strval($favouriteProduct->price);
                        $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                    }
                    else{
                        $favouriteProduct['currency_id'] = $userCurrency;
                    $favouriteProduct['price']= strval(round(($favouriteProduct->price)*$globalCurrency,2));
                    $favouriteProduct['currency'] = "PKR";
                    }
                }
            }
            else{
                $favouriteProduct['price']= null;
            }
            
            if (ProductRating::where('product_id', $favouriteProduct->id)->exists()) {
                $productRating = ProductRating::where('product_id', $favouriteProduct->id)->get();
                $rating = 0;
                for ($k = 0; $k < count($productRating); $k++) {
                    $rating += (int)$productRating[$k]['rating'];
                }
                $count = count($productRating);
                $rat = $rating / $count;
                $favouriteProduct['rating'] = (string)round($rat, 1);


            } else {
                $favouriteProduct['rating'] = "0.0";
            }
            if ($favouriteProduct['attachments']) {
                for ($i = 0; $i < count($favouriteProduct['attachments']); $i++) {
                    $favouriteProduct['attachments'][$i]['image'] = getImageUrl($favouriteProduct['attachments'][$i]['image'], 'product-attachments');
                }
            }
            if (Unit::where('id', $favouriteProduct->unit)->exists()) {

                $favouriteProduct['unit'] = Unit::where('id', $favouriteProduct->unit)->first()['unit'];
            } else {
                $favouriteProduct['unit'] = '';
            }
            if (FavouriteProduct::where('user_id',auth()->user()->id)->where('product_id',$favouriteProduct->id)->exists()){
                $favouriteProduct['favourite_products'] = 'Y';
            }
            else{
                $favouriteProduct['favourite_products'] = 'N';
            }
            $favouriteProduct['sellerName'] = User::where('id', $favouriteProduct->user_id)->first()['username'];
            $favouriteProduct['sellerLocation'] = User::where('id', $favouriteProduct->user_id)->first()['location'];
            $favouriteProduct['sellerEmail'] = User::where('id', $favouriteProduct->user_id)->first()['email'];
            $favouriteProduct['sellerPhone'] = User::where('id', $favouriteProduct->user_id)->first()['phone'];
            $favouriteProduct['categoryId'] = ProductCategory::where('category', $favouriteProduct->category)->first()['id'];
        }
        $popularProducts = Products::where('is_deleted', 'N')->whereIn('user_id', $users)->where('featured', 'Y')->get();
        foreach($popularProducts as $popularProduct){
            // $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
            if($popularProduct->price != "" && $popularProduct->price !=null){
                if($popularProduct->currency_id == 1){
                    if($userCurrency == 1)
                    {
                        $popularProduct['price'] = strval($popularProduct->price);
                        $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
                    }
                    else
                    {
                        $popularProduct['currency_id'] = $userCurrency;
                        $popularProduct['price']= strval(round(($popularProduct->price)/$globalCurrency,2));
                        $popularProduct['currency'] = "$";
                    }
                }
                else{
                    if($userCurrency == 2){
                        $popularProduct['price'] = strval($popularProduct->price);
                        $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
                    }
                    else{
                        $popularProduct['currency_id'] = $userCurrency;
                        $popularProduct['price']= strval(round(($popularProduct->price)*$globalCurrency,2));
                        $popularProduct['currency'] = "PKR";
                    }
                }
            }
            else{
                $popularProduct['price'] = null;
            }
            if (ProductRating::where('product_id', $popularProduct->id)->exists()) {
                $productRating = ProductRating::where('product_id', $popularProduct->id)->get();
                $rating = 0;
                for ($k = 0; $k < count($productRating); $k++) {
                    $rating += (int)$productRating[$k]['rating'];
                }
                $count = count($productRating);
                $rat = $rating / $count;
                $popularProduct['rating'] = (string)round($rat, 1);

            } else {
                $popularProduct['rating'] = "0.0";
            }
            $popularProduct['attachments'] = ProductAttachment::where('products_id', $popularProduct->id)->get();
            if ($popularProduct['attachments']) {
                for ($i = 0; $i < count($popularProduct['attachments']); $i++) {
                    $popularProduct['attachments'][$i]['image'] = getImageUrl($popularProduct['attachments'][$i]['image'], 'product-attachments');
                }
            }
            //            $popularProduct['unit'] = Unit::where('id', $popularProduct->unit)->first()['unit'];
            if (Unit::where('id', $popularProduct->unit)->exists()) {

                $popularProduct['unit'] = Unit::where('id', $popularProduct->unit)->first()['unit'];
            } else {
                $popularProduct['unit'] = '';
            }
            $popularProduct['sellerName'] = User::where('id', $popularProduct->user_id)->first()['username'];
            $popularProduct['sellerLocation'] = User::where('id', $popularProduct->user_id)->first()['location'];
            $popularProduct['sellerEmail'] = User::where('id', $popularProduct->user_id)->first()['email'];
            $popularProduct['sellerPhone'] = User::where('id', $popularProduct->user_id)->first()['phone'];
            $popularProduct['categoryId'] = ProductCategory::where('category', $popularProduct->category)->first()['id'];
        
        }
        $recentSearches =
//            RecentSearch::where('user_id',Auth::user()->id)->groupBy('product_id')->orderBy('id','desc')->limit(10)->get();
            Leads::where('user_id', $request->user()->id)->orderBy('id','DESC')->limit(5)->get();
        if (isset($recentSearches) && count($recentSearches)){

          foreach($recentSearches as $recentSearch){
            //            if(Products::where('id', $recentSearch->product_id)->exists()) {
                $products = Products::Select('id', 'products_name', 'category', 'sub_category', 'price', 'description', 'unit', 'user_id','minimum_order','currency_id')->where('id', $recentSearch->product_id)->first();
            //            $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                if ($products){
                    if($products->price != "" && $products->price !=null){
                        if($products->currency_id == 1){
                            if($userCurrency == 1)
                            {
                                $products['price'] = strval($products->price);
                                $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                            }
                            else
                            {
                                $products['currency_id'] = $userCurrency;
                                $products['price']= strval(round(($products->price)/$globalCurrency,2));
                                $products['currency'] = "$";
                            }
                        }
                        else{
                            if($userCurrency == 2){
                                $products['price'] = strval($products->price);
                                $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                            }
                            else{
                                $products['currency_id'] = $userCurrency;
                                $products['price']= strval(round(($products->price)*$globalCurrency,2));
                                $products['currency'] = "PKR";
                            }
                        }
                    }
                    else{
                        $products['price'] = null;
                    }
                    // $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    if (Unit::where('id', $products['unit'])->exists()) {

                        $products['unit'] = Unit::where('id', $products['unit'])->first()['unit'];
                    } else {
                        $products['unit'] = '';
                    }
                    if ($recentSearch->is_contacted == 'Y') {
                        $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                        $products['sellerLocation'] = User::where('id', $products->user_id)->first()['location'];
                        $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                        $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                        if (Chat::where('seller_id', $products->user_id)->exists()) {
                            $recentSearch['chatId'] = Chat::where('seller_id', $products->user_id)->where('product_id', $products->id)->first()['id'];
                        } else {
                            $recentSearch['chatId'] = 0;
                        }
                    } else {
                        $products['seller'] = null;
                        $recentSearch['chatId'] = 0;
                    }
                    $recentSearch['products'] = $products;
                    $products['attachments'] = ProductAttachment::where('products_id', $products->id)->get();
                    if ($products['attachments']) {
                        for ($i = 0; $i < count($products['attachments']); $i++) {
                            $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                        }
                    }
                    //            }
                    //            else
                    //            {
                    //                $recentSearch['products'] = [];
                }
                //            }
            }
        }
            //        return $recentSearches;

        $ads = Ads::get();
        for ($j = 0; $j < count($ads); $j++) {
            if ($ads[$j]['image']) {
                $ads[$j]['image'] = getImageUrl($ads[$j]['image'], 'ads');
            } else {
                $ads[$j]['image'] = '';
            }
        }
        $data = ['favouriteProducts' => $favouriteProducts, 'popularProducts' => $popularProducts, 'ads' => $ads, 'recentSearches' => $recentSearches];
        return $this->sendResponse(1, 'success', $data);
    }
    public function addProduct(Request $request)
    {
//dd($request->all());
//dd($request->imagePath);
     //   try {
            $validator = Validator::make($request->all(), [

                'sub_category' => 'required',
                'products_name' => 'required',
                'unit' => 'required',
                'category' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError(0, $validator->errors()->first());
            }
            if ($request->status == 'Active') {
                $request->status = 'Y';
            } else if ($request->status == 'Inactive') {
                $request->status = 'N';
            } else {
                $request->status = 'Y';
            }
            if (!$request->product_id) {
//dd('hit');
                if (Products::where('user_id', $request->user()->id)->where('products_name', $request->products_name)->exists()) {
                    return $this->sendError(0, 'Product of this name already exist.Please choose another name.');
                }
                $products = new Products();
                $products->category = $request->category;
                $products->user_id = $request->user()->id;
                if($request->user()->type == 'global'){
                    $products->currency_id = 2;
                }
                else{
                    $products->currency_id = 1;
                }
                $products->sub_category = $request->sub_category;
                $products->products_name = $request->products_name;
                $products->unit = $request->unit;
                if($request->minimum_order)
                {
                    $products->minimum_order = $request->minimum_order;
                }

                if ($request->price){
                    if ((integer)$request->price > 0){
                        $products->price = $request->price;
                    }
                    else{
                        $products->price = null;
                    }
                }
                else{
                    $products->price = null;
                }
                $products->status = $request->status;
                // $products->featured = $request->featured;
                $products->description = $request->description;
                $products->is_active = $request->status;
                $result = $products->save();
                if ($result) {
                    $notification = new Notification();
                    $notification->user_id = $products->user_id;
                    $notification->type_id = $products->id;
                    $notification->schedule_date = \Carbon\Carbon::now();
                    $notification->is_msg_app = 'Y';
                    $notification->notification_type = 'Product';
                    $notification->title = 'Product Created';
                    $notification->description = 'Congratulations! Your Product has been created';
                    $notification->save();
                    $this->send_comm_app_notification();
//                    dd($request->imagePath);
                    if ($request->has('imagePath')) {
//                        dd($request->has('imagePath'));
                        $images = json_decode($request->imagePath);
                        if ($images){
                            for ($i = 0; $i < count($images); $i++) {
                                $imageName = basename($images[$i]);
                                $productImage = new ProductAttachment();
                                $productImage->products_id = $products->id;
                                $productImage->image = $imageName;
                                $productImage->save();
                            }
                            $fromPath = File::allFiles(public_path('/images/temporary-product'));
                            foreach($fromPath as $move){
                                $basename = basename($move);
//                            dd($basename);
                                $toPath = public_path('/images/product-attachments'.'/'.$basename);
                                File::move($move,$toPath);
//                            $files->move(public_path('/images/product-attachments'.'/'.$basename), $productFiles);

                            }
                        }

                    }
                    // if ($request->file('image')) {
                    //     for ($i = 0; $i < count($request->file('image')); $i++) {
                    //         $files = $request->file('image')[$i];
                    //         $productFiles = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();
                    //         $files->move(public_path('/product-attachments'), $productFiles);
                    //         $productImage = new ProductAttachment();
                    //         $productImage->products_id = $products->id;
                    //         $productImage->image = $productFiles;
                    //         $productImage->save();
                    //     }
                    // }
                }
                return ['status' => 1, 'message' => 'Product created successfully!'];
            } else {
//                dd('hit');
                if (!Products:: where('id', $request->product_id)->exists()) {
                    return $this->sendError(0, 'Product not found');
                } else {
//                    dd($request->imagePath);
                    $products = Products:: where('id', $request->product_id)->first();
                    $products->category = $request->category;
                    $products->user_id = $request->user()->id;
                    $products->sub_category = $request->sub_category;
                    $products->products_name = $request->products_name;
                    if($request->user()->type == 'global'){
                        $products->currency_id = 2;
                    }
                    else{
                        $products->currency_id = 1;
                    }
                    $products->unit = $request->unit;
                    if($request->minimum_order)
                    {
                        $products->minimum_order = $request->minimum_order;
                    }
                    if ($request->price){
                        if ((integer)$request->price > 0){
                            $products->price = $request->price;
                        }
                        else{
                            $products->price = null;
                        }
                    }
                    else{
                        $products->price = null;
                    }
                    $products->status = $request->status;
                    $products->is_active = $request->status;
                    $products->featured = $request->featured;
                    $products->description = $request->description;
                    $result = $products->save();
                    if ($result) {
                        // if ($request->has('deleteAttachmentIds')) {
                        //     $attachmentIds = ProductAttachment::where('id', $request->deleteAttachmentIds)->get();
                        //     foreach ($attachmentIds as $key => $value) {
                        //         $image_path = public_path('product-attachments'.'/'.$value->image);
                        //         if(File::exists($image_path)) {
                        //             File::delete($image_path);
                        //         }
                        //     }
                        // }
                        if ($request->has('deleteAttachmentIds')) {
                            $deleteImage = json_decode($request->deleteAttachmentIds);
                            for ($i = 0; $i < count($deleteImage); $i++) {
                                $images = ProductAttachment::where('id', $deleteImage[$i])->first();
                                if ($images->image) {
                                    $path = public_path() . '/images/product-attachments/' . $images->image;
                                    File::delete($path);
                                }
                                ProductAttachment::where('id', $deleteImage[$i])->delete();
                            }
                        }

//                        if ($request->imgPath != null) {
//                            dd('hit');

                            if ($request->has('imagePath')) {
//                                dd('hit');

                                $images = json_decode($request->imagePath);
                                for ($i = 0; $i < count($images); $i++) {
                                    $imageName = basename($images[$i]);
                                    $productImage = new ProductAttachment();
                                    $productImage->products_id = $products->id;
                                    $productImage->image = $imageName;
                                    $productImage->save();
                                }
                                $fromPath = File::allFiles(public_path('/images/temporary-product'));
//                                dd($fromPath);
                                foreach ($fromPath as $move) {
                                    $basename = basename($move);
//                                    dd($basename);
                                    $toPath = public_path('/images/product-attachments/' . '/' . $basename);
//                                    $basename->move(public_path('product-attachments' . '/' . $basename);


//                                    dd($toPath);
                                    File::move($move, $toPath);
                                }
//                            }
                        }
                    }
                    return ['status' => 1, 'message' => 'Product updated successfully!'];
                }
            }

        // } catch (\Exception $exception) {
        //     Log::error($exception->getMessage());
        //     return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        // }
    }
    public function uploadImages(Request $request)
    {
//        dd($request->all());
        $images = array();
        if ($request->hasFile('image')) {
            for ($i = 0; $i < count($request->file('image')); $i++) {
                $files = $request->file('image')[$i];
                $productFiles = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();

                $files->move(public_path('/images/temporary-product'), $productFiles);
                $list = url('/').'/'.$productFiles;
                $images[]=$list;
            }

           /*     $allIimages = File::allFiles(public_path('/images/temporary-product'));

                foreach($allIimages as $key => $item){
                    $list = url('/').'/'.$item;
                    $images[] = $list ;
                }
                */
                return ['status' => 1, 'message'=>'Images Uploaded','images'=>$images];

        }
        else{
            return ['status' => 0, 'message'=>'Please Select Image'];
        }
    }
    public function addCompany(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'company_name' => 'required',
                'contact_number' => 'required',
                'email' => 'required|string|email|unique:companies',
                'address' => 'required',
            ]);
            if ($validator->fails()) {
                return 'Sorry! Might be required fields are empty.';
            }
            if ($files = $request->file('logo')) {
                $logo = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();
                $files->move(public_path('/company-logo'), $logo);
            }
            $company = new Company();
            $company->admin_id = $request->user()->id;
            $company->company_name = $request->company_name;
            $company->contact_number = $request->contact_number;
            $company->email = $request->email;
            $company->address = $request->address;
            $company->logo = isset($logo) && !empty($logo) ? $logo : NULL;
            $result = $company->save();
            if ($result) {
                $companyUser = new CompanyUser();
                $companyUser->company_id = $company->id;
                $companyUser->user_id = $request->user()->id;
                $companyUser->save();
            }
            return ['status' => $result, 'message' => 'Company created successfully!'];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }

    }
    public function addUserRequirements(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'unit_id' => 'required|not_in:0',
                'quantity' => 'required|not_in:0',
                'category_id' => 'required|not_in:0',
            ]);
            if ($validator->fails()) {
                return ['status' => 1, 'error' => $validator->errors()->first()];
            }
            if ($request->product_id) {
                if (Products::where('id', $request->product_id)->exists()) {
                    $productName = Products::where('id', $request->product_id)->first()['products_name'];
                    $sellerInfo = Products::where('products_name', $productName)->get();
                } else {
                    $productName = '';
                    $sellerInfo = [];
                }
            } else {
                $productName = $request->product_name;
                if (Products::where('products_name', $productName)->exists()) {
                    $sellerInfo = Products::where('products_name', $productName)->get();
                } else {
                    $userRequirements = new Leads();
                    $userRequirements->product_name = $productName;
                    $userRequirements->quantity = $request->quantity;
                    $userRequirements->user_id = $request->user()->id;
                    $userRequirements->type = 'not exist';
                    $userRequirements->unit_id = $request->unit_id;
                    $userRequirements->category_id = $request->category_id;
                    if($request->lead_type=='urgent')
                    {
                        $userRequirements->is_urgent = "Y";
                    }else
                    {
                        $userRequirements->is_urgent = "N";
                    }
                    $result = $userRequirements->save();
                    if ($result) {
                        $notification = new Notification();
                        $notification->user_id = $request->user()->id;
                        $notification->type_id = $userRequirements->id;
                        $notification->schedule_date = \Carbon\Carbon::now();
                        $notification->is_msg_app = 'Y';
                        $notification->notification_type = 'Lead';
                        $notification->title = 'Lead Created';
                        $notification->description = 'Congratulations! Your lead has been added';
                        $notification->save();
                        $this->send_comm_app_notification();
                    }
                    return ['status' => 1, 'message' => 'User requirements added successfully!'];
                }
            }
            for ($i = 0; $i < count($sellerInfo); $i++) {
                if (Leads::where('product_name', $productName)->where('user_id', $request->user()->id)->where('seller_id', $sellerInfo[$i]['user_id'])->exists()) {
                    $userRequirements = Leads::where('product_name', $productName)->where('user_id', $request->user()->id)->where('seller_id', $sellerInfo[$i]['user_id'])->first();
                    $userRequirements->product_name = $productName;
                    $userRequirements->user_id = $request->user()->id;
                    $userRequirements->quantity = $request->quantity;
                    $userRequirements->unit_id = $request->unit_id;
                    $userRequirements->category_id = $request->category_id;
                    $userRequirements->product_id = $sellerInfo[$i]['id'];
                    $userRequirements->seller_id = $sellerInfo[$i]['user_id'];
                    if($request->lead_type=='urgent')
                    {
                        $userRequirements->is_urgent = "Y";
                    }else
                    {
                        $userRequirements->is_urgent = "N";
                    }
                    $result = $userRequirements->update();
                    if ($result) {
                        $notification = new Notification();
                        $notification->user_id = $sellerInfo[$i]['user_id'];
                        $notification->type_id = $userRequirements->id;
                        $notification->schedule_date = \Carbon\Carbon::now();
                        $notification->is_msg_app = 'Y';
                        $notification->notification_type = 'Lead';
                        $notification->title = 'Lead Updated';
                        $notification->description = 'New lead Generated';
                        $notification->save();
                        $this->send_comm_app_notification();
                    }
                } else {
                    $userRequirements = new Leads();
                    if($request->lead_type=='urgent')
                    {
                        $userRequirements->is_urgent = "Y";
                    }else
                    {
                        $userRequirements->is_urgent = "N";
                    }
                    $userRequirements->product_name = $productName;
                    $userRequirements->user_id = $request->user()->id;
                    $userRequirements->quantity = $request->quantity;
                    $userRequirements->unit_id = $request->unit_id;
                    $userRequirements->category_id = $request->category_id;
                    $userRequirements->product_id = $sellerInfo[$i]['id'];
                    $userRequirements->seller_id = $sellerInfo[$i]['user_id'];
                    $result = $userRequirements->save();
                    if ($result) {
                        $notification = new Notification();
                        $notification->user_id = $request->user()->id;
                        $notification->type_id = $userRequirements->id;
                        $notification->schedule_date = \Carbon\Carbon::now();
                        $notification->is_msg_app = 'Y';
                        $notification->notification_type = 'Lead';
                        $notification->title = 'Lead Created';
                        $notification->description = 'New lead Generated';
                        $notification->save();
                    }
                    $this->send_comm_app_notification();

                }
            }
            return ['status' => 1, 'message' => 'User requirements added successfully!'];
//            return $userRequirements;

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function getUserRequirements(Request $request)
    {
        try {
            $userRequirements = Leads::where('is_deleted', 'N')->where('is_approved','Y')->where('type', 'exist')->get();
            $userRequirements = $userRequirements->map(function ($userRequirement) {
                $userRequirement['product'] = Products::where('id', $userRequirement->product_id)->first();
//                $userRequirement['product'] = $userRequirement['product']->map(function ($product) {
                $userRequirement['product']['category'] = ProductCategory::where('id', $userRequirement['product']->category_id)->get();
                $userRequirement['product']['sub_category'] = SubCategory::where('id', $userRequirement['product']->sub_category_id)->get();
                $userRequirement['product']['attachments'] = ProductAttachment::where('products_id', $userRequirement['product']->id)->get();
//                    return $product;
//                });
                return $userRequirement;
            });

            return ['status' => 1, 'data' => $userRequirements];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function getCategory()
    {
        try {
            $category = ProductCategory::get();
            if ($category) {
                return $this->sendResponse(1, 'success', $category);
            } else {
                return $this->sendResponse(0, 'error', 'Category not found');
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function subCategory(Request $request)
    {
        try {
            if ($request->categoryId) {
                return ['sub-category' => SubCategory::where('category_id', $request->categoryId)->get()];
            } else {
                return ['message' => 'Please add sub-category id'];
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function addCategory(Request $request)
    {
        try {
            $category = new ProductCategory();
            $category->category = $request->category;
            $category->created_by = $request->user()->id;
            $result = $category->save();
            return ['status' => $result, 'message' => 'Category added successfully!'];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function addSubCategory(Request $request)
    {
        try {
            $subCategory = new SubCategory();
            $subCategory->sub_category = $request->sub_category;
            $subCategory->category_id = $request->category_id;
            $subCategory->created_by = $request->user()->id;
            $subCategory->status = $request->status;
            $result = $subCategory->save();
            return ['status' => $result, 'message' => 'Sub category added successfully!'];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function addUnit(Request $request)
    {
        try {
            $unit = new Unit();
            $unit->status = $request->status;
            $unit->unit = $request->unit;
            $result = $unit->save();
            return ['status' => $result, 'message' => 'Unit added successfully!'];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function getUnit()
    {
        try {
            return ['data' => Unit::all()];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function addMembership(Request $request)
    {
        try {
            $membership = new Membership();
            $membership->sourcing_leads = $request->sourcing_leads;
            $membership->reach = $request->reach;
            $membership->business_enquiries = $request->business_enquiries;
            $membership->sourcing_lead_manager = $request->sourcing_lead_manager;
            $membership->catalogue = $request->catalogue;
            $membership->feature = $request->feature;
            $membership->created_by = $request->user()->id;
            $result = $membership->save();
            return ['status' => $result, 'message' => 'Membership added successfully!'];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function getMembership()
    {
        try {
            return ['data' => Membership::where('is_active', 'Y')->get()];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    // public function addAds(Request $request)
    // {
    //     try {
    //         if ($files = $request->file('image')) {
    //             $adsImage = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();
    //             $files->move(public_path() . '/ads/', $adsImage);
    //         }
    //         $ads = new Ads();
    //         $ads->image = isset($adsImage) && !empty($adsImage) ? $adsImage : NULL;;
    //         $ads->start_date = $request->start_date;
    //         $ads->end_date = $request->end_date;
    //         $ads->slide_time = 3;
    //         $ads->link = $request->link;
    //         $result = $ads->save();
    //         return ['status' => $result, 'message' => 'Ads added successfully!'];
    //     } catch (\Exception $exception) {
    //         Log::error($exception->getMessage());
    //         return json_encode(["status" => 0, "message" => $exception->getMessage()]);
    //     }
    // }
    public function getAds()
    {
        try {
            return ['data' => Ads::all()];

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function searchProduct(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        //        return $request->location;

        try {
            if ($request->has('location')) {
                if ($request->has('is_verified') && $request->is_verified == 'Y') {
                  $users = User::where('is_deleted', 'N')->where('is_verified','Y')->where('location', $request->location)->pluck('id');
                }else {
                    $users = User::where('is_deleted', 'N')->where('location', $request->location)->pluck('id');
                }

                $favouriteProducts = Products::where('is_deleted', 'N')->whereIn('user_id', $users)->where('products_name', 'like', '%' . $request->productName . '%')->where('is_active','Y')->where('is_approved','Y')->get();
                if ($favouriteProducts) {
                    foreach($favouriteProducts as $favouriteProduct){
                        $favouriteProduct['attachments'] = ProductAttachment::where('products_id', $favouriteProduct->id)->get();
                        $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                        if($favouriteProduct->price !=""  && $favouriteProduct->price !=null){
                            if($favouriteProduct->currency_id == 1){
                                if($userCurrency == 1)
                                {
                                    $favouriteProduct['price'] = strval($favouriteProduct->price);
                                    $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                                }
                                else
                                {
                                    $favouriteProduct['currency_id'] = $userCurrency;
                                    $favouriteProduct['price']= strval(round(($favouriteProduct->price)/$globalCurrency,2));
                                    $favouriteProduct['currency'] = "$";
                                }
                            }
                            else{
                                if($userCurrency == 2){
                                    $favouriteProduct['price'] = strval($favouriteProduct->price);
                                    $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                                }
                                else{
                                    $favouriteProduct['currency_id'] = $userCurrency;
                                $favouriteProduct['price']= strval(round(($favouriteProduct->price)*$globalCurrency,2));
                                $favouriteProduct['currency'] = "PKR";
                                }
                            }
                        }
                        else{
                            $favouriteProduct['price'] = null;
                        }
                        if (ProductRating::where('product_id', $favouriteProduct->id)->exists()) {
                            $productRating = ProductRating::where('product_id', $favouriteProduct->id)->get();
                            $rating = 0;
                            for ($k = 0; $k < count($productRating); $k++) {
                                $rating += (int)$productRating[$k]['rating'];
                            }
                            $count = count($productRating);
                            $rat = $rating / $count;
                            $favouriteProduct['rating'] = (string)round($rat, 1);

                        } else {
                            $favouriteProduct['rating'] = "0.0";
                        }
                        if ($favouriteProduct['attachments']) {
                            for ($i = 0; $i < count($favouriteProduct['attachments']); $i++) {
                                $favouriteProduct['attachments'][$i]['image'] = getImageUrl($favouriteProduct['attachments'][$i]['image'], 'product-attachments');
                            }
                        }
                        if (Unit::where('id', $favouriteProduct->unit)->exists()) {

                            $favouriteProduct['unit'] = Unit::where('id', $favouriteProduct->unit)->first()['unit'];
                        }
                        $favouriteProduct['sellerName'] = User::where('id', $favouriteProduct->user_id)->first()['username'];
                        $favouriteProduct['sellerLocation'] = User::where('id', $favouriteProduct->user_id)->first()['location'];
                        $favouriteProduct['sellerEmail'] = User::where('id', $favouriteProduct->user_id)->first()['email'];
                        $favouriteProduct['sellerPhone'] = User::where('id', $favouriteProduct->user_id)->first()['phone'];
                        if (ProductCategory::where('category', $favouriteProduct->category)->exists()) {
                            $cat = ProductCategory::where('category', $favouriteProduct->category)->first()['id'];
                            $favouriteProduct['categoryId'] = $cat;
                        } else {
                            $favouriteProduct['categoryId'] = 0;
                        }
                            //                        $favouriteProduct['categoryId'] = ProductCategory::where('category', $favouriteProduct->category)->first()['id'];
                    }
                }
                            //            $popularProducts = Products::where('popular_products','Y')->whereIn('user_id',$users)->where('products_name',$request->productName)->get();
                            //            $popularProducts = $popularProducts->map(function ($popularProduct) {
                            //                $popularProduct['currency'] = Currency::first()['currency'];
                            //                if(ProductRating::where('product_id',$popularProduct->id)->exists())
                            //                {
                            //                    $productRating = ProductRating::where('product_id',$popularProduct->id)->get();
                            //                    $rating = 0;
                            //                    for($k=0;$k<count($productRating);$k++)
                            //                    {
                            //                        $rating +=  (int)$productRating[$k]['rating'];
                            //                    }
                            //                    $count = count($productRating);
                            //                    $popularProduct['rating'] = $rating / $count;
                            //
                            //                }
                            //                else
                            //                {
                            //                    $popularProduct['rating'] = 0;
                            //                }
                            //                $popularProduct['attachments'] = ProductAttachment::where('products_id', $popularProduct->id)->get();
                            //                if($popularProduct['attachments']) {
                            //                    for ($i = 0; $i < count($popularProduct['attachments']);$i++)
                            //                    {
                            //                        $popularProduct['attachments'][$i]['image'] = getImageUrl($popularProduct['attachments'][$i]['image'],'product-attachments');
                            //                    }
                            //                }
                            //                $popularProduct['unit'] = Unit::where('id', $popularProduct->unit)->first()['unit'];
                            //                $popularProduct['sellerName'] = User::where('id', $popularProduct->user_id)->first()['username'];
                            //                $popularProduct['sellerLocation'] = User::where('id', $popularProduct->user_id)->first()['location'];
                            //                $popularProduct['sellerEmail'] = User::where('id', $popularProduct->user_id)->first()['email'];
                            //                $popularProduct['sellerPhone'] = User::where('id', $popularProduct->user_id)->first()['phone'];
                            //                return $popularProduct;
                            //            });
                            //            $recentSearches = Leads::get();
                            //            $recentSearches = $recentSearches->map(function ($recentSearch) {
                            //                $products = Products::Select('id', 'products_name', 'category', 'sub_category', 'price', 'description', 'unit', 'user_id')->where('id', $recentSearch->product_id)->first();
                            //                $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                            //                if ($recentSearch->is_contacted == 'Y') {
                            //                    $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                            //                    $products['sellerLocation'] = User::where('id', $products->user_id)->first()['location'];
                            //                    $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                            //                    $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                            //                } else {
                            //                    $products['seller'] = null;
                            //                }
                            //                $recentSearch['products'] = $products;
                            //                $products['attachments'] = ProductAttachment::where('products_id', $products->id)->get();
                            //                if ($products['attachments']) {
                            //                    for ($i = 0; $i < count($products['attachments']); $i++) {
                            //                        $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                            //                    }
                            //                }
                            //                return $recentSearch;
                            //            });
                            //            $ads = Ads::get();
                            //            for($j=0;$j<count($ads);$j++)
                            //            {
                            //                if($ads[$j]['image'])
                            //                {
                            //                    $ads[$j]['image'] = getImageUrl($ads[$j]['image'],'ads');
                            //                }
                            //            }

                         //            $data = ['products' => $favouriteProducts,'popularProducts' => $popularProducts,'ads' => $ads,'recentSearches' => $recentSearches];
            } else {
                $favouriteProducts = Products::where('is_deleted', 'N')->where('products_name', 'like', '%' . $request->productName . '%')->where('is_active','Y')->where('is_approved','Y')->get();
                            //                if($favouriteProducts) {
                foreach($favouriteProducts as $favouriteProduct)
                    $favouriteProduct['attachments'] = ProductAttachment::where('products_id', $favouriteProduct->id)->get();
                    $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                    if($favouriteProduct->price != "" && $favouriteProduct->price !=null){
                        if($favouriteProduct->currency_id == 1){
                            if($userCurrency == 1)
                            {
                                $favouriteProduct['price'] = strval($favouriteProduct->price);
                                $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                            }
                            else
                            {
                                $favouriteProduct['currency_id'] = $userCurrency;
                                $favouriteProduct['price']=strval(round(($favouriteProduct->price)/$globalCurrency,2));
                                $favouriteProduct['currency'] = "$";
                            }
                        }
                        else{
                            if($userCurrency == 2){
                                $favouriteProduct['price'] = strval($favouriteProduct->price);
                                $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                            }
                            else{
                                $favouriteProduct['currency_id'] = $userCurrency;
                            $favouriteProduct['price']= strval(round(($favouriteProduct->price)*$globalCurrency,2));
                            $favouriteProduct['currency'] = "PKR";
                            }
                        }
                    }
                    else{
                        $favouriteProduct['price'] = null;
                    }
                    if (ProductRating::where('product_id', $favouriteProduct->id)->exists()) {
                        $productRating = ProductRating::where('product_id', $favouriteProduct->id)->get();
                        $rating = 0;
                        for ($k = 0; $k < count($productRating); $k++) {
                            $rating += (int)$productRating[$k]['rating'];
                        }
                        $count = count($productRating);
                        $rat = $rating / $count;
                        $favouriteProduct['rating'] = (string)round($rat, 1);

                    } else {
                        $favouriteProduct['rating'] = "0.0";
                    }
                    if ($favouriteProduct['attachments']) {
                        for ($i = 0; $i < count($favouriteProduct['attachments']); $i++) {
                            $favouriteProduct['attachments'][$i]['image'] = getImageUrl($favouriteProduct['attachments'][$i]['image'], 'product-attachments');
                        }
                    }

						if (Unit::where('id', $favouriteProduct->unit)->exists()) {

                            $favouriteProduct['unit'] = Unit::where('id', $favouriteProduct->unit)->first()['unit'];
                        } else {
                            $favouriteProduct['unit'] = '';
                        }


                    $favouriteProduct['sellerName'] = User::where('id', $favouriteProduct->user_id)->first()['username'];
                    $favouriteProduct['sellerLocation'] = User::where('id', $favouriteProduct->user_id)->first()['location'];
                    $favouriteProduct['sellerEmail'] = User::where('id', $favouriteProduct->user_id)->first()['email'];
                    $favouriteProduct['sellerPhone'] = User::where('id', $favouriteProduct->user_id)->first()['phone'];
                    if (ProductCategory::where('category', $favouriteProduct->category)->exists()) {
                        $cat = ProductCategory::where('category', $favouriteProduct->category)->first()['id'];
                        $favouriteProduct['categoryId'] = $cat;
                    } else {
                        $favouriteProduct['categoryId'] = 0;
                    }
                                //                    $favouriteProduct['categoryId'] = ProductCategory::where('category', $favouriteProduct->category)->first()['id'];
                
            }
                                //            }
            $data = ['products' => $favouriteProducts];
            return $this->sendResponse(1, 'success', $data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function searchCategory(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $category = ProductCategory::where('category', 'like', '%' . $request->category . '%')->get();
        if ($category) {
           foreach($category as $cat){
                $products = Products::where('category', $cat->category)->get();
                if ($products) {
                    foreach($products as $product) {
                        $product['attachments'] = ProductAttachment::where('products_id', $product->id)->get();
                        if($product->price !="" && $product->price !=null){
                            if($product->currency_id == 1){
                                if($userCurrency == 1)
                                {
                                    $product['price'] = strval($product->price);
                                    $product['currency'] = Currency::where('id',$product->currency_id)->first()['currency'];
                                }
                                else
                                {

                                    $product['currency_id'] = $userCurrency;
                                    $product['price']= strval(round(($product->price)/$globalCurrency,2));
                                    $product['currency'] = "$";
                                }
                            }
                            else{
                                if($userCurrency == 2){
                                    $product['price'] = strval($product->price);
                                    $product['currency'] = Currency::where('id',$product->currency_id)->first()['currency'];
                                }
                                else{
                                    $product['currency_id'] = $userCurrency;
                                $product['price']= strval(round(($product->price)*$globalCurrency,2));
                                $product['currency'] = "PKR";
                                }
                            }
                        }
                        else{
                            $product['price'] = null;
                        }
                        if (ProductRating::where('product_id', $product->id)->exists()) {
                            $productRating = ProductRating::where('product_id', $product->id)->get();
                            $rating = 0;
                            for ($k = 0; $k < count($productRating); $k++) {
                                $rating += (int)$productRating[$k]['rating'];
                            }
                            $count = count($productRating);
                            $rat = $rating / $count;
                            $product['rating'] = (string)round($rat, 1);

                        } else {
                            $product['rating'] = "0.0";
                        }
                        if ($product['attachments']) {
                            for ($i = 0; $i < count($product['attachments']); $i++) {
                                $product['attachments'][$i]['image'] = getImageUrl($product['attachments'][$i]['image'], 'product-attachments');
                            }
                        }
                        if(Unit::where('id', $product->unit)->exists()){
                            $product['unit'] = Unit::where('id', $product->unit)->first()['unit'];
                        }

                        $product['sellerName'] = User::where('id', $product->user_id)->first()['username'];
                        $product['sellerLocation'] = User::where('id', $product->user_id)->first()['location'];
                        $product['sellerEmail'] = User::where('id', $product->user_id)->first()['email'];
                        $product['sellerPhone'] = User::where('id', $product->user_id)->first()['phone'];
                        $product['categoryId'] = ProductCategory::where('category', $product->category)->first()['id'];
                        // return $product;
                    }
                    $cat['product'] = $products;

                }
                // return $cat;
            }
            return $this->sendResponse(1, 'success', $category);
        } else {
            return $this->sendResponse(1, 'success', 'no data found');
        }


    }
    public function categorySearch(Request $request)
    {
        $category = ProductCategory::where('category', 'like', '%' . $request->category . '%')->get();
        if ($category) {
            return $this->sendResponse(1, 'success', $category);
        } else {
            return $this->sendResponse(1, 'success', 'No category found');
        }
    }
    public function favouriteProduct(Request $request)
    {
        if ($request->is_favourite == 'Y') {
            $product['product_id'] = $request->product_id;
            $product['user_id'] = $request->user()->id;
            $favouriteProducts = FavouriteProduct::where('user_id', $request->user()->id)->where('product_id', $request->product_id)->first();
            // $markProductAsFavourite = Products::where('id', $request->product_id)->first();
            // $markProductAsFavourite->favourite_products = 'Y';
            // $markProductAsFavourite->save();
            if ($favouriteProducts) {
                $result = $favouriteProducts->fill($product)->save();
            } else {
                $result = FavouriteProduct::create($product);

            }
            if ($result) {
                return $this->sendResponse(1, 'success', 'Action performed successfully!');
            }
        } else {
            // $markProductAsFavourite = Products::where('id', $request->product_id)->first();
            // $markProductAsFavourite->favourite_products = 'N';
            // $markProductAsFavourite->save();
            if (FavouriteProduct::where('product_id', $request->product_id)->exists()) {
                $fvtProducts = FavouriteProduct::where('product_id', $request->product_id)->delete();
                return $this->sendResponse(1, 'success', 'Action performed successfully!');
            }
        }
    //    $product = Products::where('user_id', $request->user()->id)->where('id', $request->product_id)->first();
    //    if ($product) {
    //        if ($product->favourite_products == 'N') {
    //            $product->favourite_products = $request->is_favourite;
    //            $result = $product->update();
    //            if ($result) {
    //                return $this->sendResponse(1, 'success', 'Action performed successfully!');
    //            }
    //        } else {
    //            return $this->sendResponse(0, 'success', 'Product status is already favourite!');
    //        }

    //    } else {
    //        return $this->sendResponse(0, 'error', 'You have no right to make this product favourite!');
    //    }
    }
    public function getfavouriteProduct(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $productIds = FavouriteProduct::where('user_id',$request->user()->id)->pluck('product_id');
        
        $favouriteProducts = Products::whereIn('id', $productIds)->where('is_approved','Y')->where('is_deleted', 'N')->where('is_active','Y')->get();
        if ($favouriteProducts) {
            foreach($favouriteProducts as $favouriteProduct){
                // return $favouriteProduct;
                $favouriteProduct['minimum_order'] = $favouriteProduct->minimum_order;
                $favouriteProduct['attachments'] = ProductAttachment::where('products_id', $favouriteProduct->id)->get();
                if($favouriteProduct->price!="" && $favouriteProduct->price != null){
                    if($favouriteProduct->currency_id == 1){
                        if($userCurrency == 1)
                        {
                            $favouriteProduct['price'] = strval($favouriteProduct->price);
                            $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                        }
                        else
                        {
                            $favouriteProduct['currency_id'] = $userCurrency;
                            $favouriteProduct['price']= strval(round(($favouriteProduct->price)/$globalCurrency,2));
                            $favouriteProduct['currency'] = "$";
                        }
                    }
                    else{
                        if($userCurrency == 2){
                            $favouriteProduct['price'] = strval($favouriteProduct->price);
                            $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                        }
                        else{
                            $favouriteProduct['currency_id'] = $userCurrency;
                        $favouriteProduct['price']= strval(round(($favouriteProduct->price)*$globalCurrency,2));
                        $favouriteProduct['currency'] = "PKR";
                        }
                    }
                }
                else{
                    $favouriteProduct['price'] = null;
                }
                // $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                if (ProductRating::where('product_id', $favouriteProduct->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $favouriteProduct->id)->get();
                    $rating = 0;
                    for ($k = 0; $k < count($productRating); $k++) {
                        $rating += (int)$productRating[$k]['rating'];
                    }
                    $count = count($productRating);
                    $rat = $rating / $count;
                    $favouriteProduct['rating'] = (string)round($rat, 1);

                } else {
                    $favouriteProduct['rating'] = "0.0";
                }
                if ($favouriteProduct['attachments']) {
                    for ($i = 0; $i < count($favouriteProduct['attachments']); $i++) {
                        $favouriteProduct['attachments'][$i]['image'] = getImageUrl($favouriteProduct['attachments'][$i]['image'], 'product-attachments');
                    }
                }
                if (Unit::where('id', $favouriteProduct->unit)->exists()) {
                    $favouriteProduct['unit'] = Unit::where('id', $favouriteProduct->unit)->first()['unit'];
                }
                $favouriteProduct['sellerName'] = User::where('id', $favouriteProduct->user_id)->first()['username'];
                $favouriteProduct['sellerLocation'] = User::where('id', $favouriteProduct->user_id)->first()['location'];
                $favouriteProduct['sellerEmail'] = User::where('id', $favouriteProduct->user_id)->first()['email'];
                $favouriteProduct['sellerPhone'] = User::where('id', $favouriteProduct->user_id)->first()['phone'];
                $favouriteProduct['seller_type'] = User::where('id', $favouriteProduct->user_id)->first()['seller_type'];
                $favouriteProduct['categoryId'] = ProductCategory::where('category', $favouriteProduct->category)->first()['id'];
            
            }
            return $this->sendResponse(1, 'success', $favouriteProducts);
        } else {
            return $this->sendResponse(1, 'success', 'No favourite product found against this user');
        }
    }
    public function getImportedProducts(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $users = User::where('type', 'global')->pluck('id');
        if ($users) {
            $product = Products::whereIn('user_id', $users)->where('is_active', 'Y')->where('is_approved', 'Y')->where('is_deleted', 'N')->get();
            if ($product) {
                foreach($product as $favouriteProduct){
                    $favouriteProduct['attachments'] = ProductAttachment::where('products_id', $favouriteProduct->id)->get();
                    // $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                    if($favouriteProduct->price !="" && $favouriteProduct->price !=null){
                    if($favouriteProduct->currency_id == 1){
                        if($userCurrency == 1)
                        {
                            $favouriteProduct['price'] = strval($favouriteProduct->price);
                            $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                        }
                        else
                        {
                            $favouriteProduct['currency_id'] = $userCurrency;
                            $favouriteProduct['price']= strval(round(($favouriteProduct->price)/$globalCurrency,2));
                            $favouriteProduct['currency'] = "$";
                        }
                    }
                    else{
                        if($userCurrency == 2){
                            $favouriteProduct['price'] = strval($favouriteProduct->price);
                            $favouriteProduct['currency'] = Currency::where('id',$favouriteProduct->currency_id)->first()['currency'];
                        }
                        else{
                            $favouriteProduct['currency_id'] = $userCurrency;
                        $favouriteProduct['price']= strval(round(($favouriteProduct->price)*$globalCurrency,2));
                        $favouriteProduct['currency'] = "PKR";
                        }
                    }
                }
                else{
                    $favouriteProduct['price'] = null;
                }
                    if (ProductRating::where('product_id', $favouriteProduct->id)->exists()) {
                        $productRating = ProductRating::where('product_id', $favouriteProduct->id)->get();
                        $rating = 0;
                        for ($k = 0; $k < count($productRating); $k++) {
                            $rating += (int)$productRating[$k]['rating'];
                        }
                        $count = count($productRating);
                        $rat = $rating / $count;
                        $favouriteProduct['rating'] = (string)round($rat, 1);

                    } else {
                        $favouriteProduct['rating'] = "0.0";
                    }
                    if ($favouriteProduct['attachments']) {
                        for ($i = 0; $i < count($favouriteProduct['attachments']); $i++) {
                            $favouriteProduct['attachments'][$i]['image'] = getImageUrl($favouriteProduct['attachments'][$i]['image'], 'product-attachments');
                        }
                    }
                    if (Unit::where('id', $favouriteProduct->unit)->exists()) {
                        $favouriteProduct['unit'] = Unit::where('id', $favouriteProduct->unit)->first()['unit'];
                    }
                    $favouriteProduct['sellerName'] = User::where('id', $favouriteProduct->user_id)->first()['username'];
                    $favouriteProduct['sellerLocation'] = User::where('id', $favouriteProduct->user_id)->first()['location'];
                    $favouriteProduct['sellerEmail'] = User::where('id', $favouriteProduct->user_id)->first()['email'];
                    $favouriteProduct['sellerPhone'] = User::where('id', $favouriteProduct->user_id)->first()['phone'];
                    $favouriteProduct['categoryId'] = ProductCategory::where('category', $favouriteProduct->category)->first()['id'];
                    // return $favouriteProduct;
                }
                return $this->sendResponse(1, 'success', $product);
            } else {
                return $this->sendResponse(1, 'success', 'No product found');
            }
        } else {
            return $this->sendResponse(1, 'success', 'No global user found');
        }

    }
    public function getProduct(Request $request)
    {
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $userCurrency = Auth::user()->currency_id;
        if (Auth::user()){
            RecentSearch::create([
                'user_id'=>Auth::user()->id,
                'product_id'=>$request->product_id
            ]);
        }
        $userType = User::where('id', $request->user()->id)->first()['role'];
       if ($userType == '4') {
           $sellerId = Products::where('id', $request->product_id)->first()['user_id'];
           $productReview = new ProductReview();
           $productReview->seller_id = $sellerId;
           $productReview->user_id = $request->user()->id;
           $productReview->product_id = $request->product_id;
           $productReview->save();

       }
        if (Products::where('id', $request->product_id)->exists()) {
            $product = Products::where('id', $request->product_id)->first();
            if ($product) {
                $product['attachments'] = ProductAttachment::where('products_id', $product->id)->get();
                if($product->price != "" && $product->price !=null){
                    if($product->currency_id == 1){
                        if($userCurrency == 1)
                        {
                            $product['price'] = strval($product->price);
                            $product['currency'] = Currency::where('id',$product->currency_id)->first()['currency'];
                        }
                        else
                        {
                            $product['currency_id'] = $userCurrency;
                            $product['price']= strval(round(($product->price)/$globalCurrency,2));
                            $product['currency'] = "$";
                        }
                    }
                    else{
                        if($userCurrency == 2){
                            $product['price'] = strval($product->price);
                            $product['currency'] = Currency::where('id',$product->currency_id)->first()['currency'];
                        }
                        else{
                            $product['currency_id'] = $userCurrency;
                        $product['price']= strval(round(($product->price)*$globalCurrency,2));
                        $product['currency'] = "PKR";
                        }
                    }
                }
                else{
                    $product['price'] = null;
                }
                // $product['currency'] = Currency::where('id',$product->currency_id)->first()['currency'];
                if (ProductRating::where('product_id', $product->id)->exists()) {
//                    $product['reviews'] = ProductRating::where('product_id', $product->id)->get();
                    $productRating = ProductRating::where('product_id', $product->id)->get();
                    $product['reviews'] = $productRating;
                    $rating = 0;
                    for ($k = 0; $k < count($productRating); $k++) {
                        $rating += (int)$productRating[$k]['rating'];
                        $productRating[$k]['userName'] = User::where('id', $productRating[$k]['user_id'])->first()['username'];
                        $productRating[$k]['image'] = User::where('id', $productRating[$k]['user_id'])->first()['image'];
                        if (empty($productRating[$k]['image'])) {
                            $productRating[$k]['image'] = 'xyz';
                        }
                        $file = public_path() . '/images/profile-pic/' . $productRating[$k]['image'];
                        if (!empty($productRating[$k]['image']) && file_exists($file)) {
                            $productRating[$k]['image'] = getImageUrl($productRating[$k]['image'], 'images');
                        } else {
                            $productRating[$k]['image'] = getImageUrl('profile.png', 'images12');
                        }
                    }
                    $count = count($productRating);
                    $rat = $rating / $count;
                    $product['rating'] = (string)round($rat, 1);

                } else {
                    $product['reviews'] = [];
                    $product['rating'] = "0.0";
                }
                if ($product['attachments']) {
                    for ($i = 0; $i < count($product['attachments']); $i++) {
                        $product['attachments'][$i]['image'] = getImageUrl($product['attachments'][$i]['image'], 'product-attachments');
                    }
                }

                if (FavouriteProduct::where('user_id',Auth::user()->id)->where('product_id',$product->id)->exists()){
                    $product['favourite_products'] = 'Y';

                }else{
                    $product['favourite_products'] = 'N';
                }
				$product['unit'] = Unit::where('id', $product->unit)->exists() ? Unit::where('id', $product->unit)->first()['unit'] : 'N/A';
                $product['sellerName'] = User::where('id', $product->user_id)->first()['username'];
                $product['sellerLocation'] = User::where('id', $product->user_id)->first()['location'];
                $product['sellerEmail'] = User::where('id', $product->user_id)->first()['email'];
                $product['sellerPhone'] = User::where('id', $product->user_id)->first()['phone'];
                $product['seller_type'] = User::where('id', $product->user_id)->first()['seller_type'];
                if (ProductCategory::where('category', $product->category)->exists()) {
                    $product['categoryId'] = ProductCategory::where('category', $product->category)->first()['id'];
                }
                if (Chat::where('buyer_id', $request->user()->id)->where('product_id', $product->id)->exists()) {
                    $product['chatId'] = Chat::where('buyer_id', $request->user()->id)->where('product_id', $product->id)->first()['id'];
                    // return $product['chatId'];
                } else {
                    $product['chatId'] = 0;
                }
                return $this->sendResponse(1, 'success', $product);
            }
        } else {
            return $this->sendResponse(1, 'success', []);
        }
    }
    public function sellerDetail(Request $request)
    {
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $userCurrency = Auth::user()->currency_id;
        $userInfo = User::where('id',$request->sellerId)->where('is_deleted','N')->where('is_active','Y')->first()['id'];
        if ($userInfo){
            if (Products::where('user_id', $userInfo)->exists()) {
                $product = Products::where('user_id', $userInfo)->get();
                foreach($product as $products) {
                    $products['attachments'] = ProductAttachment::where('products_id', $products->id)->get();
                    // $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    if($products->price != "" && $products->price !=null){
                        if($products['currency_id'] == 1){
                            if($userCurrency == 1)
                            {
                                $products['price'] = strval($products->price);
                                $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                            }
                            else
                            {
                                $products['currency_id'] = $userCurrency;
                                $products['price']= strval(round(($products->price)/$globalCurrency,2));
                                $products['currency'] = "$";
                            }
                        }
                        else{
                            if($userCurrency == 2){
                                $products['price'] = strval($products->price);
                                $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                            }
                            else{
                                $products['currency_id'] = $userCurrency;
                                $products['price']= strval(round(($products->price)*$globalCurrency,2));
                                $products['currency'] = "PKR";
                            }
                        }
                    }
                    else{
                        $products['price'] = null;
                    }
                    if (ProductRating::where('product_id', $products->id)->exists()) {
                        $productRating = ProductRating::where('product_id', $products->id)->get();
                        $rating = 0;
                        for ($k = 0; $k < count($productRating); $k++) {
                            $rating += (double)$productRating[$k]['rating'];
                        }
                        $count = count($productRating);
                        $ratingAvg = $rating / $count;
                        $products['rating'] = (string)round($ratingAvg, 1);
                        //                    if(strlen($products['rating']) == 1)
                        //                    {
                        //                        $products['rating'] = $products['rating'] .'.0';
                        //                    }

                    } else {
                        $products['rating'] = "0.0";
                    }
                    if ($products['attachments']) {
                        for ($i = 0; $i < count($products['attachments']); $i++) {
                            $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                        }
                    }
                    if (Unit::where('id', $products->unit)->exists()) {

                        $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                    }
                    $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                    $products['sellerLocation'] = User::where('id', $products->user_id)->first()['location'];
                    $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                    $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                    $products['seller_type'] = User::where('id', $products->user_id)->first()['seller_type'];
                    $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                    // return $products;
                }
            } else {
                $product = [];
            }
            $category = Products::Select('category')->where('user_id', $userInfo)->get();
            foreach($category as $products){
                $products['id'] = ProductCategory::where('category', $products->category)->first()['id'];
                $products['products'] = Products::where('category', $products->category)->get();
                foreach($products['products'] as $products){
                    $products['attachments'] = ProductAttachment::where('products_id', $products->id)->get();
                    $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    if($products->price !="" && $products->price != null){
                    if($products['currency_id'] == 1){
                        if($userCurrency == 1)
                        {
                            $products['price'] = strval($products->price);
                            $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                        }
                        else
                        {
                            $products['currency_id'] = $userCurrency;
                            $products['price']=strval(round(($products->price)/$globalCurrency,2));
                            $products['currency'] = "$";
                        }
                    }
                    else{
                        if($userCurrency == 2){
                            $products['price'] = strval($products->price);
                            $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                        }
                        else{
                            $products['currency_id'] = $userCurrency;
                            $products['price']= strval(round(($products->price)*$globalCurrency,2));
                            $products['currency'] = "PKR";
                        }
                    }
                }
                else{
                    $products['price'] = null;
                }
                    if (ProductRating::where('product_id', $products->id)->exists()) {
                        $productRating = ProductRating::where('product_id', $products->id)->avg('rating');
                        //                        $array = json_decode(json_encode($productRating), true);
                        //                        $sum = array_sum($array);
                        //                        $count = count($array);
                        //                        $ratingAvg = $sum / $count;
                        //                        $productRating = ProductRating::where('product_id', $products->id)->get();
                        //                        $rating = 0;
                        //                        for ($k = 0; $k < count($productRating); $k++) {
                        //                            $rating += (int)$productRating[$k]['rating'];
                        //                        }
                        //                        $count = count($productRating);
                        //                        $ratingAvg = $rating / $count;
                        $products['rating'] = (string)round($productRating, 1);

                    } else {
                        $products['rating'] = "0.0";
                    }
                    if ($products['attachments']) {
                        for ($i = 0; $i < count($products['attachments']); $i++) {
                            $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                        }
                    }
                    if (Unit::where('id', $products->unit)->exists()) {

                        $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                    }
                    if (User::where('id',$products->user_id)->exists()) {
                        $getUserInfo = User::where('id',$products->user_id)->first();
                        $products['sellerName'] = $getUserInfo['username'];
                        $products['sellerLocation'] = $getUserInfo['location'];
                        $products['sellerEmail'] = $getUserInfo['email'];
                        $products['sellerPhone'] = $getUserInfo['phone'];
                        $products['seller_type'] = $getUserInfo['seller_type'];
                    }
                    $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                    // return $products;
                }
                // return $products;
            }

            $aboutUs = User::Select('phone', 'email', 'location','seller_type')->where('id', $userInfo)->first();
            $aboutUs['productPortfolio'] = ProductPortfolio::first()['portfolio'];
            $aboutUs['team'] = Team::first()['team'];
            if (ProductRating::where('user_id', $userInfo)->exists()) {
                $rating = ProductRating::where('user_id', $userInfo)->get();
                $rating = $rating->map(function ($products) {
                    $products['name'] = User::where('id', $products->user_id)->first()['username'];
                    $sellerImage = User::where('id', $products->user_id)->first()['image'];
                    $products['attachments'] = getImageUrl($sellerImage, 'images');
                    //                    if ($products['attachments']) {
                    //                        for ($i = 0; $i < count($products['attachments']); $i++) {
                    //                            $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                    //                        }
                    //                    }
                    return $products;
                });
            } else {
                $rating = [];
            }

            $sellersInformation = User::Select('id','location','username', 'email', 'phone', 'image','seller_type')->where('id', $userInfo)->first();
            if(empty($sellerDetails['image']))
            {
                $sellerDetails['image'] = 'xyz';
            }
            $file = public_path() . '/images/profile-pic/' . $sellerDetails['image'];
            if ($sellersInformation->image && file_exists($file)) {
                $sellersInformation->image = getImageUrl($sellersInformation->image, 'images');
            }
            else
            {
                $sellersInformation->image = getImageUrl('profile.png', 'images123');
            }
            if (ProductRating::where('user_id', $userInfo)->exists()) {
                $productRating = ProductRating::where('user_id', $userInfo)->pluck('rating');
                $array = json_decode(json_encode($productRating), true);
                $sum = array_sum($array);
                $count = count($array);
                $ratingAvg = $sum / $count;
                $rat = (string)round($ratingAvg, 1);
                $sellersInformation['rating'] = $rat;
                $sellersInformation['ratingCount'] = $count;

            } else {
                $sellersInformation['rating'] = "0.0";
                $sellersInformation['ratingCount'] = 0;
            }
            $aboutUsImages = AboutUsImage::where('seller_id', $userInfo)->get();
            for ($i = 0; $i < count($aboutUsImages); $i++) {
                $aboutUsImages[$i]['image'] = getImageUrl($aboutUsImages[$i]['image'], 'images');
            }
            $videos = AboutUsVideo::where('seller_id', $userInfo)->get();
            for ($i = 0; $i < count($videos); $i++) {
                $videos[$i]['video'] = getImageUrl($videos[$i]['video'], 'images');
            }
            $aboutUs = AboutUs::where('seller_id', $userInfo)->get();
            $aboutUsSellerDetail = AboutUs::where('seller_id', $userInfo)->first();
            if($aboutUsSellerDetail)
            {
                $aboutUsTeam = $aboutUsSellerDetail['team'];
                $aboutUsPortFolio = $aboutUsSellerDetail['port_folio'];
            }
            else
            {
                $aboutUsTeam = '';
                $aboutUsPortFolio = '';
            }
            $aboutUsData =
                [
                    'team' => $aboutUsTeam,
                    'productPortFolio' => $aboutUsPortFolio,
                    'image' => $sellersInformation['image'],
                    'email' => $sellersInformation['email'],
                    'phone' => $sellersInformation['phone'],
                    'sellerID' => $sellersInformation['id'],
                    'location' => $sellersInformation['location'],
                    'rating' => $sellersInformation['rating']

                ];
            $data =
                [
                    'topProducts' => $product,
                    'category' => $category,
                    'aboutUsDescription' => $aboutUs,
                    'aboutUsImages' => $aboutUsImages,
                    'aboutUsVideos' => $videos,
                    'ratingDetails' => $rating,
                    'sellersInformation' => $sellersInformation,
                    'aboutUs' => $aboutUsData
                ];

            return $this->sendResponse(1, 'success', $data);
        }
        else{
            return $this->sendResponse(1, 'no User found', null);
        }

    }
    public function buyerRequirementDetails()
    {
        try {
            
            $userRequirements = Leads::where('is_deleted','N')->where('user_id',auth()->user()->id)->orderBy('id','DESC')->get();

            if ($userRequirements) {
                $userRequirements = $userRequirements->map(function ($requirement) {
                    if ($requirement->product_name) {
                        $requirement['product'] = $requirement->product_name;
                    }
                    if (ProductCategory::where('id', $requirement->category_id)->exists()) {
                        $requirement['productCategory'] = ProductCategory::where('id', $requirement->category_id)->first()['category'];
                    }
                    if (Unit::where('id', $requirement->unit_id)->exists()) {
                        $requirement['unit'] = Unit::where('id', $requirement->unit_id)->first()['unit'];
                    }
                    return $requirement;

                });
                return $this->sendResponse(1, 'success', $userRequirements);
            } else {
                return $this->sendResponse(1, 'success', 'No data found');
            }
        }catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return json_encode(["status" => 0, "message" => $exception->getMessage()]);
        }
    }
    public function buyerRequirementHistory(Request $request)
    {
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $userCurrency = Auth::user()->currency_id;
        $userRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id')->where('id', $request->requirement_id)->first();
       
        if ($userRequirements) {
            if(Products::where('id', $userRequirements->product_id)->exists()){
                $userRequirements['product'] = Products::where('id', $userRequirements->product_id)->first()['products_name'];
                $userRequirements['productCategory'] = ProductCategory::where('id', $userRequirements->category_id)->first()['category'];
                if(Unit::where('id', $userRequirements->unit_id)->exists()){
                    $userRequirements['unit'] = Unit::where('id', $userRequirements->unit_id)->first()['unit'];
                }
                //           $availableServices = Leads::Select('quantity','product_id','category_id','unit_id')->where('id',$request->requirement_id)->first();
                $availableServices['availableProducts'] = Products::where('products_name', $userRequirements['product'])->where('is_approved', 'Y')->where('is_deleted', 'N')->where('is_active', 'Y')->get();
                foreach($availableServices['availableProducts'] as $products) {
                    $sellerImage = User::where('id', $products->user_id)->first()['image'];
                    $products['attachments'] = getImageUrl($sellerImage, 'images');
                    $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    if($products->price !="" && $products->price !=null){
                    if($products['currency_id'] == 1){
                        if($userCurrency == 1)
                        {
                            $products['price'] = strval($products->price);
                            $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                        }
                        else
                        {
                            $products['currency_id'] = $userCurrency;
                            $products['price']=strval(round(($products->price)/$globalCurrency,2));
                            $products['currency'] = "$";
                        }
                    }
                    else{
                        if($userCurrency == 2){
                            $products['price'] = strval($products->price);
                            $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                        }
                        else{
                            $products['currency_id'] = $userCurrency;
                            $products['price']= strval(round(($products->price)*$globalCurrency,2));
                            $products['currency'] = "PKR";
                        }
                    }
                }
                else{
                    $products['price'] = null;
                }
                    if (ProductRating::where('product_id', $products->id)->exists()) {
                        $productRating = ProductRating::where('product_id', $products->id)->get();
                        $rating = 0;
                        for ($k = 0; $k < count($productRating); $k++) {
                            $rating += (int)$productRating[$k]['rating'];
                        }
                        $count = count($productRating);
                        $rat = $rating / $count;
                        $products['rating'] = (string)round($rat, 1);

                    } else {
                        $products['rating'] = "0.0";
                    }
                    //               if ($products['attachments']) {
                    //                   for ($i = 0; $i < count($products['attachments']); $i++) {
                    //                       $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                    //                   }
                    //               }
                    if(Unit::where('id', $products->unit)->exists()){

                        $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                    }
                    $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                    $products['sellerLocation'] = User::where('id', $products->user_id)->first()['location'];
                    $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                    $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                    $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                    // return $products;
                }
            }
            $userRequirements['productCategory'] = ProductCategory::where('id', $userRequirements->category_id)->first()['category'];
            if (Unit::where('id', $userRequirements->unit_id)->exists()) {
                $userRequirements['unit'] = Unit::where('id', $userRequirements->unit_id)->first()['unit'];
            }
            //           $availableServices = Leads::Select('quantity','product_id','category_id','unit_id')->where('id',$request->requirement_id)->first();
            $availableServices['availableProducts'] = Products::where('products_name', $userRequirements['product'])->where('is_approved', 'Y')->where('is_deleted', 'N')->where('is_active', 'Y')->get();
            foreach($availableServices['availableProducts'] as $products){
                $sellerImage = User::where('id', $products->user_id)->first()['image'];
                $products['attachments'] = getImageUrl($sellerImage, 'images');
                // $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                if($products->price !="" && $products->price !=null){
                if($products['currency_id'] == 1){
                    if($userCurrency == 1)
                    {
                        $products['price'] = strval($products->price);
                        $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                    }
                    else
                    {
                        $products['currency_id'] = $userCurrency;
                        $products['price']= strval(round(($products->price)/$globalCurrency,2));
                        $products['currency'] = "$";
                    }
                }
                else{
                    if($userCurrency == 2){
                        $products['price'] = strval($products->price);
                        $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    }
                    else{
                        $products['currency_id'] = $userCurrency;
                        $products['price']= strval(round(($products->price)*$globalCurrency,2));
                        $products['currency'] = "PKR";
                    }
                }
            }
            else{
                $products['price'] = null;
            }
                if (ProductRating::where('product_id', $products->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $products->id)->get();
                    $rating = 0;
                    for ($k = 0; $k < count($productRating); $k++) {
                        $rating += (int)$productRating[$k]['rating'];
                    }
                    $count = count($productRating);
                    $rat = $rating / $count;
                    $products['rating'] = (string)round($rat, 1);

                } else {
                    $products['rating'] = "0.0";
                }
                //               if ($products['attachments']) {
                //                   for ($i = 0; $i < count($products['attachments']); $i++) {
                //                       $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                //                   }
                //               }
                if(Unit::where('id', $products->unit)->exists()){
                    $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                }
                $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                $products['sellerLocation'] = User::where('id', $products->user_id)->first()['location'];
                $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                // return $products;
            }
            $userRequirements['productCategory'] = ProductCategory::where('id', $userRequirements->category_id)->first()['category'];
            if (Unit::where('id', $userRequirements->unit_id)->exists()) {
                $userRequirements['unit'] = Unit::where('id', $userRequirements->unit_id)->first()['unit'];
            }
            //           $availableServices = Leads::Select('quantity','product_id','category_id','unit_id')->where('id',$request->requirement_id)->first();
            $availableServices['availableProducts'] = Products::where('products_name', $userRequirements['product'])->where('is_approved', 'Y')->where('is_deleted', 'N')->where('is_active', 'Y')->get();
            foreach($availableServices['availableProducts'] as $products) {
                $sellerImage = User::where('id', $products->user_id)->first()['image'];
                $products['attachments'] = getImageUrl($sellerImage, 'images');
                // $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                if($products->price != "" && $products->price!=null){
                if($products['currency_id'] == 1){
                    if($userCurrency == 1)
                    {
                        $products['price'] = strval($products->price);
                        $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                    }
                    else
                    {
                        $products['currency_id'] = $userCurrency;
                        $products['price']= strval(round(($products->price)/$globalCurrency,2));
                        $products['currency'] = "$";
                    }
                }
                else{
                    if($userCurrency == 2){
                        $products['price'] = strval($products->price);
                        $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    }
                    else{
                        $products['currency_id'] = $userCurrency;
                        $products['price']= strval(round(($products->price)*$globalCurrency,2));
                        $products['currency'] = "PKR";
                    }
                }
            }
            else{
                $products['price'] = null;
            }
                if (ProductRating::where('product_id', $products->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $products->id)->get();
                    $rating = 0;
                    for ($k = 0; $k < count($productRating); $k++) {
                        $rating += (int)$productRating[$k]['rating'];
                    }
                    $count = count($productRating);
                    $rat = $rating / $count;
                    $products['rating'] = (string)round($rat, 1);

                } else {
                    $products['rating'] = "0.0";
                }
                //               if ($products['attachments']) {
                //                   for ($i = 0; $i < count($products['attachments']); $i++) {
                //                       $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                //                   }
                //               }
                if (Unit::where('id', $products->unit)->exists()) {
                    $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                }
                $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                $products['sellerLocation'] = User::where('id', $products->user_id)->first()['location'];
                $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                // return $products;
            }
            $data =
                [
                    'buyerHistory' => $userRequirements,
                    'availableServices' => $availableServices
                ];
                //           $userRequirements = $userRequirements->map(function ($requirement)
                //           {
                //
                //               return $requirement;
                //
                //           });
            return $this->sendResponse(1, 'success', $data);
        } else {
            return $this->sendResponse(1, 'success', 'No data found');
        }
    }
    public function addReviews(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(0, $validator->errors()->first());
        }
        if(isset($request->company_id)  && (!isset($request->product_id) || $request->product_id==null)){
            //update seller rating
            if (ProductRating::where('user_id',$request->user()->id)->where('company_id',$request->company_id)->where('product_id',null)->exists()) {
                $userInfo = ProductRating::where('user_id',$request->user()->id)->where('company_id',$request->company_id)->where('product_id',null)->first();
                $userInfo->update([
                    'rating'=>$request->rating,
                    'comment'=>$request->review
                ]);
                if ($userInfo) {
                    $user = User::findOrFail(auth()->user()->id);
                    $notification = new Notification();
                    $notification->user_id = $request->company_id;
                    $notification->type_id = $request->company_id;
                    $notification->schedule_date = \Carbon\Carbon::now();
                    $notification->is_msg_app = 'Y';
                    $notification->notification_type = 'Product';
                    $notification->title = 'Add Rating';
                    $notification->description = $user->name.' Added Rating to your product';
                    $notification->save();
                    $this->send_comm_app_notification();
                    return $this->sendResponse(1, 'success', $userInfo);
                } else {
                    return $this->sendResponse(1, 'success', 'Reviews could not be added!');
                }
            }
            //create rating for seller
            else{
                $reviews = new ProductRating();
                $reviews->product_id =  null;
                $reviews->company_id =$request->company_id;
                $reviews->rating = $request->rating;
                $reviews->user_id = $request->user()->id;
                $reviews->comment = $request->review;
                $result = $reviews->save();
                if ($result) {
                    $user = User::findOrFail(auth()->user()->id);
                    $notification = new Notification();
                    $notification->user_id = $reviews->company_id;
                    $notification->type_id = $reviews->company_id;
                    $notification->schedule_date = \Carbon\Carbon::now();
                    $notification->is_msg_app = 'Y';
                    $notification->notification_type = 'UserInfo';
                    $notification->title = 'Add Rating';
                    $notification->description = $user->name.' Added Rating to your Company';
                    $notification->save();
                    $this->send_comm_app_notification();

                    return $this->sendResponse(1, 'success', $reviews);
                } else {
                    return $this->sendResponse(1, 'success', 'Reviews could not be added!');
                }
            }
        }
        else if(isset($request->product_id)){
            // update product rating
            if (ProductRating::where('user_id',$request->user()->id)->where('product_id',$request->product_id)->exists()) {
                $userInfo = ProductRating::where('user_id',$request->user()->id)->where('product_id',$request->product_id)->first();
                if (Products::where('id',$request->product_id)->exists()) {
                    $productInfo = Products::where('id',$request->product_id)->first()['user_id'];
                }else{
                    $productInfo = 1;
                }
                $userInfo->update([
                    'rating'=>$request->rating,
                    'comment'=>$request->review
                ]);
                if ($userInfo) {
                    $user = User::findOrFail(auth()->user()->id);
                    $notification = new Notification();
                    $notification->user_id =  isset($productInfo) != 1 ? $productInfo:$userInfo->company_id;
                    $notification->type_id = $request->product_id;
                    $notification->schedule_date = \Carbon\Carbon::now();
                    $notification->is_msg_app = 'Y';
                    $notification->notification_type = 'Product';
                    $notification->title = 'Add Rating';
                    $notification->description = $user->name.' Added Rating to your product';
                    $notification->save();
                    $this->send_comm_app_notification();
                    return $this->sendResponse(1, 'success', $userInfo);
                } else {
                    return $this->sendResponse(1, 'success', 'Reviews could not be added!');
                }
            }
            //create product rating
            else{
                $reviews = new ProductRating();
                $reviews->product_id = $request->product_id;
                $reviews->company_id = Products::where('id',$request->product_id)->exists() ? Products::where('id',$request->product_id)->first()['user_id'] : null ;
                $reviews->rating = $request->rating;
                $reviews->user_id = $request->user()->id;
                $reviews->comment = $request->review;
                $result = $reviews->save();
                if ($result) {

                    $user = User::findOrFail(auth()->user()->id);
                    $notification = new Notification();
                    $notification->user_id = $reviews->company_id ;
                    $notification->type_id = $request->product_id;
                    $notification->schedule_date = \Carbon\Carbon::now();
                    $notification->is_msg_app = 'Y';
                    $notification->notification_type = 'Product';
                    $notification->title = 'Add Rating';
                    $notification->description = $user->name.' Added Rating to your product';
                    $notification->save();
                    $this->send_comm_app_notification();

                return $this->sendResponse(1, 'success', $reviews);
                } else {
                    return $this->sendResponse(1, 'success', 'Reviews could not be added!');
                }
            }

        }
    }
    public function getReviews(Request $request)
    {
        if ($request->product_id) {
            if (ProductRating::where('product_id', $request->product_id)->exists()) {
                $ratings = ProductRating::where('product_id', $request->product_id)->get();
                $ratings = $ratings->map(function ($rating) {
                    $rating['userName'] = User::where('id', $rating->user_id)->first()['username'];
                    $sellerImage = User::where('id', $rating->user_id)->first()['image'];
                    if ($sellerImage != null) {
                        $rating['attachment'] = getImageUrl($sellerImage, 'images');
                    }else{
                        $rating['attachment'] = getImageUrl('profile.png', 'images12');
                    }
                    return $rating;
                });
                return $this->sendResponse(1, 'success', $ratings);
            } else {
                return $this->sendResponse(1, 'success', 'No data found against this product');
            }
        } else if ($request->company_id) {
            if (ProductRating::where('company_id', $request->company_id)->exists()) {
                $ratings = ProductRating::where('company_id', $request->company_id)->where('product_id',null)->get();
                $leads = $ratings->map(function ($rating) {
                    $rating['companyName'] = User::where('id', $rating->company_id)->first()['username'];
                    $rating['ratedBy'] = User::where('id', $rating->user_id)->first()['username'];
                    $sellerImage = User::where('id', $rating->company_id)->first()['image'];
                    if ($sellerImage != null) {
                        $rating['attachment'] = getImageUrl($sellerImage, 'images');
                    }else{
                        $rating['attachment'] = getImageUrl('profile.png', 'images12');
                    }
                    return $rating;
                });
                return $this->sendResponse(1, 'success', $leads);
            } else {
                return $this->sendResponse(1, 'success', 'No data found against this user');
            }
        } else {
            return $this->sendResponse(1, 'success', 'no data found');
        }
    }
    public function addFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'user_id' => 'required|not_in:0',
            'feedback' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        $feedback = new Feedback();
        $feedback->user_id = $request->user_id;
        $feedback->feedback = $request->feedback;
        $result = $feedback->save();
        if ($result) {
            return $this->sendResponse(1, 'Thank you for submitting the feedback', '');
        } else {
            return $this->sendError(false, 'Data not added');
        }
    }
    public function getFeedbacks()
    {
        return $this->sendResponse(true, 'success', Feedback::all());
    }
    public function chat(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'buyer_id' => 'required|not_in:0',
            'seller_id' => 'required|not_in:0',
            'product_id' => 'required|not_in:0',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        if (!Chat::where('product_id', $request->product_id)->where('seller_id', $request->seller_id)->where('buyer_id', $request->buyer_id)->exists()) {
            $chat = new Chat();
            $chat->product_id = $request->product_id;
            $chat->seller_id = $request->seller_id;
            $chat->buyer_id = $request->buyer_id;
            $chat->save();
        } else {
            $chat = Chat::where('product_id', $request->product_id)->where('seller_id', $request->seller_id)->where('buyer_id', $request->buyer_id)->first();
        }
        //        return $chat;
        //        if ($request->has('message')) {
        $chatMessage = new ChatMessage();
        $chatMessage->message = $request->message;
        $chatMessage->user_id = $request->user()->id;
        $chatMessage->chat_id = $chat->id;
        $chatMessage->save();
        if ($chatMessage) {
            $notification = new Notification();
            if($chat->seller_id == $request->user_id){
                $notification->user_id = $chat->buyer_id;
            }else{
                $notification->user_id = $chat->seller_id;
            }
            $notification->type_id = $chat->id;
            $notification->schedule_date = \Carbon\Carbon::now();
            $notification->is_msg_app = 'Y';
            $notification->notification_type = 'Chat';
            $notification->title = 'Chat Message';
            $notification->description = 'You Received a new message';
            $notification->save();
            $this->send_comm_app_notification();
        }
        //        return $chatMessage;
        //        }
        $attachmentFiles = '';
        if ($request->file('image')) {
            //            for ($i = 0; $i < count($request->file('image')); $i++) {
            $files = $request->file('image');
            $chatFiles = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();
            $files->move(public_path('/images/chat'), $chatFiles);
            $chatImage = new ChatAttachment();
            $chatImage->message_id = $chatMessage->id;
            $chatImage->attachment = $chatFiles;
            $chatImage->user_id = $request->user()->id;
            $chatImage->save();
            //            $attachmentFiles = getImageUrl($chatFiles, 'chat-attachments');
            //            }
        }
            //        "id": 20,
            //                    "message": "happy",
            //                    "user_id": 30,
            //                    "chat_id": 12,
            //                    "created_at": "2021-05-25T10:00:57.000000Z",
            //                    "updated_at": "2021-05-25T10:00:57.000000Z",
            //                    "attachment": "",
            //                    "role": "buyer",
            //                    "userImage": "https://cartify.viion.net/public/assets/images/profile.png";
        if (!empty($chatFiles)) {

            $attachment = getImageUrl($chatFiles, 'chat-attachments');
        } else {
            $attachment = '';
        }
        $role = User::where('id', $request->user()->id)->first()['role'];
        $userRole = $role==4 ? 'buyer' : 'seller';
        $sellerImage = User::where('id', $request->user()->id)->first()['image'];
        if (!empty($sellerImage)) {
            $userImage = getImageUrl($sellerImage, 'images');
        } else {
            $userImage = getImageUrl('profile.png', 'images12');
        }
        $userData =
            [
                "id" => $chatMessage->id,
                "message" => $request->message,
                "user_id" => $request->user()->id,
                "chat_id" => $chat->id,
                "created_at" => $chatMessage->created_at,
                "updated_at" => $chatMessage->updated_at,
                "attachment" => $attachment,
                "role" => $userRole,
                "userImage" => $userImage


            ];
        //        $chatResponse = Chat::where('product_id', $request->product_id)->where('seller_id', $request->seller_id)->where('buyer_id', $request->buyer_id)->orderBy('id', 'DESC')->paginate(100);
        //        for ($i = 0; $i < count($chatResponse); $i++) {
        //            if (ChatAttachment::where('chat_id', $chatResponse[$i]['id'])->exists()) {
        //                $attachment = ChatAttachment::where('chat_id', $chatResponse[$i]['id'])->get();
        //                for ($k = 0; $k < count($attachment); $k++) {
        //                    $attachment[$k]['attachment'] = getImageUrl($attachment[$k]['attachment'], 'chat-attachments');
        //                }
        //                $chatResponse[$i]['attachment'] = $attachment;
        //            } else {
        //                $chatResponse[$i]['attachment'] = '';
        //            }
        //        }
        return $this->sendResponse(1, 'success', $userData);

    }
    public function addChatMsg(Request $request)
    {
        // dd($request->file('image'));
        $validator = Validator::make($request->all(), [

            'chat_id' => 'required|not_in:0',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        if (Chat::where('id',$request->chat_id)->exists()) {

            $chat = Chat::where('id', $request->chat_id)->first();

            $chatMessage = new ChatMessage();
            $chatMessage->message = $request->message;
            $chatMessage->user_id = $request->user()->id;
            $chatMessage->chat_id = $chat->id;
            $chatMessage->save();
            $notification = new Notification();
            if($chat->seller_id == $request->user()->id){
                $notification->user_id = $chat->buyer_id;
            }else{
                $notification->user_id = $chat->seller_id;
            }
            $notification->type_id = $chat->id;
            $notification->schedule_date = \Carbon\Carbon::now();
            $notification->is_msg_app = 'Y';
            $notification->notification_type = 'Chat';
            $notification->title = 'Chat Message';
            $notification->description = 'You Received a new message';
            $notification->save();
            $this->send_comm_app_notification();
            $attachmentFiles = '';
            if ($request->file('image')) {
                $files = $request->file('image');
                $chatFiles = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();
                $files->move(public_path('/images/chat'), $chatFiles);
                $chatImage = new ChatAttachment();
                $chatImage->message_id = $chatMessage->id;
                $chatImage->attachment = $chatFiles;
                $chatImage->user_id = $request->user()->id;
                $chatImage->save();
            }
            if (!empty($chatFiles)) {

                $attachment = getImageUrl($chatFiles, 'chat-attachments');
            } else {
                $attachment = '';
            }
            $role = User::where('id', $request->user()->id)->first()['role'];
            $userRole = $role==4 ? 'buyer' : 'seller';
            $sellerImage = User::where('id', $request->user()->id)->first()['image'];
            if (!empty($sellerImage)) {
                $userImage = getImageUrl($sellerImage, 'images');
            } else {
                $userImage = getImageUrl('profile.png', 'images12');
            }
            $userData =
                [
                    "id" => $chatMessage->id,
                    "message" => $request->message,
                    "user_id" => $request->user()->id,
                    "chat_id" => $chat->id,
                    "created_at" => $chatMessage->created_at,
                    "updated_at" => $chatMessage->updated_at,
                    "attachment" => $attachment,
                    "role" => $userRole,
                    "userImage" => $userImage


                ];
            return $this->sendResponse(1, 'success', $userData);
        }else {
            return $this->sendResponse(1,'No Record Found','');
        }

    }
    public function getChat(Request $request)
    {
        $template = Template::Select('id', 'template')->get();
        $chatMessage = '';
        $productInfo = [];
        if (Chat::where('id', $request->chat_id)->exists()) {
            $productId = Chat::where('id', $request->chat_id)->first()['product_id'];
            if (Products::where('id', $productId)->exists()) {
                $productInfo = Products::where('id', $productId)->first();
                if(Unit::where('id', $productInfo->unit)->exists()){
                    $productInfo['unit'] = Unit::where('id', $productInfo->unit)->first()['unit'];
                }
                $productInfo['sellerName'] = User::where('id', $productInfo->user_id)->first()['username'];
                $productInfo['sellerLocation'] = User::where('id', $productInfo->user_id)->first()['location'];
                $productInfo['sellerEmail'] = User::where('id', $productInfo->user_id)->first()['email'];
                $productInfo['sellerPhone'] = User::where('id', $productInfo->user_id)->first()['phone'];
                $productInfo['categoryId'] = ProductCategory::where('category', $productInfo->category)->first()['id'];
                $productInfo['currency'] = Currency::where('id',$productInfo->currency_id)->first()['currency'];
                $sellerImage = User::where('id', $productInfo->user_id)->first()['image'];
                $productInfo['companyImage'] = getImageUrl($sellerImage, 'images');
                if (ProductRating::where('product_id', $productInfo->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $productInfo->id)->get();
                    $rating = 0;
                    for ($k = 0; $k < count($productRating); $k++) {
                        $rating += (int)$productRating[$k]['rating'];
                    }
                    $count = count($productRating);
                    $rat = $rating / $count;
                    $productInfo['rating'] = (string)round($rat, 1);

                } else {
                    $productInfo['rating'] = "0.0";
                }
                if (ProductAttachment::where('products_id', $productInfo->id)->exists()) {
                    $productsAttachments = ProductAttachment::where('products_id', $productInfo->id)->first()['image'];
                    $productInfo['productImage'] = getImageUrl($productsAttachments, 'product-attachments');
                } else {
                    $productInfo['productImage'] = getImageUrl('profile.png', 'images12');
                }
            } else {
                $productInfo = [];
            }
            $chatId = Chat::where('id', $request->chat_id)->first()['id'];
            if (ChatMessage::where('chat_id', $chatId)->exists()) {
                $chatMessage = ChatMessage::where('chat_id', $chatId)->orderBy('id', 'DESC')->paginate(10);
                for ($i = 0; $i < count($chatMessage); $i++) {
                    //if (ChatAttachment::where('message_id', $chatMessage[$i]['id'])->exists()) {
                    //    $chatMessage[$i]['attachment'] = ChatAttachment::where('message_id', $chatMessage[$i]['id'])->get();
                    //    for ($k = 0; $k < count($chatMessage[$i]['attachment']); $k++) {
                    //        $chatMessage[$i]['attachment'][$k]['attachment'] = getImageUrl($chatMessage[$i]['attachment'][$k]['attachment'], 'chat-attachments');
                    //    }
                    //} else {
                    //    $chatMessage[$i]['attachment'] = [];
                    //}
                    // if (ChatAttachment::where('message_id', $chatMessage[$i]['id'])->exists()) {
                    //     $attachments = ChatAttachment::where('message_id', $chatMessage[$i]['id'])->first()['attachment'];
                    //     $chatMessage[$i]['attachment'] = getImageUrl($attachments, 'chat-attachments');
                    // } else {
                    //     $chatMessage[$i]['attachment'] = '';
                    // }
                    $chatMessage[$i]['created_at'] = date('H:i:s', strtotime($chatMessage[$i]['created_at']));
                    $role = User::where('id', $chatMessage[$i]['user_id'])->first()['role'];
                    $chatMessage[$i]['role'] = $role==4 ? 'buyer' : 'seller';
                    $sellerImage = User::where('id', $chatMessage[$i]['user_id'])->first()['image'];
                    if (!empty($sellerImage)) {
                        $chatMessage[$i]['userImage'] = getImageUrl($sellerImage, 'images');
                    } else {
                        $chatMessage[$i]['userImage'] = getImageUrl('profile.png', 'images12');
                    }
                }

            } else {
                $chatMessage = null;
            }

        } else {
            $chatMessage = null;
        }
        if (Chat::where('id', $request->chat_id)->exists()) {
            $chatId = Chat::where('id', $request->chat_id)->first()['id'];
        } else {
            return $this->sendError(0, 'Chat does not exist');
        }
        if (Leads::where('chat_id',$chatId)->exists()) {
            $leads = Leads::where('chat_id',$chatId)->first();
        }else {
            $leads = null ;
        }
        $data =
            [
                'productDetail' => $productInfo,
                'template' => $template,
                'chatId' => $chatId,
                'chats' => $chatMessage,
                'leads' => $leads
            ];
        return $this->sendResponse(1, 'success', $data);
    }
    public function addPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'transaction_id' => 'required|not_in:0',
            'user_id' => 'required|not_in:0',
            'amount' => 'required|not_in:0',
            'payment_date' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        if (empty($request->file('reciept'))) {
            return $this->sendError(false, 'Please add receipt and try again!');
        }

        $files = $request->file('reciept');
        $paymentFile = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();
        $files->move(public_path('/images/receipt'), $paymentFile);
        $payment = new Payment();
        $payment->user_id = $request->user_id;
        $payment->transaction_id = $request->transaction_id;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->lead_id = $request->lead_id;
        $payment->receipt = $paymentFile;
        $result = $payment->save();
        if ($result) {
            return $this->sendResponse(1, 'success', 'Payment added successfully!');
        } else {
            return $this->sendResponse(1, 'success', 'Payment could not be added.Please try again!');
        }

    }
    public function bestPractice(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'product_id' => 'required|not_in:0',
            'quantity' => 'required|not_in:0',
            'unit_id' => 'required|not_in:0',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        $sellerId = Products::where('id', $request->product_id)->first()['user_id'];
        if (!Chat::where('product_id', $request->product_id)->where('seller_id', $sellerId)->where('buyer_id', $request->user()->id)->exists()) {
            $chat = new Chat();
            $chat->product_id = $request->product_id;
            $chat->seller_id = $sellerId;
            $chat->buyer_id = $request->user()->id;
            $chat->save();
        } else {
            $chat = Chat::where('product_id', $request->product_id)->where('seller_id', $sellerId)->where('buyer_id', $request->user()->id)->first();
        }
        $cat = Products::where('id', $request->product_id)->first();
        $categoryId = ProductCategory::where('category', $cat->category)->first()['id'];
        if (Leads::where('product_id',$request->product_id)->where('user_id',$request->user()->id)->exists()) {
            $result = Leads::where('product_id',$request->product_id)->where('user_id',$request->user()->id)->first();
            $result->update([
                'quantity'=>$request->quantity,
                'unit_id'=>$request->unit_id,
                'bid'=>$request->bid,
                'chat_id'=>$chat->id,
                'is_approved' => 'Y'
            ]);
        }
        else {
            $leads = new Leads();
            $leads->product_id = $request->product_id;
            $leads->seller_id = $cat->user_id;
            $leads->category_id = $categoryId;
            $leads->product_name = $cat->products_name;
            $leads->unit_id = $request->unit_id;
            $leads->quantity = $request->quantity;
            $leads->bid = $request->bid;
            $leads->bid_status = 'Y';
            $leads->is_approved = 'Y';
            $leads->user_id = $request->user()->id;
            $leads->chat_id = $chat->id;
            $result = $leads->save();
//            if ($result) {
//                $notification = new Notification();
//                $notification->user_id = $request->user()->id;
//                $notification->type_id = $leads->id;
//                $notification->schedule_date = \Carbon\Carbon::now();
//                $notification->is_msg_app = 'Y';
//                $notification->notification_type = 'Lead';
//                $notification->title = 'Lead Created';
//                $notification->description = 'Congratulations! Your lead has been added';
//                $notification->save();
//                $this->send_comm_app_notification();
//            }
        }

        $data = [
            'chatId' => $chat->id,
        ];
        if ($result) {
            return $this->sendResponse(1, 'success', $data);
        } else {
            return $this->sendResponse(1, 'success', 'Requirement could not be added.Please try again!');
        }

    }
		// Comparison function
    public function date_compare($element1, $element2) {
        $datetime1 = strtotime($element1['updated_at']);
        $datetime2 = strtotime($element2['updated_at']);
        return $datetime1 - $datetime2;
    }
      public function getChatList(Request $request)
    {
        if (Chat::where('buyer_id', $request->user()->id)->orwhere('seller_id', $request->user()->id)->exists()) {
            $chat = Chat::where('buyer_id', $request->user()->id)->orwhere('seller_id', $request->user()->id)->orderBy('id', 'desc')->paginate(100);
            for ($i = 0; $i < count($chat); $i++) {
                if (ChatMessage::where('chat_id', $chat[$i]['id'])->exists()) {
					$chatMessageLatest=ChatMessage::where('chat_id', $chat[$i]['id'])->latest()->first();
                    $chat[$i]['lastMessage'] = $chatMessageLatest->message;
					        $chat[$i]['updated_at'] = $chatMessageLatest->updated_at;

                    $lastChatDate = ChatMessage::where('chat_id', $chat[$i]['id'])->latest()->first()['created_at'];
                    $date = strtotime($lastChatDate);
                    $day = date("D", $date);
                    $timeStamp = new \DateTime('@' . $date);
                    $chat[$i]['lastChatTime'] = $day . ' ' . $timeStamp->format('H') . ':' . $timeStamp->format('i');
                } else {
                    $date12 = strtotime($chat[$i]['created_at']);
                    $day = date("D", $date12);
                    $timeStamp = new \DateTime('@' . $date12);
                    $chat[$i]['lastChatTime'] = $day . ' ' . $timeStamp->format('H') . ':' . $timeStamp->format('i');;
                    $chat[$i]['lastMessage'] = '';
                }

                $chat[$i]['productName'] = Products::where('id', $chat[$i]['product_id'])->first()['products_name'];
                if (ProductAttachment::where('products_id', $chat[$i]['product_id'])->exists()) {
                    $productsAttachments = ProductAttachment::where('products_id', $chat[$i]['product_id'])->first()['image'];
                    $chat[$i]['productImage'] = getImageUrl($productsAttachments, 'product-attachments');
                } else {
                    $chat[$i]['productImage'] = getImageUrl('profile.png', 'images12');
                }

            }


            return $this->sendResponse(1, 'success', $chat);
        }


//		    elseif (Chat::where('seller_id', $request->user()->id)->exists()) {
//            $chat = Chat::where('seller_id', $request->user()->id)->paginate(10);
//            for ($i = 0; $i < count($chat); $i++) {
//                if (ChatMessage::where('chat_id', $chat[$i]['id'])->exists()) {
//                    $chat[$i]['lastMessage'] = ChatMessage::where('chat_id', $chat[$i]['id'])->latest()->first()['message'];
//                    $lastChatDate = ChatMessage::where('chat_id', $chat[$i]['id'])->latest()->first()['created_at'];
//                    $date = strtotime($lastChatDate);
//                    $day = date("D", $date);
//                    $timeStamp = new \DateTime('@' . $date);
//                    $chat[$i]['lastChatTime'] = $day . ' ' . $timeStamp->format('H') . ':' . $timeStamp->format('i');
//                } else {
//                    $date12 = strtotime($chat[$i]['created_at']);
//                    $day = date("D", $date12);
//                    $timeStamp = new \DateTime('@' . $date12);
//                    $chat[$i]['lastChatTime'] = $day . ' ' . $timeStamp->format('H') . ':' . $timeStamp->format('i');;
//                    $chat[$i]['lastMessage'] = '';
//                }
//
//                $chat[$i]['productName'] = Products::where('id', $chat[$i]['product_id'])->first()['products_name'];
//                if (ProductAttachment::where('products_id', $chat[$i]['product_id'])->exists()) {
//                    $productsAttachments = ProductAttachment::where('products_id', $chat[$i]['product_id'])->first()['image'];
//                    $chat[$i]['productImage'] = getImageUrl($productsAttachments, 'product-attachments');
//                } else {
//                    $chat[$i]['productImage'] = getImageUrl('profile.png', 'images12');
//                }
//
//            }
//            return $this->sendResponse(1, 'success', $chat);
//        }

		else {
            return $this->sendResponse(1, 'success', []);
        }

    }
    public function getConnectInfo()
    {
        $connectInfo = Configuration::all();
        for ($i = 0; $i < count($connectInfo); $i++) {
            if ($connectInfo[$i]['key'] == 'company_name') {
                $companyName = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_email') {
                $companyEmail = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_phone') {
                $companyPhone = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_mobile') {
                $companyMobile = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_mail') {
                $companyMail = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_address') {
                $companyAddress = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_wattsapp') {
                $companyWhattsapp = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_fb') {
                $companyFb = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_linkdin') {
                $companyLinkdin = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_youtube') {
                $companyYoutube = $connectInfo[$i]['value'];
            }
            if ($connectInfo[$i]['key'] == 'company_image') {
                $companyImage = getImageUrl($connectInfo[$i]['value'], 'images');
            }
        }
        $companyData =
            [
                'companyName' => $companyName,
                'companyEmail' => $companyEmail,
                'companyPhone' => $companyPhone,
                'companyMail' => $companyMail,
                'companyMobile' => $companyMobile,
                'companyAddress' => $companyAddress,
                'companyWhattsapp' => $companyWhattsapp,
                'companyFb' => $companyFb,
                'companyLinkdin' => $companyLinkdin,
                'companyYoutube' => $companyYoutube,
                'companyImage' => $companyImage
            ];
        return $this->sendResponse(1, 'success', $companyData);
    }
    public function getUserNotification(Request $request)
    {
        $notifications = Notification::where('sent_status', 'Y')->where('user_id', Auth::user()->id)->orderBy('app_sent_date','DESC')->paginate(10);
        $notifications = $notifications->map(function ($noti) {
            $scheduleDate = date('d-m-Y H:i A',strtotime($noti->schedule_date));
            $appSentDate = date('d-m-Y H:i A',strtotime($noti->app_sent_date));
            $data = ([
                'id'=>$noti->id,
                'user_id'=>$noti->user_id,
                'title'=>$noti->title,
                'description'=>$noti->description,
                'schedule_date'=>$scheduleDate,
                'read_status'=>$noti->read_status,
                'app_sent_date'=>$appSentDate,
                'type_id'=>$noti->type_id,
                'notification_type'=>$noti->notification_type
            ]);

            return $data;
//            return collect($noti->toArray())
//                ->only(['id', 'user_id', 'title','description',$data,'read_status','type_id','notification_type'])
//                ->all();
        });
        $update = Notification::where('user_id',Auth::user()->id)->where('sent_status', 'Y')->where('read_status', 'N')->update(['read_status'=>'Y']);

//        $update->update(['read_status'=>'Y']);
        return $this->sendResponse(1, 'success', $notifications);
    }
    public function phoneCall(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'seller_id' => 'required|not_in:0',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        $phoneCall = new PhoneCall();
        $phoneCall->seller_id = $request->seller_id;
        $phoneCall->buyer_id = $request->user()->id;
        $phoneCall->save();
        $count = PhoneCall::where('seller_id', $request->seller_id)->count();
        return $this->sendResponse(1, 'success', ['callCount' => $count]);
    }
    public function exportLeads(Request $request)
    {
                //        $productIds = Products::where('user_id', $request->user()->id)->pluck('id');
        $userRequirements = Leads::where('seller_id', $request->user()->id)->where('is_approved','Y')->where('is_contacted', 'N')->get();
        if ($userRequirements) {
            for ($i = 0; $i < count($userRequirements); $i++) {
                $products = Products::where('id', $userRequirements[$i]['product_id'])->first();
                //                $userRequirements[$i]['product'] = $products->products_name;
                // $userRequirements[$i]['subCategory'] = $products->sub_category;
                $userRequirements[$i]['productCategory'] = ProductCategory::where('id', $userRequirements[$i]['category_id'])->first()['category'];
                //                $userRequirements[$i]['subCategory'] = ProductCategory::where('id', $userRequirements[$i]['sub_id'])->first()['category'];
                if (Unit::where('id', $userRequirements[$i]['unit_id'])->exists()) {

                    $userRequirements[$i]['unit'] = Unit::where('id', $userRequirements[$i]['unit_id'])->first()['unit'];
                }
            }
        }
                //        $urgentRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->whereIn('product_id', $productIds)->where('is_contacted', 'N')->where('is_urgent', 'Y')->get();
                //        if ($urgentRequirements) {
                //            for ($k = 0; $k < count($urgentRequirements); $k++) {
                //                $urgentRequirements[$k]['product'] = Products::where('id', $urgentRequirements[$k]->product_id)->first()['products_name'];
                //                $urgentRequirements[$k]['productCategory'] = ProductCategory::where('id', $urgentRequirements[$k]->category_id)->first()['category'];
                //                $urgentRequirements[$k]['unit'] = Unit::where('id', $urgentRequirements[$k]->unit_id)->first()['unit'];
                //            }
                //
                //        }
                //        $latestRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->whereIn('product_id', $productIds)->where('is_contacted', 'N')->whereDate('created_at', '>', Carbon::now()->subDays(1))->get();
                //        if ($latestRequirements) {
                //            for ($l = 0; $l < count($latestRequirements); $l++) {
                //                $latestRequirements[$l]['product'] = Products::where('id', $latestRequirements[$l]->product_id)->first()['products_name'];
                //                $latestRequirements[$l]['productCategory'] = ProductCategory::where('id', $latestRequirements[$l]->category_id)->first()['category'];
                //                $latestRequirements[$l]['unit'] = Unit::where('id', $latestRequirements[$l]->unit_id)->first()['unit'];
                //            }
                //
                //        }
                //        $pastRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->whereIn('product_id', $productIds)->whereDate('created_at', '!=', date('Y-m-d'))->where('is_contacted', 'N')->get();
                //        if ($pastRequirements) {
                //            for ($m = 0; $m < count($pastRequirements); $m++) {
                //                $pastRequirements[$m]['product'] = Products::where('id', $pastRequirements[$m]->product_id)->first()['products_name'];
                //                $pastRequirements[$m]['productCategory'] = ProductCategory::where('id', $pastRequirements[$m]->category_id)->first()['category'];
                //                $pastRequirements[$m]['unit'] = Unit::where('id', $pastRequirements[$m]->unit_id)->first()['unit'];
                //            }
                //
                //        }
                //        $data =
                //            [
                //                'latest' => $latestRequirements,
                //                'all' => $userRequirements,
                //                'urgent' => $urgentRequirements,
                //                'past' => $pastRequirements
                //            ];
        return $this->sendResponse(1, 'success', $userRequirements);
    }
    public function consumedLeads(Request $request)
    {
        $userRequirements = Leads::where('seller_id', $request->user()->id)->where('is_approved','Y')->where('is_contacted', 'Y')->get();
        if ($userRequirements) {
            for ($i = 0; $i < count($userRequirements); $i++) {
                $products = Products::where('id', $userRequirements[$i]['product_id'])->first();
                //                $userRequirements[$i]['product'] = $products->products_name;
                $userRequirements[$i]['subCategory'] = $products->sub_category;
                //                $userRequirements[$i]['product'] = Products::where('id', $userRequirements[$i]['product_id'])->first()['products_name'];
                $userRequirements[$i]['productCategory'] = ProductCategory::where('id', $userRequirements[$i]['category_id'])->first()['category'];
                if (Unit::where('id', $userRequirements[$i]['unit_id'])->exists()) {
                    $userRequirements[$i]['unit'] = Unit::where('id', $userRequirements[$i]['unit_id'])->first()['unit'];
                }
            }
        }
            //        $urgentRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->whereIn('product_id', $productIds)->where('is_contacted', 'Y')->where('is_urgent', 'Y')->get();
            //        if ($urgentRequirements) {
            //            for ($k = 0; $k < count($urgentRequirements); $k++) {
            //                $urgentRequirements[$k]['product'] = Products::where('id', $urgentRequirements[$k]->product_id)->first()['products_name'];
            //                $urgentRequirements[$k]['productCategory'] = ProductCategory::where('id', $urgentRequirements[$k]->category_id)->first()['category'];
            //                $urgentRequirements[$k]['unit'] = Unit::where('id', $urgentRequirements[$k]->unit_id)->first()['unit'];
            //            }
            //
            //        }
            //        $latestRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->whereIn('product_id', $productIds)->where('is_contacted', 'Y')->whereDate('created_at', '>', Carbon::now()->subDays(1))->get();
            //        if ($latestRequirements) {
            //            for ($l = 0; $l < count($latestRequirements); $l++) {
            //                $latestRequirements[$l]['product'] = Products::where('id', $latestRequirements[$l]->product_id)->first()['products_name'];
            //                $latestRequirements[$l]['productCategory'] = ProductCategory::where('id', $latestRequirements[$l]->category_id)->first()['category'];
            //                $latestRequirements[$l]['unit'] = Unit::where('id', $latestRequirements[$l]->unit_id)->first()['unit'];
            //            }
            //
            //        }
            //        $pastRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->whereIn('product_id', $productIds)->whereDate('created_at', '!=', date('Y-m-d'))->where('is_contacted', 'Y')->get();
            //        if ($pastRequirements) {
            //            for ($m = 0; $m < count($pastRequirements); $m++) {
            //                $pastRequirements[$m]['product'] = Products::where('id', $pastRequirements[$m]->product_id)->first()['products_name'];
            //                $pastRequirements[$m]['productCategory'] = ProductCategory::where('id', $pastRequirements[$m]->category_id)->first()['category'];
            //                $pastRequirements[$m]['unit'] = Unit::where('id', $pastRequirements[$m]->unit_id)->first()['unit'];
            //            }
            //
            //        }
            //        $data =
            //            [
            //                'latest' => $latestRequirements,
            //                'all' => $userRequirements,
            //                'urgent' => $urgentRequirements,
            //                'past' => $pastRequirements
            //            ];
        return $this->sendResponse(1, 'You are about to consume 1 lead.', $userRequirements);

    }
    public function sellerProducts(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $approvedProducts = Products::where('user_id', $request->user()->id)->where('is_deleted', 'N')->where('is_approved', 'Y')->get();
//      dd($approvedProducts);
        if ($approvedProducts) {
            for ($i = 0; $i < count($approvedProducts); $i++) {
                // $approvedProducts[$i]['currency'] = Currency::where('id',$approvedProducts[$i]['currency_id'])->first()['currency'];
                if($approvedProducts[$i]['price'] !="" && $approvedProducts[$i]['price'] !=null){
                if($approvedProducts[$i]['currency_id'] == 1){
                    if($userCurrency == 1)
                    {
                        $approvedProducts[$i]['price'] = strval($approvedProducts[$i]['price']);
                        $favouriteProduct['currency'] = Currency::where('id',$approvedProducts[$i]['currency_id'])->first()['currency'];
                    }
                    else
                    {
                        $approvedProducts[$i]['currency_id'] = $userCurrency;
                        $approvedProducts[$i]['price']=strval(round(($approvedProducts[$i]['price'])/$globalCurrency,2));
                        $approvedProducts[$i]['currency'] = "$";
                    }
                }
                else{

                    if($userCurrency == 2){
                        $approvedProducts[$i]['price'] =strval($approvedProducts[$i]['price']);
                        $approvedProducts[$i]['currency'] = Currency::where('id',$approvedProducts[$i]['currency_id'])->first()['currency'];
                    }
                    else{
                        $approvedProducts[$i]['currency_id'] = $userCurrency;
                        $approvedProducts[$i]['price']= strval(round(($approvedProducts[$i]['price'])*$globalCurrency,2));
                        $approvedProducts[$i]['currency'] = "PKR";
                    }
                }
            }
            else{
                $approvedProducts[$i]['price'] = null;
            }
                $approvedProducts[$i]['productView'] = ProductReview::where('product_id', $approvedProducts[$i]['id'])->count();
                $approvedProducts[$i]['mobileNumber'] = User::where('id', $approvedProducts[$i]['user_id'])->first()['phone'];
                $approvedProducts[$i]['attachments'] = ProductAttachment::where('products_id', $approvedProducts[$i]->id)->get();
                for ($k = 0; $k < count($approvedProducts[$i]['attachments']); $k++) {
                    if (!empty($approvedProducts[$i]['attachments'][$k]['image'])) {
                        $approvedProducts[$i]['attachments'][$k]['image'] = getImageUrl($approvedProducts[$i]['attachments'][$k]['image'], 'product-attachments');
                    } else {
                        $approvedProducts[$i]['attachments'][$k]['image'] = getImageUrl('profile.png', 'images12');
                    }
                }
                $approvedProducts[$i]['location'] = User::where('id', $approvedProducts[$i]['user_id'])->first()['location'];
                $approvedProducts[$i]['sellerName'] = User::where('id', $approvedProducts[$i]['user_id'])->first()['username'];
                if (ProductRating::where('product_id', $approvedProducts[$i]->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $approvedProducts[$i]->id)->avg('rating');
                    $approvedProducts[$i]['rating'] = (string)$productRating;
                    // $rating = 0;
                    // for ($k = 0; $k < count($productRating); $k++) {
                    //     $rating += (int)$productRating[$k]['rate'];
                    // }
                    // $count = count($productRating);
                    // $rat = $rating / $count;
                    // $approvedProducts[$i]['rating'] = round($rat, 1);

                } else {
                    $approvedProducts[$i]['rating'] = "0.0";
                }
                if(Unit::where('id', $approvedProducts[$i]->unit)->exists()){
                    $approvedProducts[$i]['unit'] = Unit::where('id', $approvedProducts[$i]->unit)->first()['unit'];
                }

            }
        }
        // return $approvedProducts;
        $pendingProducts = Products::where('user_id', $request->user()->id)->where('is_approved','N')->where('is_deleted', 'N')->get();
//     dd(auth()->user()->id);
//        dd($pendingProducts);
        if ($pendingProducts) {
            for ($j = 0; $j < count($pendingProducts); $j++) {
                // $pendingProducts[$j]['currency'] = Currency::where('id',$pendingProducts[$j]['currency_id'])->first()['currency'];
                if($pendingProducts[$j]['price'] != "" && $pendingProducts[$j]['price'] !=null){
                if($pendingProducts[$j]['currency_id'] == 1){
                    if($userCurrency == 1)
                    {
                        $pendingProducts[$j]['price'] = strval($pendingProducts[$j]['price']);
                        $pendingProducts['currency'] = Currency::where('id',$pendingProducts[$j]['currency_id'])->first()['currency'];
                    }
                    else
                    {
                        $pendingProducts[$j]['currency_id'] = $userCurrency;
                        $pendingProducts[$j]['price']= strval(round(($pendingProducts[$j]['price'])/$globalCurrency,2));
                        $pendingProducts[$j]['currency'] = "$";
                    }
                }
                else{

                    if($userCurrency == 2){
                        $pendingProducts[$j]['price'] = strval($pendingProducts[$j]['price']);
                        $pendingProducts[$j]['currency'] = Currency::where('id',$pendingProducts[$j]['currency_id'])->first()['currency'];
                    }
                    else{
                        $pendingProducts[$j]['currency_id'] = $userCurrency;
                        $pendingProducts[$j]['price']=strval(round(($pendingProducts[$j]['price'])*$globalCurrency,2));
                        $pendingProducts[$j]['currency'] = "PKR";
                    }
                }
            }
            else{
                $pendingProducts[$j]['price'] = null;
            }

                $pendingProducts[$j]['productView'] = ProductReview::where('product_id', $pendingProducts[$j]['id'])->count();
                $pendingProducts[$j]['mobileNumber'] = User::where('id', $pendingProducts[$j]['user_id'])->first()['phone'];
                $pendingProducts[$j]['attachments'] = ProductAttachment::where('products_id', $pendingProducts[$j]->id)->get();
                for ($k = 0; $k < count($pendingProducts[$j]['attachments']); $k++) {
                    if (!empty($pendingProducts[$j]['attachments'][$k]['image'])) {
                        $pendingProducts[$j]['attachments'][$k]['image'] = getImageUrl($pendingProducts[$j]['attachments'][$k]['image'], 'product-attachments');
                    } else {
                        $pendingProducts[$j]['attachments'][$k]['image'] = getImageUrl('profile.png', 'images12');
                    }
                }
                $pendingProducts[$j]['location'] = User::where('id', $pendingProducts[$j]['user_id'])->first()['location'];
                $pendingProducts[$j]['sellerName'] = User::where('id', $pendingProducts[$j]['user_id'])->first()['username'];
                if (ProductRating::where('product_id', $pendingProducts[$j]->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $pendingProducts[$j]->id)->get();
                    $rating = 0;
                    for ($k = 0; $k < count($productRating); $k++) {
                        $rating += (int)$productRating[$k]['rate'];
                    }
                    $count = count($productRating);
                    $rat = $rating / $count;
                    $pendingProducts[$j]['rating'] = (string)round($rat, 1);

                } else {
                    $pendingProducts[$j]['rating'] = "0.0";
                }
                if(Unit::where('id', $pendingProducts[$j]->unit)->exists()){
                    $pendingProducts[$j]['unit'] = Unit::where('id', $pendingProducts[$j]->unit)->first()['unit'];
                }

            }
        }
        $featuredProducts = Products::where('user_id', $request->user()->id)->where('is_active', 'Y')->where('is_deleted', 'N')->where('is_approved', 'Y')->where('featured', 'Y')->get();
        if ($featuredProducts) {
            for ($j = 0; $j < count($featuredProducts); $j++) {
                $featuredProducts[$j]['productView'] = ProductReview::where('product_id', $featuredProducts[$j]['id'])->count();
                $featuredProducts[$j]['mobileNumber'] = User::where('id', $featuredProducts[$j]['user_id'])->first()['phone'];
                $featuredProducts[$j]['attachments'] = ProductAttachment::where('products_id', $featuredProducts[$j]->id)->get();
                for ($k = 0; $k < count($featuredProducts[$j]['attachments']); $k++) {
                    if (!empty($featuredProducts[$j]['attachments'][$k]['image'])) {
                        $featuredProducts[$j]['attachments'][$k]['image'] = getImageUrl($featuredProducts[$j]['attachments'][$k]['image'], 'product-attachments');
                    } else {
                        $featuredProducts[$j]['attachments'][$k]['image'] = getImageUrl('profile.png', 'images12');
                    }
                }
                $featuredProducts[$j]['location'] = User::where('id', $featuredProducts[$j]['user_id'])->first()['location'];
                $featuredProducts[$j]['sellerName'] = User::where('id', $featuredProducts[$j]['user_id'])->first()['username'];
                // $featuredProducts[$j]['currency'] = Currency::where('id',$featuredProducts[$j]['currency_id'])->first()['currency'];
                if($featuredProducts[$j]['price'] !="" && $featuredProducts[$j]['price'] !=null){
                if($featuredProducts[$j]['currency_id'] == 1){
                    if($userCurrency == 1)
                    {
                        $featuredProducts[$j]['price'] = strval($featuredProducts[$j]['price']);
                        $featuredProducts['currency'] = Currency::where('id',$featuredProducts[$j]['currency_id'])->first()['currency'];
                    }
                    else
                    {
                        $featuredProducts[$j]['currency_id'] = $userCurrency;
                        $featuredProducts[$j]['price']= strval(round(($featuredProducts[$j]['price'])/$globalCurrency,2));
                        $featuredProducts[$j]['currency'] = "$";
                    }
                }
                else{

                    if($userCurrency == 2){
                        $featuredProducts[$j]['price'] = strval($featuredProducts[$j]['price']);
                        $featuredProducts[$j]['currency'] = Currency::where('id',$featuredProducts[$j]['currency_id'])->first()['currency'];
                    }
                    else{
                        $featuredProducts[$j]['currency_id'] = $userCurrency;
                        $featuredProducts[$j]['price']= strval(round(($featuredProducts[$i]['price'])*$globalCurrency,2));
                        $featuredProducts[$j]['currency'] = "PKR";
                    }
                }
            }
            else{
                $featuredProducts[$j]['price'] = null;
            }
                if (ProductRating::where('product_id', $featuredProducts[$j]->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $featuredProducts[$j]->id)->avg('rating');
                    $featuredProducts[$j]['rating'] = (string)$productRating;

                    //                    return $productRating;
                    // $rating = 0;
                    // for ($k = 0; $k < count($productRating); $k++) {
                    //     $rating += (int)$productRating[$k]['rating'];
                    // }
                    // $count = count($productRating);
                    // $rat = $rating / $count;
                    // $featuredProducts[$j]['rating'] = round($rat, 1);

                } else {
                    $featuredProducts[$j]['rating'] = "0.0";
                }
                if (Unit::where('id', $featuredProducts[$j]->unit)->exists()) {

                    $featuredProducts[$j]['unit'] = Unit::where('id', $featuredProducts[$j]->unit)->first()['unit'];
                }

            }
        }
        $data =
            [
                'approved' => $approvedProducts,
                'feature' => $featuredProducts,
                'pending' => $pendingProducts,
            ];
        return $this->sendResponse(1, 'success', $data);
    }
    public function sellerDashboard(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $user = User::where('id', Auth::user()->id)->first();
        if($user->role == 4 && $user->seller_type == ""){
            return response()->json(['status'=>1, 'message'=>'success','is_seller' => 'N']);
        }

        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(0, $validator->errors()->first());
        }
        $users = User::where('location', 'like', '%' . $request->location . '%')->pluck('id');
    //    $productIds = Products::whereIn('user_id', $users)->pluck('id');
        $popularIndustries = DB::table('product_rating')
                                ->select(DB::raw('sum(rating)/COUNT(DISTINCT id) as rating, company_id as user_id'))
                                ->whereIn('company_id',$users)
                                ->where('product_id',null)
                                ->groupBy('company_id')
                                ->orderBy('rating', 'DESC')
                                ->limit(5)
								 ->get();
        if ($popularIndustries) {
            $popularIndustries = $popularIndustries->map(function ($popularIndustries) {
                $sellerDetails = User::Select('username', 'image', 'location', 'phone')->where('id', $popularIndustries->user_id)->first();
                if (empty($sellerDetails['image'])) {
                    $sellerDetails['image'] = 'xyz';
                }
                $file = public_path() . '/images/profile-pic/' . $sellerDetails['image'];
                if (!empty($sellerDetails->image) && file_exists($file)) {
                    $popularIndustries->companyImage = getImageUrl($sellerDetails->image, 'images');
                } else {
                    $popularIndustries->companyImage  = getImageUrl('profile.png', 'images12');
                }
                $popularIndustries->location = isset($sellerDetails->location) ? $sellerDetails->location : '';
                $popularIndustries->companyName = isset($sellerDetails->username) ? $sellerDetails->username : '';
                $popularIndustries->phone = isset($sellerDetails->phone) ? $sellerDetails->phone : '';
				$popularIndustries->rating= (string)round($popularIndustries->rating, 1);

                return $popularIndustries;
            });
        }
//        return  $popularIndustries;

        $popularProducts = Products::where('featured', 'Y')->where('is_active','Y')->whereIn('user_id', $users)->where('is_deleted', 'N')->orderBy('id', 'DESC')->groupBy('id')->limit(10)->get();
        if ($popularProducts) {
           foreach($popularProducts as $popularProduct){
                // $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
                if($popularProduct->price !="" && $popularProduct->price!=null){
                    if($popularProduct->currency_id == 1){
                        if($userCurrency == 1)
                        {
                            $popularProduct['price'] = strval($popularProduct->price);
                            $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
                        }
                        else
                        {
                            $popularProduct['currency_id'] = $userCurrency;
                            $popularProduct['price']= strval(round(($popularProduct->price)/$globalCurrency,2));
                            $popularProduct['currency'] = "$";
                        }
                    }
                    else{
                        if($userCurrency == 2){
                            $popularProduct['price'] = strval($popularProduct->price);
                            $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
                        }
                        else{
                            $popularProduct['currency_id'] = $userCurrency;
                        $popularProduct['price']= strval(round(($popularProduct->price)*$globalCurrency,2));
                        $popularProduct['currency'] = "PKR";
                        }
                    }
                }
                else{
                    $popularProduct['price']= null;
                }
                if (ProductRating::where('product_id', $popularProduct->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $popularProduct->id)->avg('rating');
                    $popularProduct['rating'] = (string)$productRating;
                //   $rat=$productRating->sum()/$productRating->count();

                //     $popularProduct['rating'] = round($rat, 1);

                } else {
                    $popularProduct['rating'] = "0.0";
                }

                if (Unit::where('id', $popularProduct->unit)->exists()) {

                    $popularProduct['unit'] = Unit::where('id', $popularProduct->unit)->first()['unit'];
                } else {
                    $popularProduct['unit'] = '';
                }

                $sellerDetails = User::Select('username', 'image', 'location', 'phone')->where('id', $popularProduct->user_id)->first();

                if (ProductAttachment::where('products_id', $popularProduct->id)->exists()) {
					 $productsAttachments = ProductAttachment::where('products_id', $popularProduct->id)->first()['image'];
					     $file = public_path() . '/images/product-attachments/' . $productsAttachments;

                    if (file_exists($file)) {

                            $popularProduct['companyImage'] = getImageUrl($productsAttachments, 'product-attachments');
                    }
                    else {
                            $popularProduct['companyImage'] = getImageUrl('profile.png', 'images12');
                        }
                } else {
                    $popularProduct['companyImage'] = getImageUrl('profile.png', 'images12');
                }

			//  if (empty($sellerDetails['image'])) {
            //         $sellerDetails['image'] = 'xyz';
            //     }
            //     $file = public_path() . '/images/profile-pic/' . $sellerDetails->image;
            //     if (!empty($sellerDetails->image) && file_exists($file)) {
            //         $popularProduct['companyImage'] = getImageUrl($sellerDetails->image, 'images');
            //     } else {
            //         $popularProduct['companyImage'] = getImageUrl('profile.png', 'images12');
            //     }


                $popularProduct['location'] = isset($sellerDetails->location) ? $sellerDetails->location : '';
                $popularProduct['companyName'] = isset($sellerDetails->username) ? $sellerDetails->username : '';
                $popularProduct['phone'] = isset($sellerDetails->phone) ? $sellerDetails->phone : '';
                // return $popularProduct; 
            }
        }

        $recentAdded = Products::where('is_approved','Y')->where('is_active','Y')->where('is_deleted', 'N')->whereIn('user_id', $users)->orderBy('id', 'DESC')->groupBy('id')->limit(5)->get();
        if ($recentAdded) {
            foreach($recentAdded as $recentlyAdded){
                // $recentlyAdded['currency'] = Currency::where('id',$recentlyAdded->currency_id)->first()['currency'];
                if($recentlyAdded->price != "" && $recentlyAdded->price!=null){
                if($recentlyAdded->currency_id == 1){
                    if($userCurrency == 1)
                    {
                        $recentlyAdded['price'] = strval($recentlyAdded->price);
                        $recentlyAdded['currency'] = Currency::where('id',$recentlyAdded->currency_id)->first()['currency'];
                    }
                    else
                    {
                        $recentlyAdded['currency_id'] = $userCurrency;
                        $recentlyAdded['price']= strval(round(($recentlyAdded->price)/$globalCurrency,2));
                        $recentlyAdded['currency'] = "$";
                    }
                }
                else{
                    if($userCurrency == 2){
                        $recentlyAdded['price'] = strval($userCurrency);
                        $recentlyAdded['currency'] = Currency::where('id',$recentlyAdded->currency_id)->first()['currency'];
                    }
                    else{
                        $recentlyAdded['currency_id'] = $userCurrency;
                    $recentlyAdded['price']= strval(round(($recentlyAdded->price)*$globalCurrency,2));
                    $recentlyAdded['currency'] = "PKR";
                    }
                }
            }
            else{
                $recentlyAdded['price'] = null;
            }
                if (ProductRating::where('product_id', $recentlyAdded->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $recentlyAdded->id)->pluck('rating');
					$rat=$productRating->sum()/$productRating->count();

                    $recentlyAdded['rating'] = (string)round($rat, 1);

                } else {
                    $recentlyAdded['rating'] = "0.0";
                }

				if (Unit::where('id', $recentlyAdded->unit)->exists()) {

                $recentlyAdded['unit'] = Unit::where('id', $recentlyAdded->unit)->first()['unit'];
            } else {
                $recentlyAdded['unit'] = '';
            }



                if (User::where('id', $recentlyAdded->user_id)->exists()) {
                    $sellerDetails = User::Select('username', 'image', 'location', 'phone')->where('id', $recentlyAdded->user_id)->first();
                }



				 if (ProductAttachment::where('products_id', $recentlyAdded->id)->exists()) {
					 $productsAttachments = ProductAttachment::where('products_id', $recentlyAdded->id)->first()['image'];
					     $file = public_path() . '/images/product-attachments/' . $productsAttachments;

			 if (file_exists($file)) {

                    $recentlyAdded['companyImage'] = getImageUrl($productsAttachments, 'product-attachments');
			 }
			 else {
                    $recentlyAdded['companyImage'] = getImageUrl('profile.png', 'images12');
                }
			 } else {
                    $recentlyAdded['companyImage'] = getImageUrl('profile.png', 'images12');
                }



			/*
                if (empty($sellerDetails['image'])) {
                    $sellerDetails['image'] = 'xyz';
                }

                $file = public_path() . '/images/profile-pic/' . $sellerDetails->image;

			 if (!empty($sellerDetails->image) && file_exists($file)) {
                    $recentlyAdded['companyImage'] = getImageUrl($sellerDetails->image, 'images');
                } else {
                    $recentlyAdded['companyImage'] = getImageUrl('profile.png', 'images12');
                }
				*/
                $recentlyAdded['location'] = isset($sellerDetails->location) ? $sellerDetails->location : '';
                $recentlyAdded['companyName'] = isset($sellerDetails->username) ? $sellerDetails->username : '';
                $recentlyAdded['phone'] = isset($sellerDetails->phone) ? $sellerDetails->phone : '';
                // return $recentlyAdded;
            }
        }

		//Not Used. Delete in futures
        $recentSearches = Leads::orderBy('id', 'DESC')->where('is_approved','Y')->whereIn('seller_id', $users)->limit(10)->get();
        if ($recentSearches) {
          foreach($recentSearches as $recentSearch){
                $products = Products::where('id', $recentSearch->product_id)->select('id', 'products_name', 'category', 'sub_category', 'price', 'description', 'unit', 'user_id','currency_id')->first();
                if($products){
                if(Unit::where('id',$products['unit'])->exists()){
                    $recentSearch['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                }
                if($products->price !="" && $products->price !=null){
                if($products->currency_id == 1){
                    if($userCurrency == 1)
                    {
                        $recentSearch['price'] = strval($products->price);
                        $recentSearch['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    }
                    else
                    {
                        $recentSearch['currency_id'] = $userCurrency;
                        $recentSearch['price']= strval(round(($products->price/$globalCurrency),2));
                        $recentSearch['currency'] = "$";
                    }
                }
                else{
                    if($userCurrency == 2){
                        $recentSearch['price'] = strval($products->price);
                        $recentSearch['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    }
                    else{
                        $recentSearch['currency_id'] = $userCurrency;
                        $recentSearch['price']= strval(round(($products->price*$globalCurrency),2));
                        $recentSearch['currency'] = "PKR";
                    }
                }
            }
            else{
                $recentSearch['price'] = null;
            }
			    $sellerDetails = User::Select('username', 'image', 'location', 'phone')->where('id', $recentSearch->user_id)->first();
                $file = public_path() . '/images/profile-pic/' . $sellerDetails->image;
                if (!empty($sellerDetails->image) && file_exists($file)) {
                    $recentSearch['companyImage'] = getImageUrl($sellerDetails->image, 'images');
                } else {
                    $recentSearch['companyImage'] = getImageUrl('profile.png', 'images12');
                }
                if (ProductRating::where('product_id', $products->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $products->id)->pluck('rating');
                //    return $productRating;
                  $rat=$productRating->sum()/$productRating->count();

                    $recentlyAdded['rating'] = (string)round($rat, 1);

                } else {
                    $recentSearch['rating'] = "0.0";
                }
                $recentSearch['location'] = isset($sellerDetails->location) ? $sellerDetails->location : '';
                $recentSearch['companyName'] = isset($sellerDetails->username) ? $sellerDetails->username : '';
                $recentSearch['phone'] = isset($sellerDetails->phone) ? $sellerDetails->phone : '';
                $recentSearch['productName'] = $products->products_name;
                $recentSearch['category'] = $products->category;
                $recentSearch['sub_category'] = $products->sub_category;
                // $recentSearch['price'] = $products->price;
                $recentSearch['description'] = $products->description;
                }

                // $recentSearch['unit'] = $products->unit;
                // $recentSearch['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                // return $recentSearch;
            }
        }
        $data =
            ['popularIndustries' => $popularIndustries,
                'popularProducts' => $popularProducts,
                'recentProducts' => $recentAdded,
                'recentSearches' => $recentSearches
            ];
        // return $this->sendResponse(1, 'success', $data);
        return response()->json(['status'=>1, 'message'=>'success', 'is_seller'=>'Y', 'result'=>$data]);

    }
    public function dashboardSearch(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
            'product_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(0, $validator->errors()->first());
        }
        $users = User::where('is_deleted', 'N')->where('location', $request->location)->pluck('id');
        $searchProduct = Products::where('products_name', 'like', '%' . $request->product_name . '%')->orWhere('category', 'like', '%' . $request->product_name . '%')->where('is_active','Y')->where('is_approved','Y')->pluck('id');
        $popularIndustries = ProductRating::orderBy('rating', 'DESC')->select('id', 'rating', 'product_id', 'user_id', 'comment')->whereIn('user_id', $users)->whereIn('product_id', $searchProduct)->groupBy('user_id')->limit(10)->get();
        $popularIndustries = $popularIndustries->map(function ($popularIndustries) {
            $sellerDetails = User::Select('username', 'image', 'location')->where('id', $popularIndustries->user_id)->first();
            if (empty($sellerDetails['image'])) {
                $sellerDetails['image'] = 'xyz';
            }
            $file = public_path() . '/images/profile-pic/' . $sellerDetails['image'];
            if (!empty($sellerDetails->image) && file_exists($file)) {
                $popularIndustries['companyImage'] = getImageUrl($sellerDetails->image, 'images');
            } else {
                $popularIndustries['companyImage'] = getImageUrl('profile.png', 'images12');
            }
            $popularIndustries['location'] = isset($sellerDetails->location) ? $sellerDetails->location : '';
            $popularIndustries['companyName'] = isset($sellerDetails->username) ? $sellerDetails->username : '';
            return $popularIndustries;
        });
        $popularProducts = Products::where('featured', 'Y')->where('is_deleted', 'N')->where('is_active','Y')->where('is_approved','Y')->whereIn('user_id', $users)->where('products_name', 'like', '%' . $request->product_name . '%')->orderBy('id', 'DESC')->groupBy('id')->limit(10)->get();
        foreach($popularProducts as $popularProduct){
            // $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
            if($popularProduct->price !="" && $popularProduct->price!=null){
            if($popularProduct->currency_id == 1){
                if($userCurrency == 1)
                {
                    $popularProduct['price'] = strval($popularProduct->price);
                    $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
                }
                else
                {
                    $popularProduct['currency_id'] = $userCurrency;
                    $popularProduct['price']= strval(round(($popularProduct->price/$globalCurrency),2));
                    $popularProduct['currency'] = "$";
                }
            }
            else{
                if($userCurrency == 2){
                    $popularProduct['price'] = strval($popularProduct->price);
                    $popularProduct['currency'] = Currency::where('id',$popularProduct->currency_id)->first()['currency'];
                }
                else{
                    $popularProduct['currency_id'] = $userCurrency;
                $popularProduct['price']= strval(round(($popularProduct->price*$globalCurrency),2));
                $popularProduct['currency'] = "PKR";
                }
            }
        }
        else{
            $popularProduct['price'] = null;
        }
            if (ProductRating::where('product_id', $popularProduct->id)->exists()) {
                $productRating = ProductRating::where('product_id', $popularProduct->id)->get();
                $rating = 0;
                for ($k = 0; $k < count($productRating); $k++) {
                    $rating += (int)$productRating[$k]['rating'];
                }
                $count = count($productRating);
                $rat = $rating / $count;
                $popularProduct['rating'] = (string)round($rat, 1);

            } else {
                $popularProduct['rating'] = "0.0";
            }
            if(Unit::where('id', $popularProduct->unit)->exists()){
                $popularProduct['unit'] = Unit::where('id', $popularProduct->unit)->first()['unit'];
            }
            $sellerDetails = User::Select('username', 'image', 'location')->where('id', $popularProduct->user_id)->first();
            if (empty($sellerDetails['image'])) {
                $sellerDetails['image'] = 'xyz';
            }
            $file = public_path() . '/images/profile-pic/' . $sellerDetails->image;
            if (!empty($sellerDetails->image) && file_exists($file)) {
                $popularProduct['companyImage'] = getImageUrl($sellerDetails->image, 'images');
            } else {
                $popularProduct['companyImage'] = getImageUrl('profile.png', 'images12');
            }
            $popularProduct['location'] = isset($sellerDetails->location) ? $sellerDetails->location : '';
            $popularProduct['companyName'] = isset($sellerDetails->username) ? $sellerDetails->username : '';
            // return $popularProduct;
        }
        $recentSearches =
//            RecentSearch::where('user_id',Auth::user()->id)->groupBy('product_id')->orderBy('id','desc')->limit(10)->get();
        Leads::orderBy('id', 'DESC')->where('is_approved','Y')->whereIn('user_id', $users)->whereIn('product_id', $searchProduct)->limit(10)->get();
        foreach($recentSearches as $recentSearch) {
            $products = Products::Select('id', 'products_name', 'category', 'sub_category', 'price', 'description', 'unit', 'user_id','currency_id')->where('id', $recentSearch->product_id)->first();
            if (Unit::where('id', $products->unit)->exists()) {
                $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
            }
            if($products->price !="" && $products->price!=null){
            if($products->currency_id == 1){
                if($userCurrency == 1)
                {
                    $recentSearch['price'] = strval($products->price);
                    $recentSearch['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                }
                else
                {
                    $recentSearch['currency_id'] = $userCurrency;
                    $recentSearch['price']= strval(round($products->price/$globalCurrency,2));
                    $recentSearch['currency'] = "$";
                }
            }
            else{
                return "inelse";
                if($userCurrency== 2){
                    $recentSearch['price'] = strval($products->price);
                    $recentSearch['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                }
                else{
                    $recentSearch['currency_id'] = $userCurrency;
                $recentSearch['price']= strval(round($products->price*$globalCurrency,2));
                $recentSearch['currency'] = "PKR";
                }
            }
        }
        else{
            $recentSearch['price'] = null;
        }
            $sellerDetails = User::Select('username', 'image', 'location')->where('id', $recentSearch->user_id)->first();
            $file = public_path() . '/images/profile-pic/' . $sellerDetails->image;
            if (!empty($sellerDetails->image) && file_exists($file)) {
                $recentSearch['companyImage'] = getImageUrl($sellerDetails->image, 'images');
            } else {
                $recentSearch['companyImage'] = getImageUrl('profile.png', 'images12');
            }
            $recentSearch['location'] = isset($sellerDetails->location) ? $sellerDetails->location : '';
            $recentSearch['companyName'] = isset($sellerDetails->username) ? $sellerDetails->username : '';
            $recentSearch['productName'] = $products->products_name;
            $recentSearch['category'] = $products->category;
            $recentSearch['sub_category'] = $products->sub_category;
            // $recentSearch['price'] = $products->price;
            $recentSearch['description'] = $products->description;
            $recentSearch['unit'] = $products->unit;
            // return $recentSearch;
        }
        $data =
            ['popularIndustries' => $popularIndustries,
                'popularProducts' => $popularProducts,
                'recentSearches' => $recentSearches];
        return $this->sendResponse(1, 'success', $data);
    }
    public function leadsFilter(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'lead_type' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        $where_array = [];
        if ($request->unit_id) {
            $where_array['leads.unit_id'] = $request->unit_id;
        }
        if ($request->category_id) {
            $where_array['leads.category_id'] = $request->category_id;
        }
        if ($request->price) {
            $where_array['leads.bid'] = $request->price;
        }
        if ($request->lead_type == 'export') {
            if ($request->sub_category_id) {
                $subCategory = SubCategory::where('id', $request->sub_category_id)->pluck('sub_category');
                $productId = Leads::where($where_array)->where('is_approved','Y')->pluck('product_id');
                $productIds = Products::whereIn('id', $productId)->whereIn('sub_category', $subCategory)->pluck('id');
                $userRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->whereIn('product_id', $productIds)->where('is_contacted', 'N')->get();
                if ($userRequirements) {
                    for ($i = 0; $i < count($userRequirements); $i++) {
                        $userRequirements[$i]['product'] = Products::where('id', $userRequirements[$i]['product_id'])->first()['products_name'];
                        $userRequirements[$i]['productCategory'] = ProductCategory::where('id', $userRequirements[$i]['category_id'])->first()['category'];
                        if (Unit::where('id', $userRequirements[$i]['unit_id'])->exists()) {
                            $userRequirements[$i]['unit'] = Unit::where('id', $userRequirements[$i]['unit_id'])->first()['unit'];
                        }
                    }
                }
                $urgentRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->whereIn('product_id', $productIds)->where('is_contacted', 'N')->where('is_urgent', 'Y')->get();
                if ($urgentRequirements) {
                    for ($k = 0; $k < count($urgentRequirements); $k++) {
                        $urgentRequirements[$k]['product'] = Products::where('id', $urgentRequirements[$k]->product_id)->first()['products_name'];
                        $urgentRequirements[$k]['productCategory'] = ProductCategory::where('id', $urgentRequirements[$k]->category_id)->first()['category'];
                        if (Unit::where('id', $urgentRequirements[$k]->unit_id)->exists()) {
                            $urgentRequirements[$k]['unit'] = Unit::where('id', $urgentRequirements[$k]->unit_id)->first()['unit'];
                        }
                    }

                }
                $latestRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->whereIn('product_id', $productIds)->where('is_contacted', 'N')->whereDate('created_at', '>', Carbon::now()->subDays(1))->get();
                if ($latestRequirements) {
                    for ($l = 0; $l < count($latestRequirements); $l++) {
                        $latestRequirements[$l]['product'] = Products::where('id', $latestRequirements[$l]->product_id)->first()['products_name'];
                        $latestRequirements[$l]['productCategory'] = ProductCategory::where('id', $latestRequirements[$l]->category_id)->first()['category'];
                        if (Unit::where('id', $latestRequirements[$l]->unit_id)->exists()) {
                            $latestRequirements[$l]['unit'] = Unit::where('id', $latestRequirements[$l]->unit_id)->first()['unit'];
                        }
                    }

                }
                $pastRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->whereIn('product_id', $productIds)->whereDate('created_at', '!=', date('Y-m-d'))->where('is_contacted', 'N')->get();
                if ($pastRequirements) {
                    for ($m = 0; $m < count($pastRequirements); $m++) {
                        $pastRequirements[$m]['product'] = Products::where('id', $pastRequirements[$m]->product_id)->first()['products_name'];
                        $pastRequirements[$m]['productCategory'] = ProductCategory::where('id', $pastRequirements[$m]->category_id)->first()['category'];
                        if (Unit::where('id', $pastRequirements[$m]->unit_id)->exists()) {
                            $pastRequirements[$m]['unit'] = Unit::where('id', $pastRequirements[$m]->unit_id)->first()['unit'];
                        }
                    }

                }
                $data =
                    [
                        'latest' => $latestRequirements,
                        'all' => $userRequirements,
                        'urgent' => $urgentRequirements,
                        'past' => $pastRequirements
                    ];
                return $this->sendResponse(1, 'success', $data);
            } else {
                $userRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->where($where_array)->where('is_contacted', 'N')->get();
                if ($userRequirements) {
                    for ($i = 0; $i < count($userRequirements); $i++) {
                        $userRequirements[$i]['product'] = Products::where('id', $userRequirements[$i]['product_id'])->first()['products_name'];
                        $userRequirements[$i]['productCategory'] = ProductCategory::where('id', $userRequirements[$i]['category_id'])->first()['category'];
						$unit=$userRequirements[$i]['unit_id'];
                        if (Unit::where('id', $unit)->exists()) {
                            $userRequirements[$i]['unit'] = Unit::where('id', $unit)->first()['unit'];
                        }
                    }
                }
                $urgentRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->where($where_array)->where('is_contacted', 'N')->where('is_urgent', 'Y')->get();
                if ($urgentRequirements) {
                    for ($k = 0; $k < count($urgentRequirements); $k++) {
                        $urgentRequirements[$k]['product'] = Products::where('id', $urgentRequirements[$k]->product_id)->first()['products_name'];
                        $urgentRequirements[$k]['productCategory'] = ProductCategory::where('id', $urgentRequirements[$k]->category_id)->first()['category'];
                        if (Unit::where('id', $urgentRequirements[$k]->unit_id)->exists()) {
                            $urgentRequirements[$k]['unit'] = Unit::where('id', $urgentRequirements[$k]->unit_id)->first()['unit'];
                        }
                    }

                }
                $latestRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where($where_array)->where('is_approved','Y')->where('is_contacted', 'N')->whereDate('created_at', '>', Carbon::now()->subDays(1))->get();
                if ($latestRequirements) {
                    for ($l = 0; $l < count($latestRequirements); $l++) {
                        $latestRequirements[$l]['product'] = Products::where('id', $latestRequirements[$l]->product_id)->first()['products_name'];
                        $latestRequirements[$l]['productCategory'] = ProductCategory::where('id', $latestRequirements[$l]->category_id)->first()['category'];
                        if (Unit::where('id', $latestRequirements[$l]->unit_id)->exists()) {
                            $latestRequirements[$l]['unit'] = Unit::where('id', $latestRequirements[$l]->unit_id)->first()['unit'];
                        }
                    }

                }
                $pastRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where($where_array)->where('is_approved','Y')->whereDate('created_at', '!=', date('Y-m-d'))->where('is_contacted', 'N')->get();
                if ($pastRequirements) {
                    for ($m = 0; $m < count($pastRequirements); $m++) {
                        $pastRequirements[$m]['product'] = Products::where('id', $pastRequirements[$m]->product_id)->first()['products_name'];
                        $pastRequirements[$m]['productCategory'] = ProductCategory::where('id', $pastRequirements[$m]->category_id)->first()['category'];
                        if (Unit::where('id', $pastRequirements[$m]->unit_id)->exists()) {
                            $pastRequirements[$m]['unit'] = Unit::where('id', $pastRequirements[$m]->unit_id)->first()['unit'];
                        }
                    }

                }
                $data =
                    [
                        'latest' => $latestRequirements,
                        'all' => $userRequirements,
                        'urgent' => $urgentRequirements,
                        'past' => $pastRequirements
                    ];
                return $this->sendResponse(1, 'success', $data);
            }
        } else if ($request->lead_type == 'consumed') {
            if ($request->sub_category_id) {
                $subCategory = SubCategory::where('id', $request->sub_category_id)->pluck('sub_category');
                $productId = Leads::where($where_array)->where('is_approved','Y')->pluck('product_id');
                $productIds = Products::whereIn('id', $productId)->whereIn('sub_category', $subCategory)->pluck('id');
                $userRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->whereIn('product_id', $productIds)->where('is_contacted', 'Y')->get();
                if ($userRequirements) {
                    for ($i = 0; $i < count($userRequirements); $i++) {
                        $userRequirements[$i]['product'] = Products::where('id', $userRequirements[$i]['product_id'])->first()['products_name'];
                        $userRequirements[$i]['productCategory'] = ProductCategory::where('id', $userRequirements[$i]['category_id'])->first()['category'];
                        if (Unit::where('id', $userRequirements[$i]['unit_id'])->exists()) {
                            $userRequirements[$i]['unit'] = Unit::where('id', $userRequirements[$i]['unit_id'])->first()['unit'];
                        }
                    }
                }
                $urgentRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->whereIn('product_id', $productIds)->where('is_contacted', 'Y')->where('is_urgent', 'Y')->get();
                if ($urgentRequirements) {
                    for ($k = 0; $k < count($urgentRequirements); $k++) {
                        $urgentRequirements[$k]['product'] = Products::where('id', $urgentRequirements[$k]->product_id)->first()['products_name'];
                        $urgentRequirements[$k]['productCategory'] = ProductCategory::where('id', $urgentRequirements[$k]->category_id)->first()['category'];
                        if (Unit::where('id', $urgentRequirements[$k]->unit_id)->exists()) {
                            $urgentRequirements[$k]['unit'] = Unit::where('id', $urgentRequirements[$k]->unit_id)->first()['unit'];
                        }
                    }

                }
                $latestRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->whereIn('product_id', $productIds)->where('is_contacted', 'Y')->whereDate('created_at', '>', Carbon::now()->subDays(1))->get();
                if ($latestRequirements) {
                    for ($l = 0; $l < count($latestRequirements); $l++) {
                        $latestRequirements[$l]['product'] = Products::where('id', $latestRequirements[$l]->product_id)->first()['products_name'];
                        $latestRequirements[$l]['productCategory'] = ProductCategory::where('id', $latestRequirements[$l]->category_id)->first()['category'];
                        if (Unit::where('id', $latestRequirements[$l]->unit_id)->exists()) {
                            $latestRequirements[$l]['unit'] = Unit::where('id', $latestRequirements[$l]->unit_id)->first()['unit'];
                        }
                    }

                }
                $pastRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->whereIn('product_id', $productIds)->whereDate('created_at', '!=', date('Y-m-d'))->where('is_contacted', 'Y')->get();
                if ($pastRequirements) {
                    for ($m = 0; $m < count($pastRequirements); $m++) {
                        $pastRequirements[$m]['product'] = Products::where('id', $pastRequirements[$m]->product_id)->first()['products_name'];
                        $pastRequirements[$m]['productCategory'] = ProductCategory::where('id', $pastRequirements[$m]->category_id)->first()['category'];
                        if (Unit::where('id', $pastRequirements[$m]->unit_id)->exists()) {
                            $pastRequirements[$m]['unit'] = Unit::where('id', $pastRequirements[$m]->unit_id)->first()['unit'];
                        }
                    }

                }
                $data =
                    [
                        'latest' => $latestRequirements,
                        'all' => $userRequirements,
                        'urgent' => $urgentRequirements,
                        'past' => $pastRequirements
                    ];
                return $this->sendResponse(1, 'success', $data);
            } else {
                $userRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->where($where_array)->where('is_contacted', 'Y')->get();
                if ($userRequirements) {
                    for ($i = 0; $i < count($userRequirements); $i++) {
                        $userRequirements[$i]['product'] = Products::where('id', $userRequirements[$i]['product_id'])->first()['products_name'];
                        $userRequirements[$i]['productCategory'] = ProductCategory::where('id', $userRequirements[$i]['category_id'])->first()['category'];
                        if (Unit::where('id', $userRequirements[$i]['unit_id'])->exists()) {
                            $userRequirements[$i]['unit'] = Unit::where('id', $userRequirements[$i]['unit_id'])->first()['unit'];
                        }
                    }
                }
                $urgentRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->where($where_array)->where('is_contacted', 'Y')->where('is_urgent', 'Y')->get();
                if ($urgentRequirements) {
                    for ($k = 0; $k < count($urgentRequirements); $k++) {
                        $urgentRequirements[$k]['product'] = Products::where('id', $urgentRequirements[$k]->product_id)->first()['products_name'];
                        $urgentRequirements[$k]['productCategory'] = ProductCategory::where('id', $urgentRequirements[$k]->category_id)->first()['category'];
                        if (Unit::where('id', $urgentRequirements[$k]->unit_id)->exists()) {
                            $urgentRequirements[$k]['unit'] = Unit::where('id', $urgentRequirements[$k]->unit_id)->first()['unit'];
                        }
                    }

                }
                $latestRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where($where_array)->where('is_approved','Y')->where('is_contacted', 'Y')->whereDate('created_at', '>', Carbon::now()->subDays(1))->get();
                if ($latestRequirements) {
                    for ($l = 0; $l < count($latestRequirements); $l++) {
                        $latestRequirements[$l]['product'] = Products::where('id', $latestRequirements[$l]->product_id)->first()['products_name'];
                        $latestRequirements[$l]['productCategory'] = ProductCategory::where('id', $latestRequirements[$l]->category_id)->first()['category'];
                        if (Unit::where('id', $latestRequirements[$l]->unit_id)->exists()) {
                            $latestRequirements[$l]['unit'] = Unit::where('id', $latestRequirements[$l]->unit_id)->first()['unit'];
                        }
                    }

                }
                $pastRequirements = Leads::Select('quantity', 'product_id', 'category_id', 'unit_id', 'id')->where('is_approved','Y')->where($where_array)->whereDate('created_at', '!=', date('Y-m-d'))->where('is_contacted', 'Y')->get();
                if ($pastRequirements) {
                    for ($m = 0; $m < count($pastRequirements); $m++) {
                        $pastRequirements[$m]['product'] = Products::where('id', $pastRequirements[$m]->product_id)->first()['products_name'];
                        $pastRequirements[$m]['productCategory'] = ProductCategory::where('id', $pastRequirements[$m]->category_id)->first()['category'];
                        if (Unit::where('id', $pastRequirements[$m]->unit_id)->exists()) {
                            $pastRequirements[$m]['unit'] = Unit::where('id', $pastRequirements[$m]->unit_id)->first()['unit'];
                        }
                    }

                }
                $data =
                    [
                        'latest' => $latestRequirements,
                        'all' => $userRequirements,
                        'urgent' => $urgentRequirements,
                        'past' => $pastRequirements
                    ];
                return $this->sendResponse(1, 'success', $data);
            }
        }
        return $this->sendError(false, 'Lead type must be either export ot consumed.Please type either from these options');

    }
    public function companyProfile(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        $product = [];
        $category = [];
        // $aboutUs = '';
        $rating = [];
        $categoryProducts = [];
        $sellersInformation = '';
        if (Products::where('user_id', $request->user()->id)->exists()) {
            $product = Products::where('user_id', $request->user()->id)->where('is_deleted', 'N')->where('is_approved', 'Y')->get();
//          dd($product);
            foreach($product as $products) {
                $sellerImage = User::where('id', $products->user_id)->first()['image'];
                $file = public_path() . '/images/profile-pic/' . $sellerImage;
                if ($sellerImage && file_exists($file)) {
                    $products['image'] = getImageUrl($sellerImage, 'images');
                } else {
                    $products['image'] = getImageUrl('profile.png', 'images12');
                }
                // $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                if($products['price'] != "" && $products['price'] !=null){
                if($products['currency_id'] == 1){
                    if($userCurrency == 1)
                    {
                        $products['price'] = strval($products['price']);
                        $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                    }
                    else
                    {
                        $products['currency_id'] = $userCurrency;
                        $products['price']= strval(round(($products['price'])/$globalCurrency,2));
                        $products['currency'] = "$";
                    }
                }
                else{

                    if($userCurrency == 2){
                        $products['price'] = strval($products['price']);
                        $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                    }
                    else{
                        $products['currency_id'] = $userCurrency;
                        $products['price']= strval(round(($products['price'])*$globalCurrency,2));
                        $products['currency'] = "PKR";
                    }
                }
            }
            else{
                $products['price'] = null;
            }
                if (ProductRating::where('product_id', $products->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $products->id)->get();
                    $rating = 0;
                    for ($k = 0; $k < count($productRating); $k++) {
                        $rating += (int)$productRating[$k]['rating'];
                    }
                    $count = count($productRating);
                    $rat = $rating / $count;
                    $products['rating'] = (string)round($rat, 1);
                    $products['ratingCount'] = $count;

                } else {
                    $products['rating'] = "0.0";
                    $products['ratingCount'] = 0;
                }
                if (Unit::where('id', $products->unit)->exists()) {
                    $products['productUnit'] = Unit::where('id', $products->unit)->first()['unit'];
                }
                $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                $products['sellerLocation'] = User::where('id', $products->user_id)->first()['address'];
                $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                $products['seller_type'] = User::where('id', $products->user_id)->first()['seller_type'];
                $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                // return $products;
            }
            $category = ProductCategory::where('is_deleted', 'N')->pluck('category');
            //            $categoryProducts = ProductCategory::where('id',$request->categoryId)->first()['category'];
            $categoryProducts = Products::whereIn('category', $category)->where('user_id', $request->user()->id)->where('is_approved', 'Y')->where('is_deleted', 'N')->where('is_active', 'Y')->get();
             //            return [$category[0]['category'],$categoryProducts ];
            foreach($categoryProducts as $products) {
                $sellerImage = User::where('id', $products->user_id)->first()['image'];
                if ($sellerImage) {
                    $products['image'] = getImageUrl($sellerImage, 'images');
                } else {
                    $products['image'] = getImageUrl('profile.png', 'images12');
                }
                $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                // $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                if($products['price'] != "" && $products['price'] !=null){
                if($products['currency_id'] == 1){
                    if($userCurrency == 1)
                    {
                        $products['price'] = strval($products['price']);
                        $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                    }
                    else
                    {
                        $products['currency_id'] = $userCurrency;
                        $products['price']= strval(round(($products['price'])/$globalCurrency,2));
                        $products['currency'] = "$";
                    }
                }
                else{

                    if($userCurrency == 2){
                        $products['price'] = strval($products['price']);
                        $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                    }
                    else{
                        $products['currency_id'] = $userCurrency;
                        $products['price']= strval(round(($products['price'])*$globalCurrency,2));
                        $products['currency'] = "PKR";
                    }
                }
            }
            else{
                $products['price'] = null;
            }
                if (ProductRating::where('product_id', $products->id)->exists()) {
                    $productRating = ProductRating::where('product_id', $products->id)->get();
                    $rating = 0;
                    for ($k = 0; $k < count($productRating); $k++) {
                        $rating += (int)$productRating[$k]['rating'];
                    }
                    $count = count($productRating);
                    $rat = $rating / $count;
                    $products['rating'] = (string)round($rat, 1);

                } else {
                    $products['rating'] = "0.0";
                }
                if (Unit::where('id', $products->unit)->exists()) {
                    $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                }
                $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                $products['sellerLocation'] = User::where('id', $products->user_id)->first()['address'];
                $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                $products['seller_type'] = User::where('id', $products->user_id)->first()['seller_type'];
                $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                // return $products;
            }
            if (ProductRating::where('user_id', $request->user()->id)->exists()) {
                $rating = ProductRating::where('user_id', $request->user()->id)->get();
                $rating = $rating->map(function ($products) {
                    $products['name'] = User::where('id', $products->user_id)->first()['username'];
                    $sellerImage['image'] = User::where('id', $products->user_id)->first()['image'];
                    if (empty($sellerImage['image'])) {
                        $sellerImage['image'] = 'abc';
                    }
                    $file = public_path() . '/images/profile-pic/' . $sellerImage['image'];
                    if ($sellerImage['image'] && file_exists($file)) {
                        $products['image'] = getImageUrl($sellerImage['image'], 'images');
                    } else {
                        $products['image'] = getImageUrl('profile.png', 'images12');
                    }
                    //                    $products['attachments'] = getImageUrl($sellerImage, 'images');
                    return $products;
                });
            } else {
                $rating = [];
            }
        }
        $sellersInformation = User::Select('username', 'email', 'phone', 'image', 'location', 'address','seller_type')->where('id', $request->user()->id)->first();
        if (empty($sellersInformation['image'])) {
            $sellersInformation['image'] = 'xyz';
        }

        $file = public_path() . '/images/profile-pic/' . $sellersInformation['image'];
        if ($sellersInformation['image'] && file_exists($file)) {
            $sellersInformation['image'] = getImageUrl($sellersInformation['image'], 'images');
        } else {
            $sellersInformation['image'] = getImageUrl('profile.png', 'images12');
        }

        if (ProductRating::where('company_id', $request->user()->id)->where('product_id',null)->exists()) {

            $productRating = ProductRating::where('company_id', $request->user()->id)->where('product_id',null)->pluck('rating');
            $array = json_decode(json_encode($productRating), true);
            $sum = array_sum($array);
            $count = count($array);
            $rat = $sum / $count;
            $sellersInformation['rating'] = (string)round($rat, 1);
            $sellersInformation['ratingCount'] = $count;

        } else {
            // dd('hit');
            $sellersInformation['rating'] = "0.0";
            $sellersInformation['ratingCount'] = 0;
        }

        $images = AboutUsImage::where('seller_id', $request->user()->id)->get();
        for ($i = 0; $i < count($images); $i++) {
            $images[$i]['image'] = getImageUrl($images[$i]['image'], 'images');
        }
        $videos = AboutUsVideo::where('seller_id', $request->user()->id)->get();
        for ($i = 0; $i < count($videos); $i++) {
            $videos[$i]['video'] = getImageUrl($videos[$i]['video'], 'images');
        }
        $aboutUs = AboutUs::where('seller_id', $request->user()->id)->get();
        $data =
            [
                'topProducts' => $product,
                'category' => $category,
                'ratingDetails' => $rating,
                'categoryProducts' => $categoryProducts,
                'sellersInformation' => $sellersInformation,
                'aboutUs' => $aboutUs,
                'aboutUsImages' => $images,
                'aboutUsImagesVideos' => $videos
            ];
        return $this->sendResponse(1, 'success', $data);
    }
    public function biddingLeads(Request $request)
    {
        $bidLeads = Leads::where('seller_id', $request->user()->id)->where('is_approved','Y')->where('is_contacted', 'N')->get();
        for ($i = 0; $i < count($bidLeads); $i++) {
            // $products = Products::where('id', $bidLeads[$i]['product_id'])->first();
            // $bidLeads[$i]['subCategory'] = $products->sub_category;
            $bidLeads[$i]['category'] = ProductCategory::where('id', $bidLeads[$i]['category_id'])->first()['category'];
            if (Unit::where('id', $bidLeads[$i]['unit_id'])->exists()) {
                $bidLeads[$i]['unit'] = Unit::where('id', $bidLeads[$i]['unit_id'])->first()['unit'];
            }
        }
        return $this->sendResponse(1, 'success', $bidLeads);
    }
    public function contactBuyer(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'product_id' => 'required|not_in:0',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        if (Leads::where('product_id', $request->product_id)->where('seller_id', $request->user()->id)->exists()) {
            $leads = Leads::where('product_id', $request->product_id)->where('seller_id', $request->user()->id)->first();
            if (Chat::where('product_id',$leads->product_id)->where('seller_id',$request->user()->id)->where('buyer_id',$leads->user_id)->exists()){
                $chats = Chat::where('product_id',$leads->product_id)->where('seller_id',$request->user()->id)->where('buyer_id',$leads->user_id)->first();
                $chatId = $chats->id;
            }
            else{
                $chat = Chat::create([
                    'buyer_id'=>$leads->user_id,
                    'seller_id'=>$request->user()->id,
                    'product_id'=>$leads->product_id
                ]);
                $chatId = $chat->id;
            }
            $leads->update([
                'is_contacted' => 'Y',
                'chat_id'=>$chatId
            ]);

            return $this->sendResponse(1, 'lead updated successfully',$chatId);
        } else {
            return $this->sendResponse(0, 'no lead found against this user', '');
        }

    }
    public function getPrefReports(Request $request)
    {
        $chats = Chat::where('seller_id',$request->user()->id)->count();
        $phoneCalls = PhoneCall::where('seller_id',$request->user()->id)->count();
        $data = ['phonCallsCount' => $phoneCalls, 'totalMessages' => $chats];
        return $this->sendResponse(1, 'success', $data);
    }
    public function updateProfile(Request $request)
    {
        $userID = $request->user()->id;
        $user = User::find($userID);
        if($userID) {
            // get user details
            $user  = User::where('id', $userID)->first();

            $data=array();
            // checks on each param
            if($request['new_password']) {
                if (Hash::check($request['password'], $user->password)) {
                    //password is correct use your logic here
                    $data = array();
                    $data['password'] = Hash::make($request['new_password']);
                } else{
                    return $this->sendResponse(0,'Password does not match!',null);

                }
            }
            if($request['ntn']){
                $data['ntn']=$request['ntn'];
            }
            if($request['name']){
                $data['name']=$request['name'];
            }
            if($request['location']){
                $data['location']=$request['location'];
            }
            if($request['address']){
                $data['address']=$request['address'];
            }
            if($request['email']){
                $data['email']=$request['email'];
            }

            // updating data
            $user->update($data);

            // pic updation
            if ($request->has('profile_pic')) {
                $format = '.png';
                $entityBody = $request->file('profile_pic');// file_get_contents('php://input');

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

        }else{
            return $this->sendError(0,"User Id not found!", null);
        }
    }
    public  function buyLeads(Request $request){
        $leads = Leads::findOrFail($request->lead_id);
        if($leads){
            if (Chat::where('product_id',$leads->product_id)->where('seller_id',$request->user()->id)->where('buyer_id',$leads->user_id)->exists()){
                $chats = Chat::where('product_id',$leads->product_id)->where('seller_id',$request->user()->id)->where('buyer_id',$leads->user_id)->first();
                $chatId = $chats->id;
            }
            else{
                $chat = Chat::create([
                    'buyer_id'=>$leads->user_id,
                    'seller_id'=>$request->user()->id,
                    'product_id'=>$leads->product_id
                ]);
                $chatId = $chat->id;
            }
            $leads->update([
                'is_contacted' => 'Y',
                'chat_id'=>$chatId
            ]);
            if ($leads){
                return $this->sendResponse(1,'Leads Successfully Consumed',$chatId);
            }
        }
        else{
            return $this->sendError(0,'Leads Not Consumed','');
        }


    }
    public function userInfo(Request $request)
    {
        $userCurrency = Auth::user()->currency_id;
        $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
		$sellerId=$request->sellerId;
        $userInfo = User::where('id',$sellerId)->where('is_deleted','N')->where('is_active','Y')->first();
        if ($userInfo){

            if (Products::where('user_id', $sellerId)->exists()) {
                $product = Products::where('user_id', $sellerId)->where('is_active','Y')->get();
                foreach($product as $products) {
                    $products['attachments'] = ProductAttachment::where('products_id', $products->id)->get();
                    // $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    if($products['price'] !="" && $products['price'] != null){
                    if($products['currency_id'] == 1){
                        if($userCurrency == 1)
                        {
                            $products['price'] = strval($products['price']);
                            $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                        }
                        else
                        {
                            $products['currency_id'] = $userCurrency;
                            $products['price']= strval(round(($products['price'])/$globalCurrency,2));
                            $products['currency'] = "$";
                        }
                    }
                    else{

                        if($userCurrency == 2){
                            $products['price'] = strval($products['price']);
                            $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                        }
                        else{
                            $products['currency_id'] = $userCurrency;
                            $products['price']= strval(round(($products['price'])*$globalCurrency,2));
                            $products['currency'] = "PKR";
                        }
                    }
                }
                else{
                    $products['price'] = null;
                }
                    if (ProductRating::where('product_id', $products->id)->exists()) {
                        $productRating = ProductRating::where('product_id', $products->id)->get();
                        $rating = 0;
                        for ($k = 0; $k < count($productRating); $k++) {
                            $rating += (double)$productRating[$k]['rating'];
                        }
                        $count = count($productRating);
                        $ratingAvg = $rating / $count;
                        $products['rating'] = (string)round($ratingAvg, 1);
                        //                    if(strlen($products['rating']) == 1)
                        //                    {
                        //                        $products['rating'] = $products['rating'] .'.0';
                        //                    }

                    } else {
                        $products['rating'] = "0.0";
                    }
                    if ($products['attachments']) {
                        for ($i = 0; $i < count($products['attachments']); $i++) {
                            $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                        }
                    }
                    if (Unit::where('id', $products->unit)->exists()) {

                        $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                    }
                    $products['sellerName'] = User::where('id', $products->user_id)->first()['username'];
                    $products['sellerLocation'] = User::where('id', $products->user_id)->first()['location'];
                    $products['sellerEmail'] = User::where('id', $products->user_id)->first()['email'];
                    $products['sellerPhone'] = User::where('id', $products->user_id)->first()['phone'];
                    if (ProductCategory::where('category', $products->category)->exists()) {
                        $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                    }
                    // return $products;
                }
            } else {
                $product = [];
            }
            $productcategory = Products::Select('category')->where('user_id', $sellerId)->where('is_active','Y')->get();
//            dd(count($category));
            $category = ProductCategory::whereIn('category', $productcategory)->get();
//            dd(count($category));
//            $category = $category->map(function ($products) {
//            dd($request->sellerId);

            foreach($category as $products) {
//                return $request->sellerId;
                $products['id'] = ProductCategory::where('category', $products->category)->first()['id'];
                $products['products'] = Products::where('user_id', $request->sellerId)->where('category', $products->category)->where('is_active','Y')->get();
                foreach($products['products'] as $products){
                    $products['attachments'] = ProductAttachment::where('products_id', $products->id)->get();
                    // $products['currency'] = Currency::where('id',$products->currency_id)->first()['currency'];
                    if($products['price'] !="" &&  $products['price'] != null){
                    if($products['currency_id'] == 1){
                        if($userCurrency == 1)
                        {
                            $products['price'] = strval($products['price']);
                            $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                        }
                        else
                        {
                            $products['currency_id'] = $userCurrency;
                            $products['price']= strval(round(($products['price'])/$globalCurrency,2));
                            $products['currency'] = "$";
                        }
                    }
                    else{

                        if($userCurrency == 2){
                            $products['price'] = strval($products['price']);
                            $products['currency'] = Currency::where('id',$products['currency_id'])->first()['currency'];
                        }
                        else{
                            $products['currency_id'] = $userCurrency;
                            $products['price']= strval(round(($products['price'])*$globalCurrency,2));
                            $products['currency'] = "PKR";
                        }
                    }
                }
                else{
                    $products['price'] = null;
                }
                    if (ProductRating::where('product_id', $products->id)->exists()) {
                        $productRating = ProductRating::where('product_id', $products->id)->avg('rating');
                        //                        $array = json_decode(json_encode($productRating), true);
                        //                        $sum = array_sum($array);
                        //                        $count = count($array);
                        //                        $ratingAvg = $sum / $count;
                        //                        $productRating = ProductRating::where('product_id', $products->id)->get();
                        //                        $rating = 0;
                        //                        for ($k = 0; $k < count($productRating); $k++) {
                        //                            $rating += (int)$productRating[$k]['rating'];
                        //                        }
                        //                        $count = count($productRating);
                        //                        $ratingAvg = $rating / $count;
                        $products['rating'] = (string)round($productRating, 1);

                    } else {
                        $products['rating'] = "0.0";
                    }
                    if ($products['attachments']) {
                        for ($i = 0; $i < count($products['attachments']); $i++) {
                            $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                        }
                    }
                    if (Unit::where('id', $products->unit)->exists()) {

                        $products['unit'] = Unit::where('id', $products->unit)->first()['unit'];
                    }
                    if (User::where('id', $products->user_id)->exists()) {
                        $getUserInfo = User::where('id', $products->user_id)->first();
                        $products['sellerName'] = $getUserInfo['username'];
                        $products['sellerLocation'] = $getUserInfo['location'];
                        $products['sellerEmail'] = $getUserInfo['email'];
                        $products['sellerPhone'] = $getUserInfo['phone'];
                    }
                    $products['categoryId'] = ProductCategory::where('category', $products->category)->first()['id'];
                    // return $products;
                }
            }
//                return $products;
//            });

            $aboutUs = User::Select('phone', 'email', 'location','seller_type')->where('id', $sellerId)->first();
            if (ProductPortfolio::exists()) {

                $aboutUs['productPortfolio'] = ProductPortfolio::first()['portfolio'];
            }
            if (Team::exists()) {

                $aboutUs['team'] = Team::first()['team'];
            }
            if (ProductRating::where('company_id', $sellerId)->exists()) {
                $rating = ProductRating::where('company_id', $sellerId)->get();
                $rating = $rating->map(function ($products) {
                    $products['name'] = User::where('id', $products->user_id)->first()['username'];
                    $sellerImage = User::where('id', $products->user_id)->first()['image'];
                    $products['attachments'] = getImageUrl($sellerImage, 'images');
                    //                    if ($products['attachments']) {
                    //                        for ($i = 0; $i < count($products['attachments']); $i++) {
                    //                            $products['attachments'][$i]['image'] = getImageUrl($products['attachments'][$i]['image'], 'product-attachments');
                    //                        }
                    //                    }
                    return $products;
                });
            } else {
                $rating = [];
            }

            $sellersInformation = User::Select('id','location','address','username', 'email', 'phone', 'image','seller_type')->where('id', $sellerId)->first();
            if(empty($sellersInformation->image))
            {
                $sellersInformation->image = 'xyz';
            }
            $file = public_path() . '/images/profile-pic/' . $sellersInformation->image;
            // return $file;
            if ($sellersInformation->image && file_exists($file)) {
                $sellersInformation->image = getImageUrl($sellersInformation->image, 'images');
            }
            else
            {
                $sellersInformation->image = getImageUrl('profile.png', 'images123');
            }
            if (ProductRating::where('company_id', $sellerId)->where('product_id',null)->exists()) {
                $productRating = ProductRating::where('company_id', $sellerId)->where('product_id',null)->pluck('rating');
                $array = json_decode(json_encode($productRating), true);
                $sum = array_sum($array);
                $count = count($array);
                $ratingAvg = $sum / $count;
                $rat = (string)round($ratingAvg, 1);
                $sellersInformation['rating'] = $rat;
                $sellersInformation['ratingCount'] = $count;

            } else {
                $sellersInformation['rating'] = "0.0";
                $sellersInformation['ratingCount'] = 0;
            }
            $aboutUsImages = AboutUsImage::where('seller_id', $sellerId)->get();
            for ($i = 0; $i < count($aboutUsImages); $i++) {
                $aboutUsImages[$i]['image'] = getImageUrl($aboutUsImages[$i]['image'], 'images');
            }
            $videos = AboutUsVideo::where('seller_id', $sellerId)->get();
            for ($i = 0; $i < count($videos); $i++) {
                $videos[$i]['video'] = getImageUrl($videos[$i]['video'], 'images');
            }
            $aboutUs = AboutUs::where('seller_id', $sellerId)->get();
            $aboutUsSellerDetail = AboutUs::where('seller_id', $sellerId)->first();
            if($aboutUsSellerDetail)
            {
                $aboutUsSellerID = $aboutUsSellerDetail['seller_id'];
                $aboutUsTeam = $aboutUsSellerDetail['team'];
                $aboutUsPortFolio = $aboutUsSellerDetail['port_folio'];
                $aboutUsDescription = $aboutUsSellerDetail['description'];
            }
            else
            {
                $aboutUsTeam = '';
                $aboutUsPortFolio = '';
                $aboutUsSellerID = '';
                $aboutUsDescription = '';
            }
            $aboutUsData =
                [
                    'seller_id'=> $sellersInformation['id'],
                    'team' => $aboutUsTeam,
                    'productPortFolio' => $aboutUsPortFolio,
                    'description'=>$aboutUsDescription,
                    'location' => $sellersInformation['location'],
                    'address' => $sellersInformation['address'],
                    'username' => $sellersInformation['username'],
                    'email' => $sellersInformation['email'],
                    'phone' => $sellersInformation['phone'],
                    'image' => $sellersInformation['image'],
                    'rating' => $sellersInformation['rating'],
                    'ratingCount' => $sellersInformation['ratingCount'],
                    'seller_type'=>$sellersInformation['seller_type'],

                ];
            $data =
                [
                    'topProducts' => $product,
                    'category' => $category,
                    'aboutUsImages' => $aboutUsImages,
                    'aboutUsVideos' => $videos,
                    'ratingDetails' => $rating,
                    'aboutUs' => $aboutUsData
                ];

            return $this->sendResponse(1, 'success', $data);
        }
        else{
            return $this->sendError(0, 'No information found about this user');
        }

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

                            if(is_null($row->token)){
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
    public function addProfileImage(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if ($user) {

        }
    }
    public function sellerTypes(){
       $allSellersType = DB::table('sellers_types')->get();
       if($allSellersType){
           return $this->sendResponse(1, 'success', $allSellersType);
       }else{
           return $this->sendError(0, 'No Seller Type Found');
       }

    }
    public function editSellerTypes(Request $req){

       $user = User::where('id',Auth::User()->id)->first();
        $user->seller_type = $req->seller_type;
        $user->save();
        if($user->save()){
            return $this->sendResponse(1, 'success', $user);
        }else{
            return $this->sendResponse(0, 'Error! Record not edited successfully!', 'error');
        }
    }
//    public function getMembership()
//    {
//        $membership = Membership::where('is_active','Y')->get();
//        return $this->sendResponse(1, 'success', $membership);
//    }

function deleteSellerProduct(Request $request)
    {
        $id = $request->product_id;
        $user = Products::findOrFail($id);
        if ($user) {
            $user->is_deleted = 'Y';
            $user->update();
            if ($user) {

                return response()->json(['status'=> 1,'message'=> 'Product Deleted Successfully!']);

            } else {
                return response()->json(['status'=> 0,'message'=> "Product Didn't Deleted "]);

            }
        }
    }
    public function switchRole(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $validator = array(
            'type' => 'required',
            'seller_type' => 'required'

        );
        $validation = Validator::make($request->all(), $validator);
        if ($validation->fails()) {

            return $this->sendResponse(0, 'Error! Some fields are empty!', 0);
        }
        else{
            $user->type = $request->type;
            $user->seller_type = $request->seller_type;
            $user->name = $request->name;
            $user->username = $request->name;
            // $user->role = 5;
            $user->ntn = $request->ntn;
            if($request->type == "global"){
                $user->currency_id = 2;
            }
            else{
                $user->currency_id = 1;
            }
            $user->address = $request->company_address;
            $user->update();
            if($user){
            return $this->sendError(1,'success', 'Switch Role!');
            }
        }

    }
    public function allCurrencies(){
        $getcurrency = Currency::all();
        foreach($getcurrency as $currency){
            if($currency->id == 2){
                $currency['currency'] = 'USD';
            }
            if($currency->id == 1 && Auth::user()->currency_id == 1){
                $currency['is_selected'] = 1;
            }
            else if($currency->id == 2 && Auth::user()->currency_id == 2){
                $currency['is_selected'] = 1;
            }
            else{
                $currency['is_selected'] = 0;
            }
        }
        return $this->sendResponse(1, 'success', $getcurrency);
    }
    public function userCurrency(Request $request){
        // return $request->all();
        $getUser = User::where('id',Auth::user()->id)->first();
        $getUser->currency_id = $request->currency_id;
        if($request->currency_id == 2){
            $getUser->type = 'global';
        }
        else{
            $getUser->type = 'local';
        }
        $getUser->update();
        if($getUser){
            return $this->sendResponse(1, 'User Currency Updated Successfuly!',[]);
        }
        else{
            return $this->sendError(0,'there was some issue', '0');
        }
    }
}
