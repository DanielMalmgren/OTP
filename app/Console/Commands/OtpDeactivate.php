<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\XPIController;

class OtpDeactivate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:otp-deactivate {username} {serial}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate an OTP device and remove it from an user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        XPIController::DeactivateOtp($this->argument('username'), $this->argument('serial'));
    }
}
