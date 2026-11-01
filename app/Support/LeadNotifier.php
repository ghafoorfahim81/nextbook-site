<?php

namespace App\Support;

use App\Mail\LeadReceived;
use App\Models\ContactMessage;
use App\Models\DemoRequest;
use Illuminate\Support\Facades\Mail;
use Throwable;

class LeadNotifier
{
    /**
     * Emails the team about a new lead after the visitor already has their answer, so a slow
     * mail server never makes the form hang, and no queue worker is needed on a shared host.
     * If sending fails the error is logged and nothing else happens: the lead is already saved.
     */
    public static function send(DemoRequest|ContactMessage $lead): void
    {
        $recipients = config('site.leads_to');

        if (! $recipients) {
            return;
        }

        dispatch(function () use ($lead, $recipients) {
            try {
                Mail::to($recipients)->send(new LeadReceived($lead));
            } catch (Throwable $e) {
                report($e);
            }
        })->afterResponse();
    }
}
