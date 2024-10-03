<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegulationMeeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function regulation_category() {
        return $this->belongsTo(RegulationCategory::class, 'regulation_category_id', 'id');
    }
}
