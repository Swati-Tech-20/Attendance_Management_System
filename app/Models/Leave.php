<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'user_id','leave_time','leave_date','start_date', 'end_date', 'reason', 'type','option_for_leave', 'status', 'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'emp_id');
    // }
}
