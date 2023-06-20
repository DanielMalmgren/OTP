<?php

namespace App\Imports;

use App\Models\OTP;
use Maatwebsite\Excel\Concerns\ToModel;

class OTPImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $serial = $row[0];
        $pin = sprintf("%04d", $row[1]);
        $puk = sprintf("%06d",$row[2]);

        if(strlen($serial) != 12 || strlen($pin) != 4 || strlen($puk) != 6) {
            return null;
        }

        return new OTP([
            'serial' => $row[0],
            'pin'    => $row[1], 
            'puk'    => $row[2], 
        ]);
    }
}
