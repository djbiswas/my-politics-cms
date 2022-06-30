<?php

namespace App\Console\Commands;

use App\Models\Reaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportReaction extends Command
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
    protected $signature = 'import:reaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the older reaction data from older database';

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
        $this->dumpOtp();
    }

    private function dumpOtp()
    {
        $reactions = $this->oldConnection->table('reactions')->get();
        foreach($reactions as $reaction) {
            $reaction = [
                'user_id' => $reaction->user_id,
                'm_id' => $reaction->m_id,
                'm_type' => $reaction->m_type,
                'reaction' => $reaction->reaction,
                'reacted_date' => $reaction->reacted_date,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $data[] = $reaction;
        }

        $this->newConnection->beginTransaction();
        try {
            $this->newConnection->table('reactions')->truncate();
            Reaction::insert($data);
            $this->newConnection->commit();
        } catch (\Exception $e) {
            $this->newConnection->rollback();
        }
    }
}
