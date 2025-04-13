<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupGoalContribution extends Model
{
    protected $fillable = ['group_goal_id', 'user_id', 'amount'];
    public function goal() {
        return $this->belongsTo(GroupGoal::class, 'group_goal_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::created(function ($contribution) {
            $contribution->goal->increment('current_amount', $contribution->amount);
        });
    }
    public function groupGoal()
    {
        return $this->belongsTo(GroupGoal::class, 'group_goal_id');
    }
}
