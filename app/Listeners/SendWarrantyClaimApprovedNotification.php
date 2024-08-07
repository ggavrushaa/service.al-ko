<?php

namespace App\Listeners;

use App\Models\UserPartner;
use App\Mail\RequestApproved;
use Illuminate\Support\Facades\Mail;
use App\Events\WarrantyClaimApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWarrantyClaimApprovedNotification
{
    public function __construct()
    {
        //
    }

    public function handle(WarrantyClaimApproved $event)
    {
        $servicePartnerId = $event->warrantyClaim->service_partner;
        $servicePartner = UserPartner::on('mysql')->find($servicePartnerId);

        if ($servicePartner) {
            // $email = $servicePartner->email;
            $email = 'sanna.260416@gmail.com';
            $requestNumber = $event->warrantyClaim->number;

            Mail::to($email)->send(new RequestApproved($requestNumber));
        }
    }
}
