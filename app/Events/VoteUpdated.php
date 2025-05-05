<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lectureId;
    public $approveCount;
    public $rejectCount;
    public $newFeedback;

    public function __construct($lectureId, $approveCount, $rejectCount, $newFeedback = null)
    {
        $this->lectureId = $lectureId;
        $this->approveCount = $approveCount;
        $this->rejectCount = $rejectCount;
        $this->newFeedback = $newFeedback;
    }

    public function broadcastOn()
    {
        return new Channel('lecture-votes.' . $this->lectureId);
    }

    public function broadcastAs()
    {
        return 'vote-updated';
    }

    public function broadcastWith()
    {
        return [
            'approve_count' => $this->approveCount,
            'reject_count' => $this->rejectCount,
            'new_feedback' => $this->newFeedback
        ];
    }
}
