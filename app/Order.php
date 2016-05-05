<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    public function items()
    {
        return $this->belongsToMany('App\Item', 'order_item')->withPivot('amount');
    }

    public function user()
    {
        return $this->belongsToMany('App\User', 'user_id');
    }
}
