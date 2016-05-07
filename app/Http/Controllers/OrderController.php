<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {

public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List orders
     */
    public function getIndex()
    {
        $user = Auth::user();
        $orders = $user->orders;

        return view('order.index', compact('orders', 'user'));
    }

    /**
     * Order info
     */
    public function getView($id)
    {
        $order = Order::find($id);
        $order->load('items');

        return view('order.view', compact('order'));
    }

    /**
     * Страница с формой для ногового заказа
     */
    public function getCreate()
    {
        $items = Item::all();

        return view('order.create', compact('items'));
    }

    /**
     * Принимаем входные данные, проверяем и сохраняем новый заказ
     *
     * @param $request Request
     * @return void
     */
    public function postCreate(Request $request)
    {
        // проверяем данные
        // todo следует так же проверять на то что должно содержаться 6 цифр. Нужно создать свой валидатор.
        $validator = Validator::make($request->all(), [
            'zip_code_from' => 'required|integer',
            'zip_code_to' => 'required|integer',
        ]);

        // в случае ошибки делаем редирект с записью в сессию сообщения об ошибках
        if($validator->fails()) {
            return redirect('/order/create')
                ->withInput()
                ->withErrors($validator);
        }

        return redirect('/order/index');
    }
}