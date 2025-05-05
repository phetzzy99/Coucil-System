<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAgendaLecture extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean'
    ];

    // Relationship with parent section
    public function meetingAgendaSection()
    {
        return $this->belongsTo(MeetingAgendaSection::class, 'meeting_agenda_section_id');
    }

    public function meetingAgendaItems()
    {
        return $this->hasMany(MeetingAgendaItems::class, 'meeting_agenda_lecture_id');
    }

    public function meetingAgenda()
    {
        return $this->belongsTo(MeetingAgenda::class, 'meeting_agenda_id');
    }

    /**
     * Get committee opinions related to this lecture.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function committeeOpinions()
    {
        return $this->hasMany(CommitteeOpinion::class);
    }


    // public function committeeVotes() {
    //     return $this->hasMany(CommitteeVote::class, 'lecture_id');
    // }

    // Scope for active records
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Get ordered lectures
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

}
