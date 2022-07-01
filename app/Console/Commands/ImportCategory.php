<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportCategory extends Command
{

    /**
     * @var oldConnection
     */
    private $oldConnection;

    /**
     * @var newConnection
     */
    private $newConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the older categories data from older database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->oldConnection = DB::connection('mysql2');
        $this->newConnection = DB::connection('mysql');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->dumpCategory();
    }

    private function dumpCategory()
    {
        $categories = $this->oldConnection->table('p_categories')->get();
        foreach($categories as $category) {
            $category = [
                'name' => $category->cat_name,
                'description' => $category->cat_description,
                'icon' => $category->cat_icon,
                'created_by' => config('constants.status.active'),
                'updated_by' => config('constants.status.active'),
                'status' => config('constants.status.active'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $data[] = $category;
        }

        $this->newConnection->beginTransaction();
        try {
            $this->newConnection->table('categories')->truncate();
            Category::insert($data);
            $this->newConnection->commit();
        } catch (\Exception $e) {
            $this->newConnection->rollback();
        }
    }
}
