<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\User;

class RoleController extends Controller
{
    public function attach(Request $request,User $user){
        //フォームを通じて送信される 'role' を、$roleIdに代入
        $roleId=request()->input('role');
        //ユーザーのroleに、この$roleIdを紐付け(attach)
        $user->roles()->attach($roleId);
        return back();
    }

    public function detach(Request $request,User $user){
        $roleId=request()->input('role');
        //ユーザーのroleから、この$roleIdを削除(detach)
        $user->roles()->detach($roleId);
        return back();
    }
}
