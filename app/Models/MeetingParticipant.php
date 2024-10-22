<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function meetingAgenda()
    {
        return $this->belongsTo(MeetingAgenda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
