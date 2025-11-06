<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DataTableRequest;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use App\Models\CheckSheetMaster;

class CheckSheetMasterController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(CheckSheetMaster $check_sheet_master)
  {
    return Inertia::render('CheckSheetMaster/Index', [
      'check_sheet_master'      => $check_sheet_master,
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
        'group_name' => 'nullable|string|max:255',
        'komponen'   => 'nullable|string|max:255',
        'rujukan'    => 'nullable|string|max:255',
        'satuan'     => 'nullable|string|max:50',
        'urutan'     => 'nullable|integer',
    ]);

    $komponen = CheckSheetMaster::create([
        'group_name' => $request->group_name,
        'komponen'   => $request->komponen,
        'rujukan'    => $request->rujukan,
        'satuan'     => $request->satuan,
        'urutan'     => $request->urutan ?? 0,
    ]);

    if ($komponen) {
        return redirect()->back()->with('success', __(
            'Data ":group_name" berhasil ditambahkan.',
            ['group_name' => $request->group_name]
        ));
    }

    return redirect()->back()->with('error', __('Gagal menambahkan komponen.'));
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
  public function update(Request $request, $id)
  {
      $komponen = CheckSheetMaster::findOrFail($id);

      $request->validate([
          'group_name' => 'nullable|string|max:255',
          'komponen'   => 'nullable|string|max:255',
          'rujukan'    => 'nullable|string|max:255',
          'satuan'     => 'nullable|string|max:50',
          'urutan'     => 'nullable|integer',
      ]);

      $komponen->update([
          'group_name' => $request->group_name,
          'komponen'   => $request->komponen,
          'rujukan'    => $request->rujukan,
          'satuan'     => $request->satuan,
          'urutan'     => $request->urutan ?? 0,
      ]);

      if ($komponen) {
          return redirect()->back()->with('success', __(
              'Data ":group_name" berhasil diperbarui.',
              ['group_name' => $request->group_name]
          ));
      }

      return redirect()->back()->with('error', __('Gagal memperbarui komponen.'));
  }

  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
    $check_sheet_master = CheckSheetMaster::findOrFail($id);
    $check_sheet_master->delete();

    return redirect()->back()->with('success', __(
      'Data ":group_name" berhasil dihapus.',
      ['group_name' => $request->group_name]
    ));
  }

  /**
  * @return \Illuminate\Http\Response
  */
  public function get()
  {
    return CheckSheetMaster::get();
  }

  /**
  * @param \App\Http\Requests\DataTableRequest $request
  * @return \Illuminate\Http\Response
  */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    $machines = CheckSheetMaster::where(function (Builder $query) use ($request) {
        $search = '%' . $request->search . '%';
        $model = $query->getModel();

        foreach ($model->getFillable() as $column) {
            $query->orWhere($column, 'like', $search);
        }
    })
    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
    ->when(!$user->hasRole(['superuser', 'it']), fn (Builder $query) => 
        $query->where('created_by_id', $user->id)
    )
    ->select(['id', 'group_name', 'komponen', 'rujukan', 'satuan', 'urutan'])
    ->paginate($request->per_page ?: 10);

    return response()->json($machines);
  }
  
}
