<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Order;

class OrderController extends Controller {

    /**
     * List orders
     */
    public function getIndex()
    {
        $orders = Order::all();

        return view('order.index', compact('orders'));
    }
}