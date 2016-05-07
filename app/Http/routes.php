<?php
use \Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
    'order' => 'OrderController',
]);

// Выдача списка всех товаров с их характеристиками
Route::get('/item/all', ['before' => 'auth', function() {
    return Response::json(\App\Item::all());
}]);

// Выдача данных по одному товару. Принимаем ID товара
Route::post('/item/getInfo', ['before' => 'auth', function(\Illuminate\Http\Request $request) {
    $errors = [];
    $response = ['success' => true];
    // ID товара
    $id = (int)$request->input('id');

    // Проверяем ID и существование товара в базе
    if(!$id || !$item = \App\Item::find($id)) {
        $errors[] = 'Товар не найден';
    }

    if(empty($errors)) {
        $response['info'] = $item;
    } else {
        $response = [
            'success' => false,
            'errors' => $errors,
        ];
    }

    return Response::json($response);
}]);