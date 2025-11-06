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
use App\Models\Position;

class PositionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Position $position)
  {
    return Inertia::render('Position/Index', [
      'position'      => $position,
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
      'position'            => 'nullable|string',
    ]);

    $position = Position::create([
      'position'            => $request->position,
    ]);

    if ($position) {
      return redirect()->back()->with('success', __(
        'position `:position` Berhasil ditambah',
        [
          'position' => $request->position,
        ]
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t create position',
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
  public function update(Request $request, Position $position)
  {
    $request->validate([
      'position'    => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {

      $position->update([
        'position'             => $request->position ?: $position->position,
      ]);

      DB::commit();

      return redirect()->back()->with('success', __(
        'position `:position` Berhasil diubah',
        [
          'position' => $request->position,
        ]
      ));
    } catch (\Exception $e) {
      DB::rollBack();

      return redirect()->back()->with('error', __(
        'can\'t update position',
      ));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Position $position)
  {
    if ($position->delete()) {
      return redirect()->back()->with('success', __(
        'position `:position` berhasil dihapus',
        [
          'position' => $position->position,
        ]
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t delete position',
    ));
  }

  /**
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return Position::get();
  }

  /**
   * @param \App\Http\Requests\DataTableRequest $request
   * @return \Illuminate\Http\Response
   */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();

    $user = $request->user();
    $search = $request->input('search');
    $orderKey = $request->input('order.key', 'created_at');
    $orderBy = $request->input('order.by', 'asc');
    $perPage = $request->input('per_page', 10);

    $allowedOrderKeys = ['id', 'position', 'created_at', 'updated_at'];
    if (!in_array($orderKey, $allowedOrderKeys)) {
        $orderKey = 'created_at';
    }

    if (!in_array(strtolower($orderBy), ['asc', 'desc'])) {
        $orderBy = 'asc';
    }

    $positions = Position::query()
        ->when($search, function (Builder $query, $search) {
            $searchTerm = '%' . $search . '%';
            $query->where(function ($query) use ($searchTerm) {
                foreach ((new Position())->getFillable() as $column) {
                    $query->orWhere($column, 'like', $searchTerm);
                }
            });
        })
        ->orderBy($orderKey, $orderBy)
        ->select(['id', 'position', 'created_at', 'updated_at'])
        ->paginate($perPage);

    return response()->json($positions);
    }
}
