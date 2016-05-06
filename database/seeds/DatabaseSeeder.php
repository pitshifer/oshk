<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Order;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

        $this->call('UserTableSeeder');
        $this->call('ItemTableSeeder');
        $this->call('OrderTableSeeder');
        $this->call('OrderItemTableSeeder');
        $this->call('RecordPriceOrder');
	}

}

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Filimon',
            'email' => 'filimon@inc.com',
            'password' => bcrypt('pass'),
        ]);
    }
}

class ItemTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('items')->insert([
			['name' => 'Раздвижной стол', 'weight' => 2.30, 'length' => 86, 'width' => 83, 'height' => 8],
			['name' => 'Высокий каркас кровати', 'weight' => 12, 'length' => 50, 'width' => 19, 'height' => 7],
			['name' => 'Рабочий стул', 'weight' => 5.79, 'length' => 63, 'width' => 50, 'height' => 6],
			['name' => 'Шкаф платяной', 'weight' => 7, 'length' => 167, 'width' => 52, 'height' => 10],
			['name' => 'Журнальный стол', 'weight' => 2, 'length' => 51, 'width' => 66, 'height' => 6],
			['name' => 'Тумба с ящиками на колесах', 'weight' => 3.9, 'length' => 81, 'width' => 51, 'height' => 16],
			['name' => 'Кресло', 'weight' => 11.2, 'length' => 98, 'width' => 79, 'height' => 58],
		]);
	}
}

class OrderTableSeeder extends Seeder
{
	public function run()
	{
        $orders = [
            ['zipcode_from' => '105173', 'zipcode_to' => '155550', 'user_id' => 1, 'price' => '0.00'],
            ['zipcode_from' => '634011', 'zipcode_to' => '127253', 'user_id' => 1, 'price' => '0.00'],
            ['zipcode_from' => '663980', 'zipcode_to' => '433910', 'user_id' => 1, 'price' => '0.00'],
            ['zipcode_from' => '663980', 'zipcode_to' => '656000', 'user_id' => 1, 'price' => '0.00'],
            ['zipcode_from' => '105173', 'zipcode_to' => '155550', 'user_id' => 1, 'price' => '0.00'],
        ];
        foreach($orders as $order) {
            Order::create($order);
        }
	}
}

class OrderItemTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_item')->insert([
            ['order_id' => 1, 'item_id' => 1, 'amount' => 1],
            ['order_id' => 1, 'item_id' => 3, 'amount' => 1],
            ['order_id' => 1, 'item_id' => 5, 'amount' => 1],
            ['order_id' => 2, 'item_id' => 2, 'amount' => 1],
            ['order_id' => 2, 'item_id' => 3, 'amount' => 1],
            ['order_id' => 3, 'item_id' => 2, 'amount' => 1],
            ['order_id' => 3, 'item_id' => 4, 'amount' => 1],
            ['order_id' => 4, 'item_id' => 5, 'amount' => 1],
            ['order_id' => 5, 'item_id' => 6, 'amount' => 1],
        ]);
    }
}

class RecordPriceOrder extends Seeder
{
    public function run()
    {
        $orders = Order::with('items')->get();

        foreach($orders as $order) {
            try {
                $calc = new \App\Services\CalculatePriceDeliveryCdek();

                // tariff
                $calc->addTariffPriority('1');
                $calc->addTariffPriority('3');
                $calc->addTariffPriority('136');
                $calc->addTariffPriority('137');
                $calc->addTariffPriority('233');

                $calc->setSenderCityId($order->zipcode_from);
                $calc->setReceiverCityId($order->zipcode_to);

                foreach($order->items as $item) {
                    $calc->addGoodsItemBySize($item->weight, $item->length, $item->width, $item->height);
                }

                if ($calc->calculate() === true) {
                    $result = $calc->getResult();

                    $order->price = $result['result']['price'];
                    $order->save();
                } else {
                    $error = $calc->getError();
                    if( isset($error['error']) && !empty($error) ) {
                        foreach($error['error'] as $e) {
                            echo 'Order: №' . $order->id . PHP_EOL;
                            echo 'Код ошибки: ' . $e['code'] . PHP_EOL;
                            echo 'Текст ошибки: ' . $e['text'] . PHP_EOL;
                        }
                    }
                }
            } catch (Exception $e) {
                echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
            }
        }
    }
}