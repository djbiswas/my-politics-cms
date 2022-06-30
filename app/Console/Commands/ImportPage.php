<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportPage extends Command
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
    protected $signature = 'import:page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the older page data from older database';

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
        $this->dumpPage();
    }

    private function dumpPage()
    {
        $pages = $this->oldConnection->table('pages')->get();
        foreach($pages as $page) {
            $page = [
                'page_name' => $page->page_name,
                'page_url' => $page->page_url,
                'page_content' => $page->page_content,
                'status' => $page->display_status,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $data[] = $page;
        }

        $this->newConnection->beginTransaction();
        try {
            $this->newConnection->table('pages')->truncate();
            Page::insert($data);
            $this->newConnection->commit();
        } catch (\Exception $e) {
            $this->newConnection->rollback();
        }
    }
}
