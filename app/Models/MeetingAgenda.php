<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAgenda extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function meeting_type()
    {
        return $this->belongsTo(MeetingType::class , 'meeting_type_id', 'id');
    }

    public function meetingFormat()
    {
        return $this->belongsTo(MeetingFormat::class , 'meeting_format_id', 'id');
    }

    public function meetingAgendaSections()
    {
        return $this->hasMany(MeetingAgendaSection::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
