<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingResolution extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'resolution_date',
        'created_at',
        'updated_at',
    ];

    public function committeeCategory()
    {
        return $this->belongsTo(CommitteeCategory::class, 'committee_category_id', 'id');
    }

    public function meetingType()
    {
        return $this->belongsTo(MeetingType::class, 'meeting_type_id', 'id');
    }

    public function meetingAgenda()
    {
        return $this->belongsTo(MeetingAgenda::class, 'meeting_agenda_id', 'id');
    }

    public function meetingAgendaSection()
    {
        return $this->belongsTo(MeetingAgendaSection::class, 'meeting_agenda_section_id', 'id');
    }

    public function meetingAgendaLecture()
    {
        return $this->belongsTo(MeetingAgendaLecture::class, 'meeting_agenda_lecture_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
