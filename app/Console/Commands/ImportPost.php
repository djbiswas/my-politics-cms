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
        $this->dumpPostComments();
    }

    private function dumpPostComments()
    {
        $postComments = $this->oldConnection->table('post_comments')->get();
        foreach($postComments as $postComment) {
            $postComment = [
                'parent_comment_id' => $postComment->parent_comment_id,
                'user_id' => $postComment->user_id,
                'post_id' => $postComment->post_id,
                'comment' => $postComment->comment,
                'gif' => $postComment->postGif,
                'image' => $postComment->postImages,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $data[] = $postComment;
        }

        $this->newConnection->beginTransaction();
        try {
            $this->newConnection->table('post_comments')->truncate();
            PostComment::insert($data);
            $this->newConnection->commit();
        } catch (\Exception $e) {
            $this->newConnection->rollback();
        }
    }
}
