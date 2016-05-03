<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

    public $fillable = ['name', 'weight', 'size'];

	public $timestamps = false;

	public function items()
	{
		return $this->belongsToMany('App\Order', 'order_item');
	}
}
