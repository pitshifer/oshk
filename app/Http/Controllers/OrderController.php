<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}