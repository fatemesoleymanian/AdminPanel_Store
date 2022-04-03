<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function show()
    {
        return Permission::orderByDesc('id')->get();
    }
}
