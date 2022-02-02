<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Supplier;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Supply;

class csv_loader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:csv {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is command used to insert .csv files into database. Location of the file must be in public folder.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csv_name = $this->argument('name');
        $path = public_path('csv/').$csv_name.'.csv';

        // Vraća poruku ako fajl ne postoji
        if( !file_exists($path) ) return print("Fajl pod ovim imenom ne postoji.\n");

        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV);

        foreach ($file as $key => $row){

            // Proverava da li postoji prazna linija u .csv-u
            if(empty($row[0])) continue;

            // Upisujemo podatke u bazu podataka
            list($supplier_name, $days_valid, $priority, $part_number, $part_desc, $quantity, $price, $condition, $category) = $row;
                $supplier_id = Supplier::firstOrCreate(['name' => $supplier_name]);
                $condition_id = Condition::firstOrCreate(['name' => $condition]);
                $category_id = Category::firstOrCreate(['name' => $category]);

                Supply::create([
                    'supplier_id' => $supplier_id->id,
                    'days_valid' => $days_valid,
                    'priority' => $priority,
                    'part_number' => $part_number,
                    'part_desc' => $part_desc,
                    'quantity' => $quantity,
                    'price' => $price,
                    'condition_id' => $condition_id->id,
                    'category_id' => $category_id->id,
                ]);
            }

        return 0;
    }
}
