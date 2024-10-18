<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainMeeting extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [];

    public function meetingTypes()
    {
        return $this->belongsToMany(MeetingType::class);
    }

    public function committeeCategories()
    {
        return $this->belongsToMany(CommitteeCategory::class);
    }
}
