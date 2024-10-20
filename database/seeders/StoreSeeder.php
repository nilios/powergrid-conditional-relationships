<?php

namespace Database\Seeders;

use App\Models\Peripheral;
use App\Models\ProductCatalog;
use App\Models\StoreInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreInventory::truncate();
        
        echo "\n\nPopulating Store Inventory\n\n";

        for($i = 0; $i <= 500; $i++) {
            echo "Adding items: {$i}\r";

            $store_name = fake()->company . ' ' . fake()->word;

            $x = rand(1,100);
            $choice = 'peripheral';
            if($x < 50) {
                $choice = 'product';
            }

            if($choice == 'peripheral') {
                StoreInventory::create([
                    'name' => $store_name,
                    'peripheral_id' => Peripheral::inRandomOrder()->first()->id
                ]);
            }

            if($choice == 'product') {
                StoreInventory::create([
                    'name' => $store_name,
                    'product_id' => ProductCatalog::inRandomOrder()->first()->id
                ]);
            }
        }

        echo "\n\ndone.\n\n";
    }
}
