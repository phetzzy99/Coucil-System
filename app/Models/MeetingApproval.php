<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingApproval extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // ก่อนลบ MeetingApproval ให้ลบ MeetingApprovalDetail ที่เกี่ยวข้องด้วย
        static::deleting(function($meetingApproval) {
            $meetingApproval->approvalDetails()->delete();
        });
    }

    public function meeting() {
        return $this->belongsTo(Meeting::class, 'meeting_id', 'id');
    }
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

    public function meetingAgendaLecture() {
        return $this->belongsTo(MeetingAgendaLecture::class, 'meeting_agenda_lecture_id', 'id');
    }

    public function meetingAgendaSection() {
        return $this->belongsTo(MeetingAgendaSection::class, 'meeting_agenda_section_id', 'id');
    }

    public function meetingAgendaItems() {
        return $this->belongsTo(MeetingAgendaItems::class, 'meeting_agenda_items_id', 'id');
    }

    public function ruleOfMeeting() {
        return $this->belongsTo(RuleofMeeting::class, 'rule_of_meeting_id', 'id');
    }

    public function regulationMeeting() {
        return $this->belongsTo(RegulationMeeting::class, 'regulation_meeting_id', 'id');
    }

    // public function user() {
    //     return $this->belongsTo(User::class, 'user_id', 'id');
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvalDetails()
    {
        return $this->hasMany(MeetingApprovalDetail::class);
    }

    // public function approvalDetails()
    // {
    //     return $this->hasMany(MeetingApprovalDetail::class, 'meeting_approval_id');
    // }
}
