<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'value', 'type', 'target_date', 'priority'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
