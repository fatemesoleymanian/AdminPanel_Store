<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function show()
    {
        return Brand::all();
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'company' => 'required',
            'country' => 'required',
            'img' => 'required',
        ]);
        return Brand::create([
            'company' => $request->company,
            'country' => $request->country,
            'image' => $request->img,
        ]);
    }

    public function upload(Request $request)
    {
        Validator::validate($request->all(), [

            'file' => "required|image|mimes:jpg,png,jpeg,gif,svg",
        ], [
            'file.required' => 'لطفا لوگو را انتخاب کنید!',
            'file.image' => 'لطفا لوگو را به درستی انتخاب کنید!',
            'file.mimes' => 'jpeg,jpg,png,svg میباشد فرمت های قابل قبول برای عکس!',
        ]);
        $pic = time() . '.' . $request->file->extension();
        $request->file->move(public_path('images\brands'), $pic);
        return $pic;
    }

    public function edit(Request $request)
    {
        Validator::validate($request->all(),[
            'img'=>'required',
            'company'=>'required',
            'country'=>"required",
        ],[
            'img.required'=>'لطفا لوگو را وارد کنید!',
            'company.required'=>'لطفا کمپانی را وارد کنید!',
            'country.required'=>'لطفا کشور را وارد کنید!'
        ]);
        return Brand::where('id', $request->id)->update([
            'company' => $request->company,
            'country' => $request->country,
            'image' => $request->img,
        ]);
    }

    public function delete(Request $request)
    {
        Validator::validate($request->all(),[
            'id'=>'required',
        ],[
            'id.required'=>'خطا در انتخاب برند مورد نظر!',
        ]);
        return Brand::where('id',$request->id)->delete();
    }
}
