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
        $zipcodes = ['105173','155550','634011','127253','663980','433910','663980','656000','105173','155550'];

        return view('order.create', compact('items', 'zipcodes'));
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
        $this->validate($request, [
            'zip_code_from' => 'required|integer',
            'zip_code_to' => 'required|integer',
        ]);

        // Получаем пользователя
        $user = Auth::user();

        // создаем модель заказа заказ
        $order = new Order([
            'zipcode_from' => $request->get('zip_code_from'),
            'zipcode_to' => $request->get('zip_code_to'),
        ]);

        // сохраняем заказ с привязкой к пользователю
        $order = $user->orders()->save($order);

        // приязываем товары к заказу если они есть
        if($itemsID = $request->get('items')) {
            $itemsAmount = [];
            foreach($itemsID as $id => $amount) {
                $itemsAmount[$id] = ['amount' => $amount];
            }

            $order->items()->sync($itemsAmount);
        }

        return redirect('/order/index');
    }
}