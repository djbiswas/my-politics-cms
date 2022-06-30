<?php

namespace App\Console\Commands;

use App\Models\Rank;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportRank extends Command
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
    protected $signature = 'import:rank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the older ranks data from older database';

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
        $this->dumpRank();
    }

    private function dumpRank()
    {
        $ranks = $this->oldConnection->table('user_ranks')->get();
        foreach($ranks as $rank) {
            $ranks = [
                'title' => $rank->title,
                'image' => $rank->rank_image,
                'post_count' => $rank->post_count,
                'trust_percentage' => $rank->trust_percentage,
                'long_desc' => $rank->long_desc,
                'created_by' => config('constants.status.active'),
                'updated_by' => config('constants.status.active'),
                'status' => config('constants.status.inActive'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $data[] = $ranks;
        }

        $this->newConnection->beginTransaction();
        try {
            $this->newConnection->table('ranks')->truncate();
            Rank::insert($data);
            $this->newConnection->commit();
        } catch (\Exception $e) {
            $this->newConnection->rollback();
        }
    }
}
