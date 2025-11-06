<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Verifikasi;
use App\Http\Requests\DataTableRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Throwable;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use App\Models\FeedbackModels;
use Illuminate\Support\Facades\Auth;

class PerbaikanController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Report $report)
  {

    return Inertia::render('Report/Perbaikan', [
      'report'      => $report,
      'users'       => User::get(['id', 'name']),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */


  /**
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return Report::get();
  }

  /**
   * @param \App\Http\Requests\DataTableRequest $request
   * @return \Illuminate\Http\Response
   */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    return Report::where(function (Builder $query) use ($request) {
      $search = '%' . $request->search . '%';
      $model = $query->getModel();

      foreach ($model->getFillable() as $column) {
        $query->orWhere($column, ' like', $search);
      }

      $query->orWhereRelation('createdBy', 'name', 'like', $search);
      $query->orWhereRelation('bagian', 'nama', 'like', $search);
    })
      ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
      // ->when(!$user->hasRole(['superuser', 'admin']), fn (Builder $query) => $query->where('created_by_id', $user->id))
      ->when(!$user->hasRole(['superuser', 'admin']), function (Builder $query) use ($user) {
        $query->where('created_by_id', $user->id)
            ->where('status', '2')
            ->orWhereExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('report_user')
                    ->whereColumn('report_user.report_id', 'reports.id')
                    ->where('report_user.user_id', $user->id);
            });
    })   
      ->select([
        'id',
        'kode',
        'bagian_sistem',
        'bagian_hardware',
        'bagian_id',
        'tanggal',
        'bagian_pelapor',
        'kategori',
        'kendala',
        'dampak',
        'kontak',
        'url',
        'status',
        'catatan',
        'created_by_id',
        'created_at',
        'updated_at',
      ])->where('status', '2')
      ->paginate($request->per_page ?: 10);
  }
}
