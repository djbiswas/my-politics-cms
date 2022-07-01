<?php

namespace App\Console\Commands;

use App\Models\Politician;
use App\Models\PoliticianMeta;
use App\Models\PoliticanVote;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Importpolitician extends Command
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
    protected $signature = 'import:politician';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the older politicans data from older database';

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
      // $this->dumpPoliticians();
      // $this->dumpPoliticianMeta();
    }
}
