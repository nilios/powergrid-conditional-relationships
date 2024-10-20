<?php

namespace Database\Seeders;

use App\Models\Peripheral;
use App\Models\ProductCatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = 'import-data/test_product_data.csv';
        
        // Check if file exists
        if (!file_exists($file_path) || !is_readable($file_path)) {
            echo "File not found or not readable: {$file_path}\n";
            return;
        }

        ProductCatalog::truncate();
        Peripheral::truncate();

        $file = fopen($file_path, 'r');
        $header = fgetcsv($file); // Read the header
        $total = count(file($file_path)) - 1; // Total items excluding the header

        echo "\n\n";
        
        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            $count++;
            echo "Importing Item: {$count} of {$total}\r";
            flush();

            // Extract data from the row
            $name = $row[0];
            $description = $row[1];
            $manufacturer = $row[2];
            $cost = $row[3];
            $unit_type = $row[4];

            // Create records based on the unit type
            try {
                if ($unit_type == 'Unit') {
                    ProductCatalog::create([
                        'name' => $name,
                        'description' => $description,
                        'manufacturer' => $manufacturer,
                        'base_cost' => $cost
                    ]);
                } elseif ($unit_type == 'Peripheral') {
                    Peripheral::create([
                        'name' => $name,
                        'description' => $description,
                        'manufacturer' => $manufacturer,
                        'base_cost' => $cost
                    ]);
                }
            } catch (\Exception $e) {
                echo "Error importing item: {$name}. Error: {$e->getMessage()}\n";
            }
        }

        fclose($file); // Close the file
        echo "\n\ndone.\n\n";
    }
}
