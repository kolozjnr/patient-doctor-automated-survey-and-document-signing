<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index () 
    {
        $getPatientsLike = Department::where('name', 'like', '%Patient%')->pluck('id');
        $getEmployeesLike = Department::where('name', 'like', '%Employee%')->pluck('id');
        //dd($getPatientsLike, $getEmployeesLike);
        $totalPatients = DB::table('department_user')->whereIn('department_id', $getPatientsLike)->count();
        $totalEmployees = DB::table('department_user')->whereIn('department_id', $getEmployeesLike)->count();
        $totalSurveys = \App\Models\Survey::count();
        $totalQuestions = \App\Models\Question::count();
        //dd($totalPatients, $totalEmployees, $totalSurveys, $totalQuestions);
        return view('dashboards.dashboard', compact('totalPatients', 'totalEmployees', 'totalSurveys', 'totalQuestions'));
    }

    public function getDashboardStats()
    {
        $getPatientsLike  = Department::where('name', 'like', '%Patient%')->pluck('id');
        $getEmployeesLike = Department::where('name', 'like', '%Employee%')->pluck('id');

        $totalPatients  = DB::table('department_user')->whereIn('department_id', $getPatientsLike)->count();
        $totalEmployees = DB::table('department_user')->whereIn('department_id', $getEmployeesLike)->count();
        $totalDownloads = DB::table('users')->where('status', 'Algemeen Gevuld')->count();
        $totalSurveys   = \App\Models\Survey::count();
        $totalQuestions = \App\Models\Question::count();

        // ── Monthly breakdown: past 6 months including current ──
        $months         = [];
        $monthLabels    = [];
        $monthlyPatients   = [];
        $monthlyEmployees  = [];
        $monthlyDownloads  = [];

        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end   = $date->copy()->endOfMonth();

            $monthLabels[] = $date->format('M'); // "Jan", "Feb", etc.

            $monthlyPatients[] = DB::table('department_user')
                ->whereIn('department_id', $getPatientsLike)
                ->whereBetween('created_at', [$start, $end])
                ->count();

            $monthlyEmployees[] = DB::table('department_user')
                ->whereIn('department_id', $getEmployeesLike)
                ->whereBetween('created_at', [$start, $end])
                ->count();

            $monthlyDownloads[] = DB::table('users')
                ->where('status', 'Algemeen Gevuld')
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        return response()->json([
            'totalPatients'     => $totalPatients,
            'totalEmployees'    => $totalEmployees,
            'totalSurveys'      => $totalSurveys,
            'totalQuestions'    => $totalQuestions,
            'totalDownloads'    => $totalDownloads,
            'chart' => [
                'labels'    => $monthLabels,
                'patients'  => $monthlyPatients,
                'employees' => $monthlyEmployees,
                'downloads' => $monthlyDownloads,
            ],
        ]);
    }
}
