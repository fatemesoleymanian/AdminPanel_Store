<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function showRoles()
    {
        return Role::all();
    }
    public function addRole(Request $request)
    {
        $this->validate($request,[
            'roleName'=>'required'
        ]);
        return Role::create([
            'roleName'=>$request->roleName,
            'isSuper'=>$request->isSuper,
        ]);
    }
    public function editRole(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required'
        ]);
        $role= Role::where('id', $request->id)->update([
            'roleName' => $request->roleName,
            'isSuper' => $request->isSuper
        ]);
        return $role;
    }
    public function deleteRole(Request $request)
    {

        $this->validate($request, [
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->delete();
    }
    public function assignRoles(Request $request)
    {
        $this->validate($request,[
            'id'=>'required',
            'permission'=>'required'
        ]);
        return Role::where('id',$request->id)->update([
            'permission'=>$request->permission,
        ]);
    }
}
