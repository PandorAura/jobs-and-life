<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupGoalContribution extends Model
{
    public function goal() {
        return $this->belongsTo(GroupGoal::class, 'group_goal_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}
