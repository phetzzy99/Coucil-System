<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAdminEdit extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_agenda_id',
        'section_id',
        'comment',
        'edited_by',
        'edited_at'
    ];

    public function meetingAgenda()
    {
        return $this->belongsTo(MeetingAgenda::class);
    }

    public function section()
    {
        return $this->belongsTo(MeetingAgendaSection::class, 'section_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
