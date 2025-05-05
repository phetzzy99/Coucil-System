<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentExport extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_agenda_id',
        'file_name',
        'file_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meetingAgenda()
    {
        return $this->belongsTo(MeetingAgenda::class);
    }
}
