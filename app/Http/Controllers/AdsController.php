<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdsController extends Controller
{
public function getAds()
{
    return Ad::orderByDesc('id')->get();
}
public function createAds(Request $request)
{
    Validator::validate($request->all(),[
        'title'=>'required|max:20',
        'link'=>'required',
        'image'=>"required",
        'name'=>"required",
    ],[
        'title.required'=>'لطفا تیتر تبلیغ را وارد کنید!',
        'title.max'=>'تیتر تبلیغ طولانی است!',
        'link.required'=>'لطفا لینک تبلیغ را وارد کنید!',
        'name.required'=>'لطفا نام تبلیغ را وارد کنید!',
        'image.required'=>'لطفا عکس تبلیغ را وارد کنید!',
    ]);

    return Ad::create([
        'title' => $request->title,
        'link' => $request->link,
        'image' => $request->image,
        'name' => $request->name
    ]);
}
public function upload(Request $request)
{
    Validator::validate($request->all(),[

        'file'=>"required|image|mimes:jpg,png,jpeg,gif,svg",
    ],[
        'file.required'=>'لطفا عکس تبلیغ را وارد کنید!',
        'file.image'=>'لطفا عکس تبلیغ را به درستی وارد کنید!',
        'file.mimes'=>'jpeg,jpg,png,svg میباشد فرمت های قابل قبول برای عکس!',
    ]);
    $pic = time() . '.' . $request->file->extension();
    $request->file->move(public_path('images\ads'), $pic);
    return $pic;
}
public function deleteServerImage(Request $request)
    {
        $path = parse_url($request->imageName);
        $remove = File::delete(public_path($path['path']));
        if ($remove) return 'done';
        else return 'not done';
    }
public function deleteFromserver($fileName, $hasFullPath=false)
    {
        if(!$hasFullPath)
        {
            $file_path = public_path().$fileName;
        }

        if (file_exists($fileName))
        {
            @unlink($fileName);
        }

    }
public function editAd(Request $request)
    {
        Validator::validate($request->all(),[
            'title'=>'required|max:20',
            'link'=>'required',
            'image'=>"required",
            'name'=>"required",
        ],[
            'title.required'=>'لطفا تیتر تبلیغ را وارد کنید!',
            'title.max'=>'تیتر تبلیغ طولانی است!',
            'link.required'=>'لطفا لینک تبلیغ را وارد کنید!',
            'name.required'=>'لطفا نام تبلیغ را وارد کنید!',
            'image.required'=>'لطفا عکس تبلیغ را وارد کنید!',
        ]);
        return Ad::where('id', $request->id)->update([
            'title' => $request->title,
            'link' => $request->link,
            'image' => $request->image,
            'name' => $request->name,
        ]);
    }
public function deleteAd(Request $request)
    {
        Validator::validate($request->all(),[
            'id'=>'required',
        ],[
            'id.required'=>'خطا در انتخاب تبلیغ مورد نظر!',
        ]);
        return Ad::where('id',$request->id)->delete();
    }
    public function deleteGroupImages(Request $request)
    {
        $images=[];
        $images= $request->imageName;
        foreach ($images as $image) {

            $path = parse_url($image);
            $remove = File::delete(public_path($path['path']));
        }
        return 'done';
    }
}
