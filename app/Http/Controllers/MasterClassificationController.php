<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DataTableRequest;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use App\Models\MasterClassification;

class MasterClassificationController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(MasterClassification $classification)
  {
    return Inertia::render('Classification/Index', [
      'classification' => $classification,
    ]);
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
        'name' => 'required|string|max:255|unique:master_classifications,name',
    ]);

    $classification = MasterClassification::create([
        'name' => $request->name,
    ]);

    if ($classification) {
        return redirect()->back()->with('success', __(
            'Klasifikasi ":name" berhasil ditambahkan.',
            ['name' => $request->name]
        ));
    }

    return redirect()->back()->with('error', __('Gagal menambahkan klasifikasi.'));
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
      $classification = MasterClassification::findOrFail($id);

      $validated = $request->validate([
          'name' => 'required|string|max:255|unique:master_classifications,name,' . $id,
      ]);

      $classification->update($validated);

      return redirect()->back()->with('success', __(
            'Klasifikasi ":name" berhasil diperbarui.',
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
      $classification = MasterClassification::findOrFail($id);
      $classification->delete();

      return redirect()->back()->with('success', __(
            'Klasifikasi ":name" berhasil dihapus.',
            ['name' => $request->name]
        ));
  }

  /**
  * @return \Illuminate\Http\Response
  */
  public function get()
  {
    return MasterClassification::get();
  }

  /**
  * @param \App\Http\Requests\DataTableRequest $request
  * @return \Illuminate\Http\Response
  */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();

    $classifications = MasterClassification::where(function (Builder $query) use ($request) {
        $search = '%' . $request->search . '%';
        $query->where('name', 'like', $search);
    })
    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
    ->select(['id', 'name'])
    ->paginate($request->per_page ?: 10);

    return response()->json($classifications);
  }
}
