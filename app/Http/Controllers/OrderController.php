<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

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
        $this->validate($request, [
            'zip_code_from' => 'required|digits:6',
            'zip_code_to' => 'required|digits:6',
        ]);

        // начало транзакции
        DB::beginTransaction();

        try {
            // Получаем пользователя
            $user = Auth::user();

            // создаем модель заказа
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

            // Пересчитать стоимость доставки
            $order->calculateDelivery();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $messageBag = new MessageBag(['items'=>$e->getMessage()]);
            return redirect('order/create')->withInput()->withErrors($messageBag);
        }

        return redirect('/order/index');
    }

    /**
     * Удаление заказа
     */
    public function getDelete($id)
    {
        $user = Auth::user();

        // Удаляем запись о заказе
        // Пользователь может удалять только свои запись. Добиваемся этого с помошью условия в запросе на удаление.
        // Удаление отношений из связующей таблицуе реализуется самой СУБД
        Order::where('user_id', '=', $user->id)->where('id', '=', $id)->delete($id);

        return redirect('order/index');
    }
}