<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

        $this->call('ItemTableSeeder');
        $this->call('OrderTableSeeder');
        $this->call('OrderItemTableSeeder');
	}

}

class ItemTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('items')->insert([
			['name' => 'Раздвижной стол', 'weight' => 29.30, 'length' => 86, 'width' => 83, 'height' => 8],
			['name' => 'Высокий каркас кровати', 'weight' => 12, 'length' => 200, 'width' => 19, 'height' => 7],
			['name' => 'Рабочий стул', 'weight' => 11.79, 'length' => 63, 'width' => 50, 'height' => 16],
			['name' => 'Шкаф платяной', 'weight' => 21, 'length' => 167, 'width' => 52, 'height' => 10],
			['name' => 'Журнальный стол', 'weight' => 10, 'length' => 91, 'width' => 66, 'height' => 6],
			['name' => 'Тумба с ящиками на колесах', 'weight' => 32.9, 'length' => 81, 'width' => 51, 'height' => 16],
			['name' => 'Кресло', 'weight' => 11.2, 'length' => 98, 'width' => 79, 'height' => 58],
		]);
	}
}

class OrderTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('orders')->insert([
            ['zipcode_from' => '105173', 'zipcode_to' => '307041'],
            ['zipcode_from' => '634011', 'zipcode_to' => '127253'],
            ['zipcode_from' => '632959', 'zipcode_to' => '433910'],
            ['zipcode_from' => '352171', 'zipcode_to' => '656000'],
            ['zipcode_from' => '442825', 'zipcode_to' => '433320'],
		]);
	}
}

class OrderItemTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_item')->insert([
            ['order_id' => 1, 'item_id' => 1],
            ['order_id' => 1, 'item_id' => 3],
            ['order_id' => 1, 'item_id' => 5],
            ['order_id' => 2, 'item_id' => 2],
            ['order_id' => 2, 'item_id' => 4],
            ['order_id' => 2, 'item_id' => 6],
            ['order_id' => 2, 'item_id' => 3],
            ['order_id' => 3, 'item_id' => 7],
            ['order_id' => 3, 'item_id' => 5],
            ['order_id' => 3, 'item_id' => 1],
            ['order_id' => 3, 'item_id' => 2],
            ['order_id' => 3, 'item_id' => 4],
            ['order_id' => 4, 'item_id' => 5],
            ['order_id' => 5, 'item_id' => 1],
            ['order_id' => 5, 'item_id' => 7],
        ]);
    }
}