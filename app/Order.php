<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\CalculatePriceDeliveryCdek;
use PhpSpec\Exception\Exception;

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

    /**
     * Подсчет стоимости заказа
     */
    public function calculateDelivery()
    {
        // если товаров нет в заказе
        if(count($this->items) === 0) {
            return 0.00;
        }

        $calculator = new CalculatePriceDeliveryCdek();
        $calculator->addTariffPriority('1');
        $calculator->addTariffPriority('3');
        $calculator->addTariffPriority('136');
        $calculator->addTariffPriority('137');
        $calculator->addTariffPriority('233');

        $calculator->setSenderCityId($this->zipcode_from);
        $calculator->setReceiverCityId($this->zipcode_to);

        foreach($this->items as $item) {
            $calculator->addGoodsItemBySize($item->weight, $item->length, $item->width, $item->height);
        }

        if ($calculator->calculate() === true) {
            $result = $calculator->getResult();

            $this->price = $result['result']['price'];
            $this->save();
        } else {
            $error = $calculator->getError();
            $messageError = '';
            if( isset($error['error']) && !empty($error) ) {
                foreach($error['error'] as $e) {
                    $messageError .= $e['text'];
                }
            }
            throw new \Exception($messageError, 500);
        }
    }
}
