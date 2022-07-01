<?php

namespace App\Console\Commands;

use App\Models\UserTrust;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportUser extends Command
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
    protected $signature = 'import:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the older users data from older database';

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
        // $this->dumpUsers();
        // $this->dumpUserMeta();
        $this->dumpUserTrusts();
    }

    private function dumpUserTrusts()
    {
        $userTrusts = $this->oldConnection->table('users_trust')->get();
        foreach($userTrusts as $userTrust) {
            $userTrust = [
                'user_id' => $userTrust->user_id,
                'responded_id' => $userTrust->responded_id,
                'trust' => $userTrust->trust,
                'responded_date' => $userTrust->responded_date,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $data[] = $userTrust;
        }
        
        $this->newConnection->beginTransaction();
        try {
            $this->newConnection->table('user_trusts')->truncate();
            UserTrust::insert($data);
            $this->newConnection->commit();
        } catch (\Exception $e) {
            $this->newConnection->rollback();
        }
    }
}
