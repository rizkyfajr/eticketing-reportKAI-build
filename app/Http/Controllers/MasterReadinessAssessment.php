<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DataTableRequest;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use App\Models\ReadinessAssessmentMaster;
use App\Models\ReadinessAssessment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterReadinessAssessment extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(ReadinessAssessmentMaster $readines)
  {
    return Inertia::render('ReadinessAssessment/Index', [
      'readines'      => $readines,
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
        'group_name'       => 'nullable|string|max:255',
        'urutan'           => 'nullable|string',
        'nomor'            => 'nullable|integer',
        'komponen'         => 'nullable|string|max:255',
        'pertanyaan'       => 'nullable|string|max:255',
    ]);

    $komponen = ReadinessAssessmentMaster::create([
        'group_name'       => $request->group_name,
        'urutan'           => $request->urutan ?? 0,
        'nomor'            => $request->nomor,
        'komponen'         => $request->komponen,
        'pertanyaan'       => $request->pertanyaan,
    ]);

    if ($komponen) {
        return redirect()->back()->with('success', __(
            'Data ":group_name" berhasil ditambahkan.',
            ['group_name' => $request->group_name]
        ));
    }

    return redirect()->back()->with('error', __('Gagal menambahkan data.'));
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
    $komponen = ReadinessAssessmentMaster::findOrFail($id);

    $request->validate([
        'group_name'       => 'nullable|string|max:255',
        'urutan'           => 'nullable|string',
        'nomor'            => 'nullable|integer',
        'komponen'         => 'nullable|string|max:255',
        'pertanyaan'       => 'nullable|string|max:255',
    ]);

    $komponen->update([
        'group_name'       => $request->group_name,
        'urutan'           => $request->urutan ?? 0,
        'nomor'            => $request->nomor,
        'komponen'         => $request->komponen,
        'pertanyaan'       => $request->pertanyaan,
    ]);

    if ($komponen) {
        return redirect()->back()->with('success', __(
            'Data ":group_name" berhasil diperbarui.',
            ['group_name' => $request->group_name]
        ));
    }

    return redirect()->back()->with('error', __('Gagal memperbarui data.'));
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
    $readines = ReadinessAssessmentMaster::findOrFail($id);
    $readines->delete();

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
    return ReadinessAssessmentMaster::get();
  }

  /**
  * @param \App\Http\Requests\DataTableRequest $request
  * @return \Illuminate\Http\Response
  */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    $readines = ReadinessAssessmentMaster::where(function (Builder $query) use ($request) {
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
    ->select(['id', 'group_name', 'komponen', 'urutan', 'nomor', 'pertanyaan'])
    ->paginate($request->per_page ?: 10);

    return response()->json($readines);
  }
  
  public function storeassessment(Request $request)
  {
    $validated = $request->validate([
        'answers' => ['required', 'array'],
        'answers.*' => ['nullable', 'in:ya,tidak'], 
    ]);

    $answers = $validated['answers'];
    $userId = Auth::id();
    $today = now()->format('Y-m-d');

    DB::beginTransaction();

    try {
        foreach ($answers as $questionId => $answerValue) {

            if ($answerValue === null) {
                $isYa = 0;
                $isTidak = 0;
            } else {
                $isYa = $answerValue === 'ya' ? 1 : 0;
                $isTidak = $answerValue === 'tidak' ? 1 : 0;
            }

            ReadinessAssessment::updateOrCreate(
                [
                    'user_id' => $userId,
                    'assessment_master_id' => $questionId,
                    'assessment_date' => $today,
                ],
                [
                    'ya' => $isYa,
                    'tidak' => $isTidak,
                ]
            );
        }

        DB::commit();

        return redirect()->back()->with('success', 'Daily Readiness Assessment berhasil disimpan!');

    } catch (\Exception $e) {
        DB::rollback();

        return redirect()->back()
            ->withErrors(['submission' => 'Gagal menyimpan assessment. Error: ' . $e->getMessage()])
            ->withInput();
    }
  }

}
