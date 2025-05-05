<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeOpinion extends Model
{
    protected $fillable = [
        'meeting_agenda_lecture_id',
        'user_id',
        'opinion',
        'vote_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lecture()
    {
        return $this->belongsTo(MeetingAgendaLecture::class, 'meeting_agenda_lecture_id');
    }
}
