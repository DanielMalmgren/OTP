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
        $user = $row[3];

        if(strlen($serial) != 12 || strlen($pin) != 4 || strlen($puk) != 6) {
            return null;
        }

        $status = null;
        if(isset($user) && $user != '') {
            $status = 'assigned';
        }

        return new OTP([
            'serial' => $serial,
            'pin'    => $pin,
            'puk'    => $puk,
            'status' => $status,
            'user'   => $user,
        ]);
    }
}
