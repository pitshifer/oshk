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
	}

}

class ItemTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('items')->insert([
			['name' => 'Раздвижной стол', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
			['name' => '', 'weight' => '', 'size' => ''],
		]);
	}
}