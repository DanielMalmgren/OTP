<?php

namespace App\Imports;

use App\Models\OTP;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OTPImport implements ToModel, WithMultipleSheets
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        logger(print_r($row, true));
        $serial = $row[0];
        $pin = sprintf("%04d", $row[1]);
        $puk = sprintf("%06d",$row[2]);
        $user = $row[3];

        if(strlen($serial) != 12 || strlen($pin) != 4 || strlen($puk) != 6) {
            return null;
        }

        $status = null;
        if(isset($user) && $user != '' && ctype_alpha(substr($user, 0, 1))) {
            $status = 'assigned';
        } else {
            $user = null;
        }

        return new OTP([
            'serial' => $serial,
            'pin'    => $pin,
            'puk'    => $puk,
            'status' => $status,
            'user'   => $user,
        ]);
    }

    public function sheets(): array
    {
        return [
            0 => new OTPImport(),
        ];
    }
}
