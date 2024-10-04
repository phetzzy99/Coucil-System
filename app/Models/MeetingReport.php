<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function committee_category() {
        return $this->belongsTo(CommitteeCategory::class, 'committee_category_id', 'id');
    }

    public function meeting_type() {
        return $this->belongsTo(MeetingType::class, 'meeting_type_id', 'id');
    }
}
