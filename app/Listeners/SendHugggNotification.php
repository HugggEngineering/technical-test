<?php

namespace App\Listeners;

use App\Events\HugggSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

use Illuminate\Support\Facades\Log;
use View;

class SendHugggNotification
{
    /**
     * Handle the event.
     *
     * @param  HugggSent $event
     * @return void
     */
    public function handle(HugggSent $event)
    {
        if ($event->recipient) {
            $this->sendSmsNotification($event);
            $this->sendPushNotification($event);
        }
    }

    /**
     * @param HugggSent $sentHuggg
     * @return bool
     */
    private function sendPushNotification(HugggSent $sentHuggg)
    {
        if (!$sentHuggg->recipient->active) {
            return;
        }

        $message = $this->makePushMessage($sentHuggg);
        dispatch(new \App\Jobs\SendNewHugggPush($sentHuggg->hug, $message));
    }

    private function makePushMessage(HugggSent $sentHuggg)
    {
        $params = [
            'user' => $sentHuggg->user->first_name,
            'branded_huggg' => $sentHuggg->hug->labelWithHuggg(),
        ];

        return View::make("push.new-huggg", $params)->render();
    }

    /**
     * @param HugggSent $sentHuggg
     */
    private function sendSMSNotification(HugggSent $sentHuggg)
    {
        if (!$sentHuggg->recipient->phone_number) {
            return;
        }

        $message = $this->makeSMSMessage($sentHuggg);
        dispatch(new \App\Jobs\SendSMS($sentHuggg->hug->receiver->phone_number, $message));
    }

    public static function makeSMSMessage(HugggSent $sentHuggg)
    {
        $messageTemplate = $sentHuggg->recipient->active ? 'sms.new-huggg-known-user' : 'sms.new-huggg-unknown-user';
        $purchase = $sentHuggg->hug->load('purchase.brand')->purchase;
        $brand = $purchase->brand;

        $messageParams = [
            'user' => $sentHuggg->user->first_name . ' ' . $sentHuggg->user->surname,
            'branded_huggg' => $sentHuggg->hug->labelWithHuggg(),
            'url' => config('huggg.urls.launch') . '/' . $sentHuggg->hug->shortcode,
            'text' => $sentHuggg->message ?: "",
            'new_locations' => $purchase->locationsLabel(),
        ];

        $text = View::make($messageTemplate, $messageParams)->render();

        return preg_replace('~\R~u', "\r\n", $text);
    }
}
