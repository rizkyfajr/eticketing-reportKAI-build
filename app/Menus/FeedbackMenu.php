<?php

namespace App\Menus;

use App\Models\FeedbackModels;
use Illuminate\Support\Facades\Auth;

class FeedbackMenu extends Menu
{
  /**
   * @inheritdoc
   */
  public function count() : int
{
    $user = auth()->user();

    if ($this->hasRole($user, ['superuser', 'admin'])) {
        return FeedbackModels::whereHas('report', function ($query) {
            $query->where('status', '!=', 3);
        })->count();
    } else {
        return FeedbackModels::whereHas('report', function ($query) use ($user) {
            $query->where('created_by_id', $user->id)
                  ->where('status', '!=', 3);
        })->count();
    }
}

    private function hasRole($user, $roles)
    {
        return $user->roles()->whereIn('name', $roles)->exists();
    }
}
