<?php

namespace App\Http\Controllers;

use App\Models\color;
use Illuminate\Http\Request;

class ColorsController extends Controller
{
    public function add(Request $request)
    {
        \Illuminate\Support\Facades\Validator::validate($request->all(),[
            'cname'=>'required',
            'code'=>'required',
        ],[
            'cname.required'=>'لطفا نام رنگ را وارد کنید!',
            'code.required'=>'لطفا کد رنگ را وارد کنید!',
            ]);
        return color::create([
            'name'=>$request->cname,
            'code'=>$request->code
        ]);
    }
    public function show()
    {
        return color::orderByDesc('id')->get();
    }
    public function delete(Request $request)
    {
        return color::where('id',$request->id)->delete();
    }
    public function edit(Request $request)
    {
        \Illuminate\Support\Facades\Validator::validate($request->all(),[
            'name'=>'required',
            'code'=>'required',
        ],[
            'name.required'=>'لطفا نام رنگ را وارد کنید!',
            'code.required'=>'لطفا کد رنگ را وارد کنید!',
        ]);
        return color::where('id',$request->id)->update([
            'name'=>$request->name,
            'code'=>$request->code
        ]);
    }
}
