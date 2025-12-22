<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breaks extends Model
{
    protected $table = 'breaks';
    
    protected $fillable = [
        'user_id',
        'attendance_id',
        'break_in',
        'break_out',
        // other fields
    ];
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
