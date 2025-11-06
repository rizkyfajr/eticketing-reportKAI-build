<?php

namespace App\Menus;

use App\Models\JobList;
use Illuminate\Support\Facades\Auth;

class JobListMenu extends Menu
{
  /**
   * @inheritdoc
   */
  public function count() : int
{
    $user = auth()->user();

    if ($this->hasRole($user, ['superuser', 'admin'])) {
        return JobList::whereHas('report', function ($query) {
            $query->where('status', '!=', 3)
                  ->where('status', '!=', 0);
        })->count();
    } else {
        return JobList::join('reports', 'job_lists.report_id', '=', 'reports.id')
                      ->join('report_user', 'reports.id', '=', 'report_user.report_id')
                      ->where('report_user.user_id', $user->id)
                      ->where('reports.status', '!=', 3)
                      ->where('reports.status', '!=', 0)
                      ->count();
    }
}

    private function hasRole($user, $roles)
    {
        return $user->roles()->whereIn('name', $roles)->exists();
    }
}
