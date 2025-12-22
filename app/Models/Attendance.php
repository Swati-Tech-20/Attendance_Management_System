<?php

namespace App\Models;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model

{

    // protected $table = 'attendances';
    protected $fillable = [
        'user_id',
        'punch_in',
        'punch_out',
        // other fields
    ];
    protected $casts = [
        'break_in' => 'datetime',
        'break_out' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function breaks()
{
    return $this->hasMany(Breaks::class);
}
    
    public function employee()
   {
      return $this->belongsTo(Employee::class, 'emp_id');
   }
// public function schedules()
//     {
//         return $this->belongsTo(Schedule::class);
//     }
}
