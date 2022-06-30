<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostImage;
use App\Models\PostVideo;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportPost extends Command
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
    protected $signature = 'import:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the older posts data from older database';

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
        $this->dumpPosts();
    }

    private function dumpPosts()
    {
        $userTrusts = $this->oldConnection->table('posts')->get();
        foreach($userTrusts as $userTrust) {
            $userTrust = [
                'user_id ' => $userTrust->user_id,
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
            Post::insert($data);
            $this->newConnection->commit();
        } catch (\Exception $e) {
            $this->newConnection->rollback();
        }
    }
}
