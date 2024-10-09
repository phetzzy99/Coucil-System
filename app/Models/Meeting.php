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

    public function MeetingFormat() {
        return $this->belongsTo(MeetingFormat::class, 'meeting_format_id', 'id');
    }

    public function MeetingAgendaItems() {
        return $this->belongsTo(MeetingAgendaItems::class, 'meeting_agenda_items_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
