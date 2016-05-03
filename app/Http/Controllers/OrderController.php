<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class OrderController extends Controller {

    /**
     * List orders
     */
    public function getIndex()
    {
        echo 'order/index page';
    }
}