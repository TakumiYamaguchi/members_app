<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use\App\Post;
use\App\Comment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts=Post::orderBy('created_at','desc')->get();
        $user=auth()->user();
        return view('home',compact('posts','user'));
    }

    public function mypost()
    {
        //ログインしているユーザーのID取得
        $user=auth()->user()->id;
        $name=auth()->user()->name;
        //データベースの中から、条件に合うデータを取得
        //モデル名::where('テーブルのカラム名', 条件）->get();
        $posts=Post::where('user_id',$user)->orderBy('created_at','desc')->get();
        return view('mypost',compact('posts','name'));
    }

    public function mycomment()
    {
        $user=auth()->user()->id;
        $name=auth()->user()->name;
        $comments=Comment::where('user_id',$user)->orderBy('created_at','desc')->get();
        return view('mycomment',compact('comments','name'));
    }
}
