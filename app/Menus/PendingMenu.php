<?php

namespace App\Menus;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendingMenu extends Menu
{
  /**
   * @inheritdoc
   */
  public function count() : int
  {
    $user = auth()->user();
  
      if ($this->hasRole($user, ['superuser', 'admin'])) {
          return Report::where('status', 4)->count();
      } else {
          return Report::where(function ($query) use ($user) {
                $query->where('created_by_id', $user->id)
                    ->orWhereExists(function ($subquery) use ($user) {
                        $subquery->select(DB::raw(1))
                            ->from('report_user')
                            ->whereColumn('report_user.report_id', 'reports.id')
                            ->where('report_user.user_id', $user->id);
                    });
          })
          ->where('status', 4)
          ->count();
      }
  }

  private function hasRole($user, $roles)
  {
      return $user->roles()->whereIn('name', $roles)->exists();
  }

  
}
