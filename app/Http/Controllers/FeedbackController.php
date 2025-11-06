<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTableRequest;
use App\Models\Feedback;
use App\Models\FeedbackModels;
use Inertia\Inertia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Report;
use App\Models\Verifikasi;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
// use Inertia\Inertia;
use Throwable;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
  public function index(FeedbackModels $feedback, Report $report)
  {
    return Inertia::render('Feedback/Index', [
      'report'      => $report,
      'feedback'    => $feedback,
      'users'       => User::get(['id', 'name']),
    ]);
  }
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    return FeedbackModels::where(function (Builder $query) use ($request) {
      $search = '%' . $request->search . '%';
      $model = $query->getModel();

      foreach ($model->getFillable() as $column) {
        $query->orWhere($column, ' like', $search);
      }

      $query->orWhereRelation('createdBy', 'name', 'like', $search);
    })
      ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
      ->when(!$user->hasRole(['admin']), function (Builder $query) use ($user) {
          $query->where(function (Builder $query) use ($user) {
              $query->where('created_by_id', $user->id)
                  ->orWhereHas('report', function (Builder $query) use ($user) {
                      $query->whereColumn('reports.id', 'feedbacks.report_id')
                          ->where('created_by_id', $user->id);
                  });
          });
      })
      ->with('report')
      ->select([
        'report_id',
        'id',
        'kode',
        'bagian_sistem',
        'tanggal',
        'bagian_pelapor',
        'kategori',
        'kendala',
        'dampak',
        'kontak',
        'url',
        'status',
        'catatan',
        'balasan',
        'balasan1',
        'created_by_id',
        'created_at',
        'updated_at',

      ])
      ->whereHas('report', function (Builder $query) {
          $query->where('status', '!=', 3);
      })
      ->paginate($request->per_page ?: 10);
  }
  
  public function get()
  {
    return FeedbackModels::get();
  }
  public function detail(Report $report)
  {
    return Inertia::render('Feedback/Balas', [
      'report'      => $report,
      'users'       => User::get(['id', 'name']),
    ]);
  }
 
  public function store(Request $request)
  {
    $request->validate([
      'kode'             => 'nullable|unique:feedback',
      'bagian_sistem'    => 'nullable|string',
      'tanggal'          => 'nullable|date',
      'bagian_pelapor'   => 'nullable|string',
      'kategori'         => 'nullable|string',
      'kendala'          => 'nullable|string',
      'dampak'           => 'nullable|string',
      'kontak'           => 'nullable|string',
      'url'              => 'nullable|string',
      'catatan'          => 'nullable|string',
      'balasan'          => 'nullable|string',
      'balasan1'          => 'nullable|string',
    ]);
    $feedback = FeedbackModels::create([
      'kode'             => $request->kode,
      'bagian_sistem'    => $request->bagian_sistem,
      'tanggal'          => $request->tanggal,
      'bagian_pelapor'   => $request->bagian_pelapor,
      'kategori'         => $request->kategori,
      'kendala'          => $request->kendala,
      'dampak'           => $request->dampak,
      'kontak'           => $request->kontak,
      'url'              => $request->url,
      'catatan'          => $request->catatan,
      'balasan'          => $request->balasan,
      'balasan1'         => $request->balasan1,
      'created_by_id'    => $request->user()->id,
    ]);

    if ($feedback) {
      return redirect()->back()->with('success', __(
        'feedback `:bagian_sistem` Berhasil ditambah',
        [
          'bagian_sistem' => $request->bagian_sistem,
        ]
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t create feedback',
    ));
  }

  public function terkirim()
  {
    FeedbackModels::where()->update([
      'status' => '1',
    ]);
    return Inertia::render('Report/Terkirim');
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, FeedbackModels $feedback)
  {
      $request->validate([
        'balasan1'         => 'nullable|string',
      ]);

      DB::beginTransaction();

      try {

        $feedback->update([
          'balasan1'         => $request->balasan1,
          // 'created_by_id'    => $request->user()->id,
        ]);

        DB::commit();

        return redirect()->back()->with('success', __(
                  'balasan `:balasan` Berhasil Terkirim', [
                      'balasan' => $request->balasan,
                  ]));
      } catch (\Exception $e){
        DB::rollBack();

        return redirect()->back()->with('error', __(
              'can\'t update report',
          ));
      }
  }
}