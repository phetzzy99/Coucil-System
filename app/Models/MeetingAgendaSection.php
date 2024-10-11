<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAgendaSection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function meetingAgendaLectures()
    {
        return $this->hasMany(MeetingAgendaLecture::class, 'meeting_agenda_section_id');
    }

    public function meetingAgendaItems()
    {
        return $this->hasMany(MeetingAgendaItems::class, 'meeting_agenda_section_id', 'id');
    }

    public function meetingAgenda()
    {
        return $this->belongsTo(MeetingAgenda::class, 'meeting_agenda_id');
    }
}
