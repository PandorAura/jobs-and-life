<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'value',
        'raised_so_far',
        'type',
        'target_date',
        'priority'
    ];
    
    protected $casts = [
        'raised_so_far' => 'decimal:2',
        'value' => 'decimal:2',
        'target_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateRaisedAmount(): void
    {
        $this->raised_so_far = $this->allocations()->sum('amount');
        $this->save();
    }

    // protected static function booted()
    // {
    //     static::saving(function (Goal $goal) {
    //         if ($goal->raised_so_far > $goal->value) {
    //             throw new \Exception('Raised amount cannot exceed goal value');
    //         }
    //     });
    // }
}
