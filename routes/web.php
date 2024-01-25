<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/post/{id}', function($id) {
//     return view('welcome', ['id'=> $id]);
// });

// Route::match(['get','post'],'/post/{id}',function($id) {
//     return view('welcome', ['id'=> $id]);
// });

// Route::get('/post/{name?}', function ($name='Ade') {
//     return view('welcome', ['name'=> $name]);
// });


// Route::get('/{id?}', function ($id=8) {
//     return view('welcome', ['id'=> $id]);
// });

// Route::get('/employee/{id}/details', function ($id) {
//     return view('employee', ['id'=> $id]);
// })->name('employee_details');

// Route::get('/employee/{id}/details', array('as'=>'employee', function ($id) {
//     return view('employee', ['id'=> $id]);
// }))->name('employee_details');

// Route::get('/employee/{id}/details', 'EmployeeController@getDetails')->name('employee_details');

// Route::get('', function () {})->middleware();

// Route::group([], function() {
//     Route::get('','')->name('');
//     Route::post('','');
// });

// Route::middleware(['age'])->group(["prefix"=>"admin"], function () {
//     Route::get('','')->name('');
//     Route::post('/edit-profile','')->name('');
// });

// // Route::name()->;

// Route::resource('user', 'UserController');
// Route::resource('user', UserController::class);

// Route::Resources([
//     'user'=> UserController::class,
//     'posts'=> 'PostController',
// ]);

// Route::resource('user', UserController::class, ['only'=> ['index','show']]);
// Route::resource('user', UserController::class, ['names'=> ['create','user.new']]);
// Route::resource('user', UserController::class, ['parameters'=> ['id','user_id']]);

Route::get('/contact/{id}', function($id) {
    // if(View::exists('contact')) {
    //     echo 'yes it exists';
    // }
    return view('about', ['id'=> $id]);
});

// Passing data to views
// Route::get('/contact', function($id) {
//     return view('contact', ['id', $id]);
// });

// Route::get('/details', function($id) {
//     return view('contact')->with('id', $id);
// });

// Route::get('/details', function($id) {
//     return view('contact', compact('id'));
// });

