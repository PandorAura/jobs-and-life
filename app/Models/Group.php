<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $fillable = [
        'name',
        'created_by',  // User who created the group
    ];

    // Define the relationship to the User model
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function users() {
        return $this->belongsToMany(User::class, 'group_user');
    }
    
    public function goals() {
        return $this->hasMany(GroupGoal::class);
    }
    public function group(){
        return $this->belongsTo(Group::class);
    }
}
