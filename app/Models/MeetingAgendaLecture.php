<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAgendaLecture extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function meetingAgendaItems()
    {
        return $this->hasMany(MeetingAgendaItems::class, 'meeting_agenda_lecture_id');
    }
}
