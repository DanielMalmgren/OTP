<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RicorocksDigitalAgency\Soap\Facades\Soap;
use App\Http\Controllers\XPIController;

class OtpActivate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:otp-activate {username} {serial}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate OTP device and assign it to an user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        XPIController::ActivateOtp($this->argument('username'), $this->argument('serial'));
    }
}
