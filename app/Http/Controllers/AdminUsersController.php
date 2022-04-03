<?php

namespace App\Http\Controllers;

use App\Models\AdminUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminUsersController extends Controller
{
    public function access(Request $request)
    {
        if (!Auth::check() && $request->path() != 'login' && $request->path() != 'reset-password') {
            return redirect('/login');
        }
        if (!Auth::check() && $request->path() == 'reset-password') {
            return view('welcome');
        }
        if (!Auth::check() && $request->path() == 'login') {

            return view('welcome');
        }
        if (Auth::check() && $request->path() == 'reset-password') {
            return redirect('/');
        }
        if (Auth::check() && $request->path() == 'forget-password') {
            return redirect('/');
        }
        // you are already logged in... so check for if you are an admin user..
        $user = Auth::user();

        if ($user->role->isSuper == 0) {
            return redirect('/login');
        }
        if ($request->path() == 'login') {
            return redirect('/');
        }
//        if (Auth::check() && $user->role->isSuper != 0 && $request->path() == '/' ) {
//
//            return view('welcome')->with(['permission'=>$user->role->permission]);
//        }

        return $this->checkForPermission($user,$request);
    }
    public function checkForPermission($user,$request)
    {
        $permission = $user->role->permission;
        $hasPermission = false;
        if (!$permission) {
           return view('notFound');
        }
        foreach ($permission as $p) {
            $url = $p->permission->route;
            if ($request->is("$url/*")) {
                if ($p->visiter == 1 ) {
                    $hasPermission = true;
                }
            }
            if ($p->permission->route  == $request->path())
            {
                if ($p->visiter == 1 ) {
                    $hasPermission = true;
                }
            }
        }
        if ($hasPermission) {
            return view('welcome')->with(['permission'=>$user->role->permission]);
        }
        return view('welcome');
    }
   public function updateInfo(Request $request)
   { $id = Auth::id();
       Validator::validate($request->all(),[
            'fullName'=>'required',
            'password'=>'bail|required|min:8',
            'email'=>"bail|required|email|unique:users,email,$id",
        ],[
            'fullName.required'=>'لطفا نام کاربری خود را وارد کنید!',
            'password.min'=>'رمز عبور باید شامل حداقل 8 کاراکتر باشد!',
            'password.required'=>'لطفا رمزعبور خود را وارد کنید!',
           'email.required'=>'لطفا ایمیل خود را وارد کنید!',
           'email.email'=>'لطفا ایمیل خود را به درستی وارد کنید!',
           ]);
       $data = [
           'fullName' => $request->fullName,
           'email' => $request->email,
           'password' => $request->password,
       ];
       if ($request->password) {
           $password = bcrypt($request->password);
           $data['password'] = $password;
       }
       $user = User::where('id', $id)->update($data);
       return $user;
   }
   public function adminLogin(Request $request)
   {
       $this->validate($request,[
           'email'=>'bail|required|email',
           'password'=>'bail|required|min:8'
       ]);
       if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->rememberMe)) {
           $user= Auth::user();
           if ($user->role->isSuper ==0){
               Auth::logout();
               return response()->json([
                   'msg'=>'اطلاعات وارد شده صحیح نمیباشد m !'
               ]);
           }
           return response()->json([
               'msg'=>'شما با موفقیت وارد پنل شدید !',
               'user'=>$user
           ]);
       }else{
           return response()->json([
               'msg'=>'اطلاعات وارد شده صحیح نمیباشد !'
           ],401);
       }
   }
   public function createPanelUser(Request $request)
   {
       Validator::validate($request->all(),[
           'fullName'=>'required',
           'password'=>'bail|required|min:8',
           'email'=>"bail|required|email|unique:users,email",
       ],[
           'fullName.required'=>'لطفا نام کاربری را وارد کنید!',
           'password.min'=>'رمز عبور باید شامل حداقل 8 کاراکتر باشد!',
           'password.required'=>'لطفا رمزعبور را وارد کنید!',
           'email.required'=>'لطفا ایمیل را وارد کنید!',
           'email.email'=>'لطفا ایمیل را به درستی وارد کنید!',
           'email.unique'=>'کاربری در پنل با این ایمیل وجود دارد!',
       ]);
       $password = bcrypt($request->password);
        return User::create([
           'fullName' => $request->fullName,
           'email' => $request->email,
           'role_id' => $request->role_id,
           'password' => $password,
       ]);
   }
   public function logout()
   {
       Auth::logout();
       return redirect('/login');
   }
   public function showPanelUsers()
   {
       return User::with('role')->orderByDesc('id')->get();
   }
   public function deleteUser(Request $request)
   {
       $this->validate($request, [
           'id' => 'required',
       ]);
       return User::where('id',$request->id)->delete();

   }
   public function editPanelUserRole(Request $request)
   {
       Validator::validate($request->all(),[
           'id'=>'required',
           'role_id'=>'required',
       ],[
           'id.required'=>'کاربر به درستی انتخاب نشده است!',
           'role_id.required'=>'نقش را انتخاب کنید!',
       ]);
       $data = ['role_id' => $request->role_id];
       $user = User::where('id', $request->id)->update($data);
       return $user;
   }
}

