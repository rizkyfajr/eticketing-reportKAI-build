<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FeedbackModels;
use App\Models\Joblist;
use App\Models\ReportUser;

class DashboardController extends Controller
{
  /**
   * @return \Illuminate\Http\Response
   */
  public function index(Report $report)
  {

    $report = Report::all();
    $count_drafting = $report->where('status', '0')->count();
    // $count_terkirim = $report->where('status', '1')->count();
    // $count_perbaikan = $report->where('status', '2')->count();
    // $count_pending = $report->where('status', '4')->count();
    // $count_selesai = $report->where('status', '3')->count();
    // $count_joblist = $report->where('status', '5')->count();
    // $joblist = Joblist::all();
    // $count_joblist = $joblist->count();

    $user = auth()->user();

    if ($this->hasRole($user,['superuser', 'admin'])) {
      $count_feedback = FeedbackModels::whereHas('report', function ($query) {
        $query->where('status', '!=', 3);
      })->count();
    } else {
      $count_feedback = FeedbackModels::whereHas('report', function ($query) use ($user) {
        $query->where('created_by_id', $user->id)
          ->where('status', '!=', 3);
      })->count();
    }
    

    $count_pending = $report->where('status', '4')->count();

    if ($this->hasRole($user, ['superuser'])) {
      $count_datalaporin = Report::count();
    } else {
      $count_datalaporin = Report::where('created_by_id', $user->id)
            ->orWhereExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('report_user')
                    ->whereColumn('report_user.report_id', 'reports.id')
                    ->where('report_user.user_id', $user->id);
            })
            ->count();
    }

    if ($this->hasRole($user, ['superuser', 'admin'])) {
        $count_joblist = JobList::whereHas('report', function ($query) {
            $query->where('status', '!=', 3)
                  ->where('status', '!=', 0);
        })->count();
    } else {
        $count_joblist = JobList::join('reports', 'job_lists.report_id', '=', 'reports.id')
                      ->join('report_user', 'reports.id', '=', 'report_user.report_id')
                      ->where('report_user.user_id', $user->id)
                      ->where('reports.status', '!=', 3)
                      ->where('reports.status', '!=', 0)
                      ->count();
    }

    if ($this->hasRole($user, ['superuser', 'admin'])) {
      $count_terkirim = Report::where('status', 1)->count();
    } else {
        $count_terkirim = Report::where(function ($query) use ($user) {
            $query->where('created_by_id', $user->id)
                ->orWhereExists(function ($subquery) use ($user) {
                    $subquery->select(DB::raw(1))
                        ->from('report_user')
                        ->whereColumn('report_user.report_id', 'reports.id')
                        ->where('report_user.user_id', $user->id);
                });
        })->where('status', 1)->count();
    }

    if ($this->hasRole($user, ['superuser', 'admin'])) {
      $count_perbaikan = Report::where('status', 2)->count();
    } else {
        $count_perbaikan = Report::where(function ($query) use ($user) {
            $query->where('created_by_id', $user->id)
                ->orWhereExists(function ($subquery) use ($user) {
                    $subquery->select(DB::raw(1))
                        ->from('report_user')
                        ->whereColumn('report_user.report_id', 'reports.id')
                        ->where('report_user.user_id', $user->id);
                });
        })->where('status', 2)->count();
    }
    
    if ($this->hasRole($user, ['superuser', 'admin'])) {
      $count_selesai = Report::where('status', 3)->count();
    } else {
        $count_selesai = Report::where(function ($query) use ($user) {
            $query->where('created_by_id', $user->id)
                ->orWhereExists(function ($subquery) use ($user) {
                    $subquery->select(DB::raw(1))
                        ->from('report_user')
                        ->whereColumn('report_user.report_id', 'reports.id')
                        ->where('report_user.user_id', $user->id);
                });
        })->where('status', 3)->count();
    }
    
    if ($this->hasRole($user, ['superuser', 'admin'])) {
      $count_pending = Report::where('status', 4)->count();
    } else {
        $count_pending = Report::where(function ($query) use ($user) {
            $query->where('created_by_id', $user->id)
                ->orWhereExists(function ($subquery) use ($user) {
                    $subquery->select(DB::raw(1))
                        ->from('report_user')
                        ->whereColumn('report_user.report_id', 'reports.id')
                        ->where('report_user.user_id', $user->id);
                });
        })->where('status', 4)->count();
    }
    
    if ($this->hasRole($user, ['superuser', 'admin'])) {
      $count_assign = ReportUser::whereHas('report', function ($query) {
          $query->whereColumn('reports.id', 'report_user.report_id');
          $query->where('user_id', '!=', 3);
      })->count();
    } else {
        $count_assign = Report::where(function ($query) use ($user) {
            $query->where('created_by_id', $user->id)
                ->orWhereExists(function ($subquery) use ($user) {
                    $subquery->select(DB::raw(1))
                        ->from('report_user')
                        ->whereColumn('report_user.report_id', 'reports.id')
                        ->where('report_user.user_id', $user->id);
                });
        })->count();
    }

    // $count_assign = User::count();

    // dd($count_joblist);

    // modal data laporin 
    $data_laporin =  Report::limit(5)->get();

    // dd($report);

    return Inertia::render('Dashboard', [
      'report' => $report,
      'count_drafting' => $count_drafting,
      'count_terkirim' => $count_terkirim,
      'count_perbaikan' => $count_perbaikan,
      'count_feedback' => $count_feedback,
      'count_pending' => $count_pending,
      'count_selesai' => $count_selesai,
      'count_joblist' => $count_joblist,
      'count_datalaporin' => $count_datalaporin,
      'count_assign' => $count_assign,
      'data_laporin' => $data_laporin,
      'users' => User::get(['id', 'name']),
    ]);
  }
  public function get()
  {
    return Report::get();
  }

  private function hasRole($user, $roles)
    {
        return $user->roles()->whereIn('name', $roles)->exists();
    }
}
