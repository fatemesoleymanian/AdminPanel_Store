<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function  getPermissionOfRole($id)
    {
        return RolePermission::with('permission')->where('role_id',$id)->orderByDesc('permission_id')->get();
    }
    public function change(Request $request)
    {
        $datas = [];
        foreach ($request->data as $r)
        {
            array_push($datas,[
                'role_id' => $request->role,
                'permission_id' => $r['permission_id'],
                'writer'=> $r['writer'] ,
                'visiter'=> $r['visiter'] ,
                'changer'=> $r['changer'] ,
                'remover'=> $r['remover'] ,
            ]);
        }
        RolePermission::where('role_id',$request->role)->delete();
        return RolePermission::insert($datas);
    }
}
