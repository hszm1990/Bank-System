<?php

namespace App\Listeners;

use App\Events\PaymentEvent;
use App\Notifications\PaymentNotification;

class NotifyUserAfterTransaction
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentEvent $event): void
    {
        $event->paymentEventDTO->sourceUser->notify(new PaymentNotification(
            trans('messages.decrease_transaction', ['amount' => $event->paymentEventDTO->amount])
        ));

        $event->paymentEventDTO->destinationUser->notify(new PaymentNotification(
            trans('messages.increase_transaction', ['amount' => $event->paymentEventDTO->amount])
        ));
    }
}
