<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAgenda extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'meeting_agenda_date',
        'approval_deadline',
        'created_at',
        'updated_at'
    ];

    // Mutator สำหรับแปลงวันที่เป็น Carbon instance
    public function setApprovalDeadlineAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['approval_deadline'] = Carbon::parse($value);
        } else {
            $this->attributes['approval_deadline'] = $value;
        }
    }

    public function getApprovalDeadlineFormattedAttribute()
    {
        return $this->approval_deadline ? $this->approval_deadline->format('d/m/Y H:i') : '-';
    }

    public function getRemainingTimeAttribute()
    {
        if (!$this->approval_deadline) {
            return null;
        }

        $now = Carbon::now();
        if ($now->gt($this->approval_deadline)) {
            return 'หมดเวลารับรอง';
        }

        return $now->diffForHumans($this->approval_deadline, [
            'parts' => 2,
            'join' => ' และ '
        ]);
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->approval_deadline) {
            return false;
        }
        return Carbon::now()->gt($this->approval_deadline);
    }


    public function meeting_type()
    {
        return $this->belongsTo(MeetingType::class , 'meeting_type_id', 'id');
    }

    public function committeeCategory()
    {
        return $this->belongsTo(CommitteeCategory::class);
    }

    public function meetingFormat()
    {
        return $this->belongsTo(MeetingFormat::class , 'meeting_format_id', 'id');
    }

    public function ruleOfMeeting()
    {
        return $this->belongsTo(RuleofMeeting::class);
    }

    public function regulationMeeting()
    {
        return $this->belongsTo(RegulationMeeting::class);
    }

    public function meetingAgendaSections()
    {
        return $this->hasMany(MeetingAgendaSection::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(MeetingApproval::class);
    }

    public function sections()
    {
        return $this->hasMany(MeetingAgendaSection::class);
    }

    public function lectures()
    {
        return $this->hasMany(MeetingAgendaLecture::class);
    }

    public function items()
    {
        return $this->hasMany(MeetingAgendaItems::class);
    }

    public function adminApprovedBy()
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }

    public function adminEdits()
    {
        return $this->hasMany(MeetingAdminEdit::class);
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function getApprovalsBySections()
    {
        $approvalsBySection = [];

        foreach ($this->sections as $section) {
            $approvals = $section->approvalDetails()
                ->with(['meetingApproval.user.position'])
                ->get()
                ->map(function($detail) {
                    return [
                        'user' => $detail->meetingApproval->user,
                        'type' => $detail->approval_type,
                        'comments' => $detail->comments,
                        'created_at' => $detail->created_at,
                        'approval_id' => $detail->meetingApproval->id
                    ];
                });

            if ($approvals->isNotEmpty()) {
                $approvalsBySection[$section->id] = $approvals;
            }
        }

        return $approvalsBySection;
    }

    public function getSectionSummaries()
    {
        return $this->sections->mapWithKeys(function($section) {
            return [$section->id => $section->getApprovalSummary()];
        });
    }

}
