<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Role;

class ProfileController extends Controller
{
    public function index(){
        //ユーザー情報をすべて$usersに代入。
        $users = User::all();
        return view('profile.index',compact('users'));
    }

    public function edit(User $user){
        $this->authorize('update',$user);
        $roles=Role::all();
        return view('profile.edit',compact('user','roles'));
    }

    public function update(User $user,Request $request){
        $this->authorize('update',$user);

        //バリテーション
        $inputs = request()->validate([
            'name'=>'required|max:255',
            //unique('users'）のルールから、現在の$user->idを除外
            'email'=>['required','email','max:255', Rule::unique('users')->ignore($user->id)],
            'avatar'=>'image|max:1024',
            'password'=>'required|confirmed|max:255|min:8',
            //パスワード再確認用のバリデーション
            'password_confirmation'=>'required|same:password'
          ]);
        //データベースに保存
        $inputs['password'] = Hash::make($inputs['password']);
          //アバターの保存
          if(request()->hasFile('avatar')){
              if($user->avatar!=='user_default.jpg'){
                  //削除したい画像のパスとファイル名を$oldavatarに代入
                  $oldavatar='public/avatar/'.$user->avatar;
                  //古いアバター画像を削除
                  Storage::delete($oldavatar);
              }
              $name = request()->file('avatar')->getClientOriginalName();
              $avatar = date('Ymd_His').'_'.$name;
              request()->file('avatar')->storeAs('public/avatar',$avatar);
               //avatarファイル名をデータに追加
              $inputs['avatar'] = $avatar;
          }

        $user->update($inputs);
        return back()->with('message','情報を更新しました');
    }

    public function delete(User $user){
        //ユーザーの役割を role_userテーブルから削除
        $user->roles()->detach();
        //ユーザのアバターがデフォルトのイメージではない時に、avatarを削除
        if($user->avatar !== 'user_default.jpg'){
            Storage::delete('public/avatar/'.$user->avatar);
        }
        $user->delete();
        return back()->with('message','ユーザーを削除しました');
    }
}
