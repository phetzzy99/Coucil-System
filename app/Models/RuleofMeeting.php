<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleofMeeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rule_category() {
        return $this->belongsTo(RuleCategory::class, 'rule_category_id', 'id');
    }

    public function meetingAgendas()
    {
        return $this->belongsToMany(MeetingAgenda::class, 'meeting_agenda_rule_of_meeting');
    }
}
