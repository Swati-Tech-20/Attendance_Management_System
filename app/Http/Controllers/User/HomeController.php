<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->month;
        $previousMonth = Carbon::now()->subMonth()->month;

        // Get the registration date and the first day of the current month
        $registrationDate = $user->created_at->startOfDay();
        $firstDayOfCurrentMonth = Carbon::now()->startOfMonth()->startOfDay();

        // Calculate total approved leaves
        $totalLeaves = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        // Calculate previous month's total present and absent days
        if ($registrationDate->lessThan(Carbon::now()->subMonth()->endOfMonth())) {
            $previousMonthTotalPresent = Attendance::where('user_id', $user->id)
                ->whereMonth('punch_in', $previousMonth)
                ->whereNotNull('punch_in')
                ->count();

            $totalDaysInPreviousMonth = Carbon::now()->subMonth()->daysInMonth;
            $previousMonthTotalAbsent = $totalDaysInPreviousMonth - $previousMonthTotalPresent;
        } else {
            $previousMonthTotalPresent = 0;
            $previousMonthTotalAbsent = 0;
        }

        // Calculate current month's approved leaves
        $currentMonthLeaves = Leave::where('user_id', $user->id)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'approved')
            ->count();

        // Determine the start date for attendance calculation in the current month
        $attendanceStartDate = $registrationDate->greaterThan($firstDayOfCurrentMonth) 
            ? $registrationDate 
            : $firstDayOfCurrentMonth;

        // Calculate total days in the current month up to today
        $totalDaysUpToToday = Carbon::now()->diffInDays($firstDayOfCurrentMonth) + 1;

        // Calculate current month's total present days
        $currentMonthTotalPresent = Attendance::where('user_id', $user->id)
            ->whereMonth('punch_in', $currentMonth)
            ->whereDate('punch_in', '>=', $attendanceStartDate)
            ->whereNotNull('punch_in')
            ->count();

        // Calculate current month's total absent days
        $currentMonthTotalAbsent = $totalDaysUpToToday - $currentMonthTotalPresent;

        // Prepare data to be passed to the view
        $data = [
            'totalLeaves' => $totalLeaves,
            'previousMonthTotalPresent' => $previousMonthTotalPresent,
            'previousMonthTotalAbsent' => $previousMonthTotalAbsent,
            'currentMonthLeaves' => $currentMonthLeaves,
            'currentMonthTotalPresent' => $currentMonthTotalPresent,
            'currentMonthTotalAbsent' => $currentMonthTotalAbsent,
        ];

        return view('user.index')->with(['data' => $data]);
    }
}
