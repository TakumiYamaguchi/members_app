<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// Auth::routes();

//mail認証
Auth::routes(['verify'=>true]);
Route::get('/',function(){
 return view('auth.login');
});

//お問い合わせ
Route::get('/contact/create','ContactController@create')->name('contact.create');
Route::post('/contact/store','ContactController@store')->name('contact.store');

//ログイン後の通常のユーザー画面
Route::middleware(['verified'])->group(function(){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('/post','PostController');
    Route::post('/post/comment/store', 'CommentController@store')->name('comment.store');
    Route::get('/mypost','HomeController@mypost')->name('home.mypost');
    Route::get('/mycomment','HomeController@mycomment')->name('home.mycomment');
    //プロファイルの編集
     Route::get('/profile/{user}/edit', 'ProfileController@edit')->name('profile.edit');
     Route::put('/profile/{user}', 'ProfileController@update')->name('profile.update');
    //管理者用画面
    Route::middleware(['can:admin'])->group(function(){
    Route::get('/profile/index','ProfileController@index')->name('profile.index');
    Route::delete('profile/delete/{user}','ProfileController@delete')->name('profile.delete');

    Route::put('role/{user}/attach','RoleController@attach')->name('role.attach');
    Route::put('role/{user}/detach','RoleController@detach')->name('role.detach');
    });
});
