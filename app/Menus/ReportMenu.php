<?php

namespace App\Menus;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportMenu extends Menu
{
  /**
   * @inheritdoc
   */
  public function count() : int
    {
        $user = auth()->user();

        if ($this->hasRole($user, ['superuser'])) {
            return Report::count();
        } else {
            return Report::where('created_by_id', $user->id)
                ->orWhereExists(function ($query) use ($user) {
                    $query->select(DB::raw(1))
                        ->from('report_user')
                        ->whereColumn('report_user.report_id', 'reports.id')
                        ->where('report_user.user_id', $user->id);
                })
                ->count();
        }
    }



    private function hasRole($user, $roles)
    {
        return $user->roles()->whereIn('name', $roles)->exists();
    }

}
