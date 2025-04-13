<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupGoal extends Model
{
     // Specify the fields that are mass assignable
     protected $fillable = [
        'title',
        'target_amount',
        'current_amount',
        'group_id',
        'created_by',
    ];

    // If you are using the "casts" feature (optional)
    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];
    public function group() {
        return $this->belongsTo(Group::class);
    }
    
    public function contributions() {
        return $this->hasMany(GroupGoalContribution::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
