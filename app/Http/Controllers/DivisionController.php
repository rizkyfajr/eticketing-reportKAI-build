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
use App\Models\DivisionModels;
use App\Models\FeedbackModels;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(DivisionModels $division)
  {

    return Inertia::render('Division/Index', [
      'division'      => $division,
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
  public function store(Request $request)
  {
    $request->validate([
      'division_number'             => 'nullable|unique:division',
      'division_name'    => 'nullable|string',
     

    ]);

    $division = DivisionModels::create([
      'division_number'             => $request->division_number,
      'division_name'    => $request->division_name,
    

      'created_by_id'    => $request->user()->id,
    ]);

    if ($division) {
      return redirect()->back()->with('success', __(
        'division `:division_name` Berhasil ditambah',
        [
          'division_name' => $request->division_name,
        ]
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t create division',
    ));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, DivisionModels $division)
  {
    $request->validate([
      'division_number'             => ['nullable', Rule::unique('division')->ignore($division->id)],

      'division_name'    => 'nullable|string',
  

    ]);

    DB::beginTransaction();

    try {

      $division->update([
        'division_number'             => $request->division_number ?: $division->division_number,
        // 'division_number'             => $request->division_number,
        'division_name'    => $request->division_name,
     

        'created_by_id'    => $request->user()->id,
      ]);

      DB::commit();

      return redirect()->back()->with('success', __(
        'division `:division_name` Berhasil diubah',
        [
          'division_name' => $request->division_name,
        ]
      ));
    } catch (\Exception $e) {
      DB::rollBack();

      return redirect()->back()->with('error', __(
        'can\'t update division_name',
      ));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(DivisionModels $division)
  {
    if ($division->delete()) {
      return redirect()->back()->with('success', __(
        'division `:division_name` berhasil dihapus',
        [
          'division_name' => $division->division_name,
        ]
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t delete division',
    ));
  }

  /**
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return DivisionModels::get();
  }

  /**
   * @param \App\Http\Requests\DataTableRequest $request
   * @return \Illuminate\Http\Response
   */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    return DivisionModels::where(function (Builder $query) use ($request) {
      $search = '%' . $request->search . '%';
      $model = $query->getModel();

      foreach ($model->getFillable() as $column) {
        $query->orWhere($column, ' like', $search);
      }

      $query->orWhereRelation('createdBy', 'name', 'like', $search);
    })->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
      ->when(!$user->hasRole(['superuser', 'it', 'admin']), fn (Builder $query) => $query->where('created_by_id', $user->id))
      ->select([
        'id',
        'division_number',
        'division_name',
   

        'created_by_id',
        'created_at',
        'updated_at',

      ])
      ->paginate($request->per_page ?: 10);
  }
  public function paginate1(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    return DivisionModels::where(function (Builder $query) use ($request) {
      $search = '%' . $request->search . '%';
      $model = $query->getModel();

      foreach ($model->getFillable() as $column) {
        $query->orWhere($column, ' like', $search);
      }

      $query->orWhereRelation('createdBy', 'name', 'like', $search);
    })
      ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
      ->when(!$user->hasRole(['superuser', 'it']), fn (Builder $query) => $query->where('created_by_id', $user->id))
      ->select([
        'id',
        'division_number',
        'division_name',


        'created_by_id',
        'created_at',
        'updated_at',

      ])->where('status', '1')
      ->paginate($request->per_page ?: 10);
  }
}
