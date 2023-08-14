<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\XPIController;

class GetUserOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-user-otp {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if an user has an existing OTP device and returns it';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $token = XPIController::GetUserOtp($this->argument('username'));

        if(isset($token)) {
            $this->info("User has token with serial ".$token->tokenId);
        }
    }
}
