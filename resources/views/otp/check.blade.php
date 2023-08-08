@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Information</div>
                <div class="card-body">

                    Namn: {{$name}}<br><br>
                    @if($otp)
                        Dosans serienummer: {{$otp->serial}}<br><br>
                        Ursprunglig PIN-kod: {{$otp->pin}}<br><br>
                        PUK-kod: {{$otp->puk}}<br><br>
                    @else
                        Denna användare har ingen utfärdad OTP-dosa.<br><br>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
