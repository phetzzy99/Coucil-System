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
}
