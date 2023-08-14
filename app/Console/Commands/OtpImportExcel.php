<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\OTPImport;
use Maatwebsite\Excel\Facades\Excel;

class OtpImportExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:otp-import-excel {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import PINs and PUKs from Excel file';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info($this->argument('filename'));

        Excel::import(new OTPImport, $this->argument('filename'));

    }
}
