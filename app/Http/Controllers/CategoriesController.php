<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
   public function show()
   {
       $categories = Category::with('sub_category')->get();
//       $categories = Category::with('sub_category')->orderBy('category_id','asc')->get();
       return $categories;
   }
   public function upload(Request $request)
   {
       Validator::validate($request->all(),[

           'file'=>"required|image|mimes:jpg,png,jpeg,gif,svg",
       ],[
           'file.required'=>'لطفا آیکون دسته بندی را وارد کنید!',
           'file.image'=>'لطفا آیکون تبلیغ را به درستی وارد کنید!',
           'file.mimes'=>'jpeg,jpg,png,svg میباشد فرمت های قابل قبول برای آیکون!',
       ]);
       $pic = time() . '.' . $request->file->extension();
       $request->file->move(public_path('images\categories'), $pic);
       return $pic;
   }
   public function add(Request $request)
   {
       Validator::validate($request->all(),[
           'name'=>"required",
       ],[
           'name.required'=>'لطفا نام دسته بندی را وارد کنید!',
       ]);

       return Category::create([
           'category_id' => $request->category_id,
           'image' => $request->image,
           'name' => $request->name
       ]);
   }
   public function delete(Request $request)
   {
       Validator::validate($request->all(),[ 'id'=>'required'],['id.required'=>'خطا در انتخاب دسته بندی در سرور!']);
       $category = Category::where('id',$request->id)->delete();
       $sub = Category::where('category_id',$request->id)->delete();
       return [$category,$sub];
   }
   public function edit(Request $request)
   {
       Validator::validate($request->all(),['name'=>"required",],['name.required'=>'لطفا نام دسته بندی را وارد کنید!']);
       $cat = Category::where('id', $request->id)->update([
           'category_id' => $request->category_id,
           'image' => $request->image,
           'name' => $request->name,
       ]);
       return $cat;
   }
}
