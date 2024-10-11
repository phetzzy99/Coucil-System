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

    public function meetingAgendaSection()
    {
        return $this->belongsTo(MeetingAgendaSection::class, 'meeting_agenda_section_id', 'id');
    }

    public function meetingAgenda()
    {
        return $this->belongsTo(MeetingAgenda::class, 'meeting_agenda_id', 'id');
    }

    public function ruleOfMeeting()
    {
        return $this->belongsTo(RuleofMeeting::class, 'rule_of_meeting_id', 'id');
    }

    public function regulationMeeting()
    {
        return $this->belongsTo(RegulationMeeting::class, 'regulation_meeting_id', 'id');
    }

}
