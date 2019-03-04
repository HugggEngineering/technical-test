<?php

namespace App\Events;

use App\Hug;
use App\Message;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

// implements ShouldBroadcast
class HugggSent
{
    use InteractsWithSockets, SerializesModels;

    /**
     *  HugVariable
     */
    public $user;
    public $hugCount;
    public $hug;
    public $recipient;
    public $message;

    /**
     * HugggSent constructor.
     * @param User $user
     * @param Hug $hug
     * @param User $recipient
     * @param Message $message [string]
     */
    public function __construct(User $user, Hug $hug, ?User $recipient, $message)
    {
        $this->user = $user;
        $this->hug = $hug;
        $this->recipient = $recipient;
        $this->message = $message;
    }
}
