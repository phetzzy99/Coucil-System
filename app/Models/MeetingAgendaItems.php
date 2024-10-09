<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAgendaItems extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function meetingAgendaLecture()
    {
        return $this->belongsTo(MeetingAgendaLecture::class, 'meeting_agenda_lecture_id', 'id');
    }
}
