<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function submitForgetPasswordForm(Request $request)
    {
        Validator::validate($request->all(),[
            'email'=>'bail|required|email'
        ],[
            'email.required'=>'لطفا ایمیل خود را وارد کنید!',
            'email.email'=>'لطفا ایمیل خود را به درستی وارد کنید!',
        ]);
        $user =DB::table('users')
            ->where([
                'email' =>$request->email
            ])
            ->first();
        if (!$user) return response()->json([
            'msg'=>'کاربری با این ایمیل در پنل وجود ندارد!'
        ],401);
        $token = Str::random(64);

        Mail::send('mail', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('بازیابی رمز عبور');
        });

            if (Mail::failures())
            {
                return response()->json([
                    'msg'=>'خطا در ارسال رمز عبور! مجددا تلاش کنید.!'
                ]);
            }
            else {

                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
               return response()->json([
                    'msg'=>'رمز عبور به شما ایمیل شد!',
                   'token'=>$token
                ]);
            }

        return $token;
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'reset_pass' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'token' =>$request->reset_pass,
                'email' =>$request->email,
            ])
            ->first();

        if(!$updatePassword){
            return response()->json([
                'msg'=>'رمز عبور صحیح نمی باشد!'
            ],401);
        }

        $pass = bcrypt($request->reset_pass);
        $data = [
            'password' => $pass,
        ];
         User::where('email', $request->email)
            ->update($data);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();
        return response()->json([
            'msg'=>'احراز هویت شما تکمیل شد! وارد پنل کاربری خود شوید. !',
        ]);

    }
}



