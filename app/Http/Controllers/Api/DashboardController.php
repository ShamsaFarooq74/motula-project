<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Models\Feature;
use App\Http\Models\Files;
use App\Http\Models\Category;
use App\Http\Models\SubCategory;
use App\Http\Models\SubCatChild;
use App\Http\Models\Folder;
use App\Http\Models\SubCatSubChild;
use App\Http\Models\FileType;
use App\Http\Models\FileContent;
use App\Http\Models\Region;
use App\Http\Models\UserFavoriteFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DashboardController extends ResponseController
{
    public function companyList(Request $request)
{
    $getCompany = Feature::where('is_delete', '0')->where('is_active', '1')->orderBy('created_at', 'DESC')->get();
    for ($i = 0; $i < count($getCompany); $i++) {
        if (empty($getCompany[$i]['image'])) {
            $getCompany[$i]['image'] = asset('assets/images/default-user.png');
        } else {
            $imagePath = public_path('assets/company/' . $getCompany[$i]['image']);
            if (file_exists($imagePath)) {
                $getCompany[$i]['image'] = asset('assets/company/' . $getCompany[$i]['image']);
            } else {
                $getCompany[$i]['image'] = asset('assets/images/default-user.png');
            }
        }
    }
    if ($getCompany) {
        $message = "Companies Fetched Successfully";
        return $this->sendResponse(1, $message, $getCompany);
    } else {
        $error = "Something went wrong! Please try again";
        return $this->sendError(0, $error, null, 401);
    }
}

    public function CategoryList(Request $request)
    {

        $getCategory = Category::where('is_active', '1')->where('is_delete', '0')->orderBy('priority', 'asc')->get();

        foreach ($getCategory as $category) {
            $category->company_name = Feature::where('id', $category->company_id)->first()['company_name'];
            if ($category->image) {
                if (file_exists(public_path('assets/category/' . $category->image))) {
                    $category['image'] = asset('assets/category/' . $category->image);
                } else {
                    $category['image'] = null;
                }
            } else {
                $category['image'] = null;
            }
        }
        if ($getCategory) {
            $message = "Get Categories Successfully";
            return $this->sendResponse(1, $message, $getCategory);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function SubCategoryList(Request $request)
    {
        $category_id = $request->input('category_id');
        $subcategories = SubCategory::where('is_active', '1')->where('is_delete', '0')->where('category_id', $category_id)->orderBy('priority', 'ASC')->get();
        foreach ($subcategories as $subcategory) {
            $subcategory->getCategoryName = Category::where('id', $subcategory->category_id)->first()['category_name'];
            if ($subcategory->image) {
                if (file_exists(public_path('assets/subcategory/' . $subcategory->image))) {
                    $subcategory['image'] = asset('assets/subcategory/' . $subcategory->image);
                } else {
                    $subcategory['image'] = null;
                }
            } else {
                $subcategory['image'] = null;
            }
            // $subcategory->image = asset('assets/subcategory/'.$subcategory->image);
        }
        if ($subcategories) {
            $message = "Get Sub Categories Successfully";
            return $this->sendResponse(1, $message, $subcategories);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function Files(Request $request)
    {
        $user = Auth::user();
        $region_id = $user->region_id;
        $sub_category_id = $request->input('sub_category_id');
        $files = Files::where('is_delete', '0')->where('sub_category_id', $sub_category_id)->where('region_id', $region_id)
            ->latest('id')->paginate(20);
        foreach ($files as $subcategory) {
            $favorite = UserFavoriteFile::where('is_deleted', '0')
                ->where('file_id', $subcategory->id)
                ->first();

            if ($favorite) {
                $subcategory->favorite = 1;
            } else {
                $subcategory->favorite = 0;
            }
            if ($subcategory->files) {
                if (file_exists(public_path('assets/subcategory/' . $subcategory->files))) {
                    $fileInfo = pathinfo($subcategory->files);
                    $subcategory->files = asset('assets/subcategory/' . $subcategory->files);
                    $subcategory->title = $fileInfo['filename'];
                } else {
                    $subcategory->files = '';
                }
            } else {
                $subcategory->files = '';
            }
        }

        if ($files) {
            $message = "Get Files Successfully";
            return $this->sendResponse(1, $message, $files);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function FavoriteFilesShow(Request $request)
    {
        $user = Auth::user();
        $favoritefiles = UserFavoriteFile::where('is_deleted', '0')->where('user_id', $user->id)
            ->latest('id')->get();
        foreach ($favoritefiles as $favorite) {
            $file = Files::where('is_delete', '0')
                ->where('id', $favorite->file_id)
                ->select('files', 'sub_category_id')
                ->first();
            if ($file && $file->files) {
                $fileInfo = pathinfo($file->files);

                if (file_exists(public_path('assets/subcategory/' . $file->files))) {
                    $favorite->files = asset('assets/subcategory/' . $file->files);
                    $favorite->title = $fileInfo['filename'];
                } else {
                    $favorite->files = '';
                    $favorite->title = '';
                }
            } else {
                $favorite->files = '';
                $favorite->title = '';
            }

            $favorite->sub_category_id = $file ? $file->sub_category_id : '';
        }

        if ($favoritefiles) {
            $message = "Get Favorite Files Successfully";
            return $this->sendResponse(1, $message, $favoritefiles);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function Company(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(0, $validator->errors()->first(), '');
        }
        $company = new Feature();
        $company->company_name = $request->company_name;
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
            $message = "Company Added Successfully";
            return $this->sendResponse(1, $message, $company);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function Category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'company_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(0, $validator->errors()->first(), '');
        }
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
        $saveCategory->is_active = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $saveCategory->save();
        if ($saveCategory) {
            $message = "Category Added Successfully";
            return $this->sendResponse(1, $message, $saveCategory);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function SubCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_category_name' => 'required',
            'category_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(0, $validator->errors()->first(), '');
        }
        $saveSubCategory = new SubCategory();
        $saveSubCategory->category_id = $request->category_id;
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
            $message = "Sub Category Added Successfully";
            return $this->sendResponse(1, $message, $saveSubCategory);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function FileSubCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subcategory_id' => 'required',
            'region_id' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(0, $validator->errors()->first(), '');
        }
        $newFile = null;
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $originalFileName = $file->getClientOriginalName();
                // $newFileName = date('dmyHis') . '_' . $originalFileName;
                $existingFile = Files::where('region_id', $request->region_id)
                    ->where('files', $originalFileName)
                    ->first();
                // return $existingFile;
                if ($existingFile) {
                    continue;
                }

                $file->move(public_path() . '/assets/subcategory/', $originalFileName);
                $newFile = new Files();
                $newFile->sub_category_id = $request->subcategory_id;
                $newFile->region_id = $request->region_id;
                $newFile->files = $originalFileName; // Store the original file name
                $newFile->save();
            }
        }

        if ($newFile) {
            $message = "Files Added Successfully";
            return $this->sendResponse(1, $message, $newFile);
        } else {
            $error = "Something went wrong! Please try again.";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function FavoriteFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(0, $validator->errors()->first(), '');
        }
        $user = Auth::user();
        $file_id = $request->file_id;
        $favorite = new UserFavoriteFile();
        $favorite->user_id = $user->id;
        $favorite->file_id = $file_id;
        $favorite->save();
        if ($favorite) {
            $message = "Favorite File Added Successfully";
            return $this->sendResponse(1, $message, $favorite);
        } else {
            $error = "Something went wrong! Please try again.";
            return $this->sendError(0, $error, null, 401);
        }
    }
    public function FavoriteFileDelete(Request $request)
    {
        $id = $request->file_id;
        $favorite = UserFavoriteFile::where('file_id', $id)->first();
        $favorite->delete();
        if ($favorite) {
            $message = "Favorite File Deleted Successfully";
            return $this->sendResponse(1, $message, $favorite);
        } else {
            $error = "Something went wrong! Please try again.";
            return $this->sendError(0, $error, null, 401);
        }
    }

    public function SearchFile(Request $request)
    {
        $user = Auth::user();
        $region_id = $user->region_id;
        $searchKeyword = $request->input('search');
        $categoryId = $request->input('category_id');
        $sub_category_id = $request->input('sub_category_id');
        $child_id = $request->input('child_id');
        $sub_child_id = $request->input('sub_child_id');
        $file_type_id = $request->input('file_type_id');
        if ($categoryId != null) {
            $subCategories = SubCategory::where('category_id', $categoryId)->where('is_active', '1')->where('is_delete', '0')->pluck('id');
        } else {
            $subCategories = '';
        }
        $files = Files::where(function ($query) use ( $sub_category_id, $categoryId, $subCategories,$child_id,$sub_child_id) {
            $query->where('is_delete', '0');
            if ($sub_category_id !== null) {
                $query->where('files.sub_category_id', $sub_category_id);
            }
            if ($categoryId != null) {
                $query->whereIn('files.sub_category_id', $subCategories);
            }
            if ($child_id != null) {
                $query->where('files.child_id', $child_id);
            }
            if ($sub_child_id != null) {
                $query->where('files.sub_child_id', $sub_child_id);
            }
        })
            ->pluck('id');
            if ($file_type_id != null) {
                $files = FileContent::whereIn('file_id',$files)->where('file_type_id', $file_type_id)->where('file_path', 'like', "%$searchKeyword%")->where('region_id', $region_id)->where('is_deleted','0')->paginate(20);
            }else{
                $files = FileContent::whereIn('file_id',$files)->where('file_path', 'like', "%$searchKeyword%")->where('region_id', $region_id)->where('is_deleted','0')->paginate(20);
            }

        foreach ($files as $subcategory) {
            $favorite = UserFavoriteFile::where('is_deleted', '0')
                ->where('file_content_id', $subcategory->id)
                ->first();

            if ($favorite) {
                $subcategory->favorite = 1;
            } else {
                $subcategory->favorite = 0;
            }
            $subcategory->category_id = $categoryId;
            $subcategory->sub_category_id = $sub_category_id;
            $subcategory->child_id = isset($child_id) ? $child_id : "";
            $subcategory->sub_child_id = isset($sub_child_id) ? $sub_child_id: "";
            $subcategory->file_type_id = isset($file_type_id) ? $file_type_id: "";
            if ($subcategory->file_path) {
                if (file_exists(public_path('assets/subcategory/' . $subcategory->file_path))) {
                    $fileInfo = pathinfo($subcategory->file_path);
                    $subcategory->file_path = asset('assets/subcategory/' . $subcategory->file_path);
                    $subcategory->title = $fileInfo['filename'];
                } else {
                    $subcategory->file_path = '';
                    $subcategory->title = "";
                }
            } else {
                $subcategory->file_path = '';
                $subcategory->title = "";
            }
        }

        if ($files->isEmpty()) {
            $error = "No matching files found for the search keyword.";
            return $this->sendResponse(1, $error, $files);
        } else {
            $message = "Search Successful";
            return $this->sendResponse(1, $message, $files);
        }
    }
    public function subCategoryChild(Request $request)
    {
        $sub_category_id = $request->sub_category_id;
        $sub_cat_child = SubCatChild::where('is_deleted', '0')->where('is_active', '1')
            ->where('sub_category_id', $sub_category_id)->orderBy('priority', 'asc')->get();
            foreach ($sub_cat_child as $childImage) {
                if ($childImage->image) {
                    if (file_exists(public_path('assets/subcategory/' . $childImage->image))) {
                        $childImage['image'] = asset('assets/subcategory/' . $childImage->image);
                    } else {
                        $childImage['image'] = null;
                    }
                } else {
                    $childImage['image'] = null;
                }
            }
        if ($sub_cat_child->isEmpty()) {
            $error = "No Sub Category Child Found.";
            return $this->sendResponse(1, $error, $sub_cat_child);
        } else {
            $message = "Sub Category Child Found";
            return $this->sendResponse(1, $message, $sub_cat_child);
        }
    }
    public function subCategorySubChild(Request $request)
    {
        $sub_category_id = $request->sub_category_id;
        $child_id = $request->child_id;
        $checkSubchild = SubCatChild::where('id',$child_id)->where('is_deleted','0')->where('is_active','1')->first();
        if($checkSubchild == ""){
            $error = "No Sub Category Sub Child Found.";
            return $this->sendResponse(1, $error, []);
        }
        $sub_cat_sub_child = SubCatSubChild::where('is_deleted', '0')->where('is_active', '1')
        ->where(function ($query) use ($sub_category_id,$child_id) {
            $query->where('sub_category_id', $sub_category_id);
            if ($child_id) {
                $query->Where('child_id', $child_id);
            }
        })
        ->orderBy('priority', 'asc')->get();
            foreach ($sub_cat_sub_child as $subcategory) {
                if ($subcategory->image) {
                    if (file_exists(public_path('assets/subcategory/' . $subcategory->image))) {
                        $subcategory['image'] = asset('assets/subcategory/' . $subcategory->image);
                    } else {
                        $subcategory['image'] = null;
                    }
                } else {
                    $subcategory['image'] = null;
                }
            }
        if ($sub_cat_sub_child->isEmpty()) {
            $error = "No Sub Category Sub Child Found.";
            return $this->sendResponse(1, $error, $sub_cat_sub_child);
        } else {
            $message = "Sub Category Sub Child Found";
            return $this->sendResponse(1, $message, $sub_cat_sub_child);
        }
    }
    public function fileType()
    {
        $file_type = FileType::where('is_deleted', '0')->where('is_active', '1')
            ->orderBy('priority', 'asc')->get();
            foreach ($file_type as $subcategory) {
                if ($subcategory->image) {
                    if (file_exists(public_path('assets/subcategory/' . $subcategory->image))) {
                        $subcategory['image'] = asset('assets/subcategory/' . $subcategory->image);
                    } else {
                        $subcategory['image'] = null;
                    }
                } else {
                    $subcategory['image'] = null;
                }
            }
        if ($file_type->isEmpty()) {
            $error = "No File Type Found.";
            return $this->sendResponse(1, $error, $file_type);
        } else {
            $message = "File Types Found";
            return $this->sendResponse(1, $message, $file_type);
        }
    }
    public function viewFile(Request $request)
    {
        $file_type = $request->file_type_id;
        $files = FileContent::where('is_deleted', '0')->where('file_type_id', $file_type)->get();
        if ($files->isEmpty()) {
            $error = "No File Type Found.";
            return $this->sendResponse(1, $error, $files);
        } else {
            $message = "File Types Found";
            return $this->sendResponse(1, $message, $files);
        }
    }
    public function listFolder(Request $request){
        $user = Auth::user();
        $fileContentId = $request->file_content_id;
        $listFolder = Folder::where('user_id',$user->id)->where('is_deleted','0')->get();
        foreach($listFolder as $folder){
            $savedFileFolder = UserFavoriteFile::where('folder_id',$folder->id)->where('file_content_id',$fileContentId)->where('is_deleted','0')->first();
            if($savedFileFolder){
                $folder->is_favorite_file = 1;
            }else{
                $folder->is_favorite_file = 0;
            }
        }
        if ($listFolder) {
            $message = "All Folder Listed Successfully";
            return $this->sendResponse(1, $message, $listFolder);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 200);
        }
    }
    public function createFolder(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'folder_name' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(0, $validator->errors()->first(), '');
        }
        $existingFolder = Folder::where('folder_name', $request->folder_name)->first();
        if($existingFolder !=null && $existingFolder->is_deleted === "1"){
            $existingFolder->is_deleted = "0";
            $existingFolder->folder_name = $request->folder_name;
            $existingFolder->user_id = $user->id;
            $existingFolder->update();
            if ($existingFolder) {
                $message = "Folder Added Successfully";
                return $this->sendResponse(1, $message, $existingFolder);
            } else {
                $error = "Something went wrong! Please try again";
                return $this->sendError(0, $error, null, 200);
            }
        } elseif ($existingFolder) {
            $message = "Folder Already Exist";
            return $this->sendResponse(1, $message, $existingFolder);
        } else {
            $saveFolder = new Folder();
            $saveFolder->folder_name = $request->folder_name;
            $saveFolder->user_id = $user->id;
            $saveFolder->save();
            if ($saveFolder) {
                $message = "Folder Added Successfully";
                return $this->sendResponse(1, $message, $saveFolder);
            } else {
                $error = "Something went wrong! Please try again";
                return $this->sendError(0, $error, null, 200);
            }
        }
    }
    public function deleteFolder(Request $request){
        $deletefolder = Folder::find($request->id);
        $deletefolder->is_deleted = '1';
        $deletefolder->update();
        $deleteFile = UserFavoriteFile::where('folder_id',$deletefolder->id)->get();
        foreach ($deleteFile as $key => $file) {
            $file->is_deleted = '1';
            $file->update();
        }
        if ($deletefolder) {
            $message = "Folder Delete Successfully";
            return $this->sendResponse(1, $message, $deletefolder);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 200);
        }
    }
    public function addFile(Request $request){
        $file_content_id = $request->file_content_id;
        $folder_id = $request->folder_id;
        $favrtFile =  UserFavoriteFile::where('folder_id',$folder_id)->where('file_content_id',$file_content_id)->first();
        if($favrtFile){
            $favrtFile->is_deleted = "0";
            $favrtFile->save();
        }else{
            $addFile = new UserFavoriteFile();
            $addFile->file_content_id = $file_content_id;
            $addFile->folder_id = $folder_id;
            $addFile->save();
        }
        if ($addFile) {
            $message = "File Added Successfully";
            return $this->sendResponse(1, $message, $addFile);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 200);
        }
    }
    public function deleteFile(Request $request){
        // $deleteFile = UserFavoriteFile::find($request->id);
        $deleteFile = UserFavoriteFile::where('folder_id',$request->folder_id)->where('file_content_id',$request->id)->first();
        $deleteFile->is_deleted = '1';
        $deleteFile->update();
        if ($deleteFile) {  
            $message = "File Delete Successfully";
            return $this->sendResponse(1, $message, $deleteFile);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 200);
        }
    }
    public function folderFile(Request $request){
        if($request->folder_id != null){
            $folder_id = $request->folder_id;
            $folderFile = UserFavoriteFile::where('is_deleted','0')->where('folder_id',$folder_id)
            ->pluck('file_content_id');
        }else{
            $userFolders=  Folder::where('user_id',Auth::user()->id)->where('is_deleted','0')->pluck('id');
            $folderFile = UserFavoriteFile::whereIn('folder_id',$userFolders)->where('is_deleted','0')
            ->pluck('file_content_id');
        }
        $files = FileContent::where('is_deleted','0')->whereIn('id',$folderFile)->get();
        foreach($files as $file){
            if ($file->file_path) {
                if (file_exists(public_path('assets/subcategory/' . $file->file_path))) {
                    $fileInfo = pathinfo($file->file_path);
                    $file->file_path = asset('assets/subcategory/' . $file->file_path);
                    $file->title = $fileInfo['filename'];
                } else {
                    $file->file_path = '';
                    $file->title = "";
                }
            } else {
                $file->file_path = '';
                $file->title = "";
            }
        }

        if ($files) {
            $message = "Get Folder Files Successfully";
            return $this->sendResponse(1, $message, $files);
        } else {
            $error = "Something went wrong! Please try again";
            return $this->sendError(0, $error, null, 200);
        }
    }
}
