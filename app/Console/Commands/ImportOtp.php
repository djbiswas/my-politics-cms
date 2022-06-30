<?php

namespace App\Console\Commands;

use App\Models\OtpData;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportOtp extends Command
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
    protected $signature = 'import:otp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the older otp data from older database';

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
        $otps = $this->oldConnection->table('otp_data')->get();
        foreach($otps as $otp) {
            $otp = [
                'otp' => $otp->otp,
                'expiry_date' => $otp->expiry_date,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $data[] = $otp;
        }

        $this->newConnection->beginTransaction();
        try {
            $this->newConnection->table('otp_datas')->truncate();
            OtpData::insert($data);
            $this->newConnection->commit();
        } catch (\Exception $e) {
            $this->newConnection->rollback();
        }
    }
}
