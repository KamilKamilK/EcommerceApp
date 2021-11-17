<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Factories\OrderProductFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(10)->create();
         Product::factory(20)->create();
         Order::factory(20)->create();
         DB::table('order_products')->insert(
           [
               'order_id' => Order::all()->random()->id,
               'product_id' => Product::all()->random()->id,
           ]
         );
    }
}
