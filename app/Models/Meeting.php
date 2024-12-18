<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function meetingType() {
        return $this->belongsTo(MeetingType::class, 'meeting_type_id', 'id');
    }

    public function committeeCategory() {
        return $this->belongsTo(CommitteeCategory::class, 'committee_category_id', 'id');
    }

    public function meetingFormat() {
        return $this->belongsTo(MeetingFormat::class, 'meeting_format_id', 'id');
    }

    public function meetingAgenda() {
        return $this->belongsTo(MeetingAgenda::class, 'meeting_agenda_id', 'id');
    }

    public function ruleOfMeeting()
    {
        // return $this->belongsTo(RuleofMeeting::class, 'rule_of_meeting_id', 'id');
        return $this->belongsToMany(RuleOfMeeting::class);
    }

    public function regulationMeeting()
    {
        return $this->belongsTo(RegulationMeeting::class, 'regulation_meeting_id', 'id');
    }

    public function meetingAgendaSection() {
        return $this->belongsTo(MeetingAgendaSection::class, 'meeting_agenda_section_id', 'id');
    }

    public function meetingAgendaLecture() {
        return $this->belongsTo(MeetingAgendaLecture::class, 'meeting_agenda_lecture_id', 'id');
    }

    public function meetingAgendaItems() {
        return $this->belongsTo(MeetingAgendaItems::class, 'meeting_agenda_items_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
