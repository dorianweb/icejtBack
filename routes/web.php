<?php

use App\Models\User;
use App\Models\Supplement;
use App\Models\CustomCream;
use App\Models\SupplementType;
use App\Http\Resources\UserRessource;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    dd('home');
    //return User::with('roles')->paginate(15);
    // return  UserRessource::collection(User::with('roles', 'carts.classic_creams.flavor', 'carts.custom_creams.supplements.supplement_types', 'carts.custom_creams.flavors')->get());
});
