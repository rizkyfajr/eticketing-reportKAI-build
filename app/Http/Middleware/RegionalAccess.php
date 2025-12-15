<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware RegionalAccess
 *
 * Middleware ini memastikan Admin Wilayah hanya bisa mengakses/edit data
 * yang sesuai dengan region mereka
 *
 * Usage di routes:
 * Route::put('/master-machines/{machine}', ...)->middleware('regional.access:master_machine');
 */
class RegionalAccess
{
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
  * @param  string  $modelParam  Nama parameter route (contoh: 'master_machine', 'working_report')
  * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
  */
  public function handle(Request $request, Closure $next, $modelParam = null)
  {
    $user = auth()->user();

    // Super Admin atau user tanpa region_id: boleh akses semua
    if (!$user || !$user->region_id) {
      return $next($request);
    }

    // Hanya cek untuk Admin Wilayah
    if (!$user->hasRole('admin-wilayah')) {
      return $next($request);
    }

    // Jika ada parameter model di route (untuk edit/update/delete)
    if ($modelParam && $request->route($modelParam)) {
      $model = $request->route($modelParam);

      // Cek apakah model punya region_id
      if (method_exists($model, 'getAttribute') && $model->getAttribute('region_id')) {
        // Bandingkan region_id model dengan region_id user
        if ($model->region_id !== $user->region_id) {
          abort(403, 'Anda tidak memiliki akses ke data wilayah ini. Anda hanya dapat mengakses data untuk ' . ($user->region ? $user->region->name : 'wilayah Anda'));
        }
      }
    }

    return $next($request);
  }
}
