<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingApprovalDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function meetingApproval() {
        return $this->belongsTo(MeetingApproval::class, 'meeting_approval_id', 'id');
    }

    public function meetingAgendaSection() {
        return $this->belongsTo(MeetingAgendaSection::class, 'meeting_agenda_section_id', 'id');
    }
}
