<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DataTableRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\MasterRegion;

class MasterRegionController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(MasterRegion $region)
  {
    return Inertia::render('Region/Index', [
      'region'      => $region,
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
        'name'      => 'required|string|max:255',
    ]);

    $region = MasterRegion::create([
        'name'      => $request->name,
    ]);

    if ($region) {
        return redirect()->back()->with('success', __(
            'Wilayah ":name" berhasil ditambahkan.',
            ['name' => $request->name]
        ));
    }

      return redirect()->back()->with('error', __('Gagal menambahkan wilayah.'));
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
      $region = MasterRegion::findOrFail($id);

      $validated = $request->validate([
          'name' => 'required|string|max:255',
      ]);

      $region->update($validated);

      return redirect()->back()->with('success', __(
            'Wilayah ":name" berhasil diperbarui.',
            ['name' => $request->name]
      ));
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
    $region = MasterRegion::findOrFail($id);
    $region->delete();

    return redirect()->back()->with('success', __(
          'Wilayah ":name" berhasil dihapus.',
          ['name' => $request->name]
      ));
  }

  /**
  * @param \App\Http\Requests\DataTableRequest $request
  * @return \Illuminate\Http\Response
  */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    $region = MasterRegion::where(function (Builder $query) use ($request) {
        $search = '%' . $request->search . '%';
        $model = $query->getModel();

        foreach ($model->getFillable() as $column) {
            $query->orWhere($column, 'like', $search);
        }
    })
    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
    ->when(!$user->hasRole(['superuser', 'it', 'admin']), fn (Builder $query) => 
        $query->where('created_by_id', $user->id)
    )
    ->select(['id','name'])
    ->paginate($request->per_page ?: 10);

    return response()->json($region);
  }
}
