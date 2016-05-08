<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    public $fillable = ['zipcode_from', 'zipcode_to'];

    public function items()
    {
        return $this->belongsToMany('App\Item', 'order_item')->withPivot('amount');
    }

    public function user()
    {
        return $this->belongsToMany('App\User', 'user_id');
    }
}
