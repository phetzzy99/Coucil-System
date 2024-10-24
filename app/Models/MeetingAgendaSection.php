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

    // แก้ไขการ join เพื่อเพิ่มประสิทธิภาพ
    public function approvalDetails()
    {
        return $this->hasMany(MeetingApprovalDetail::class, 'meeting_agenda_section_id')
                    ->with(['meetingApproval.user.position']); // eager load user และ position
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
