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
use App\Models\SystemSectionModels;
use Illuminate\Support\Facades\Auth;

class SystemSectionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(SystemSectionModels $system)
  {

    return Inertia::render('SystemSection/Index', [
      'system'      => $system,
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
      'singkatan'             => 'nullable|unique:system_section',
      'nama'    => 'nullable|string',
      'level'          => 'nullable|string',
     
    ]);

    $system = SystemSectionModels::create([
      'singkatan'             => $request->singkatan,
      'nama'    => $request->nama,
      'level'          => $request->level,
     
      'created_by_id'    => $request->user()->id,
    ]);

    if ($system) {
      return redirect()->back()->with('success', __(
        'system `:nama` Berhasil ditambah',
        [
          'nama' => $request->nama,
        ]
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t create system',
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
  public function update(Request $request, SystemSectionModels $system)
  {
    $request->validate([
      'singkatan'             => ['nullable', Rule::unique('system_section')->ignore($system->id)],
     
      'nama'    => 'nullable|string',
      'level'          => 'nullable|string',
     
    ]);

    DB::beginTransaction();

    try {
     
      $system->update([
        'singkatan'             => $request->singkatan ?: $system->singkatan,
       
        'nama'    => $request->nama,
        'level'          => $request->level,
        
        'created_by_id'    => $request->user()->id,
      ]);

      DB::commit();

      return redirect()->back()->with('success', __(
        'system `:nama` Berhasil diubah',
        [
          'nama' => $request->nama,
        ]
      ));
    } catch (\Exception $e) {
      DB::rollBack();

      return redirect()->back()->with('error', __(
        'can\'t update nama',
      ));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(SystemSectionModels $system)
  {
    if ($system->delete()) {
      return redirect()->back()->with('success', __(
        'system `:nama` berhasil dihapus',
        [
          'nama' => $system->nama,
        ]
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t delete system',
    ));
  }

  /**
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return SystemSectionModels::get();
  }

  /**
   * @param \App\Http\Requests\DataTableRequest $request
   * @return \Illuminate\Http\Response
   */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    return SystemSectionModels::where(function (Builder $query) use ($request) {
      $search = '%' . $request->search . '%';
      $model = $query->getModel();

      foreach ($model->getFillable() as $column) {
        $query->orWhere($column, ' like', $search);
      }

      $query->orWhereRelation('createdBy', 'name', 'like', $search);
    })->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
      ->when(!$user->hasRole(['superuser', 'it']), fn (Builder $query) => $query->where('created_by_id', $user->id))
      ->select([
        'id',
      'singkatan',
        'nama',
        'level',
        
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

    return SystemSectionModels::where(function (Builder $query) use ($request) {
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
      'singkatan',
        'nama',
        'level',
      
        'created_by_id',
        'created_at',
        'updated_at',

      ])->where('status', '1')
      ->paginate($request->per_page ?: 10);
  }

  
}
