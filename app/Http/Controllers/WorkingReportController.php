<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DataTableRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\MasterMachine;
use App\Models\MasterRegion;
use App\Models\WorkingReport;
use App\Models\WorkResult;
use App\Models\CheckSheet;
use App\Models\CheckSheetMaster;
use App\Models\CheckSheetMasterDay;
use App\Models\Upload;

class WorkingReportController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(WorkingReport $report)
  {
      return Inertia::render('WorkingReport/Index', [
          'report'      => $report,
          'checksheet'  => $report->checksheet,
          'warmingup'   => $report->warmingup,
          'workresult'  => $report->workresult,
        //   'machines'    => MasterMachine::select('id', 'name', 'type')->get(),
          'machines'    => MasterMachine::with('region')->select('id', 'name', 'type', 'region_id')->get(),
          'regions'     => MasterRegion::select('id', 'name')->get(),
          'users'       => User::select('id', 'name', 'username')->get(),
      ]);
  }

  public function detail(DataTableRequest $request, WorkingReport $report, WorkResult $workresult)
  {
    //   $report->load('checksheetday.dayresults');
        $report->load('checksheetday.dayresults', 'checksheetday.checksheetworkresult', 'machine');

        $machineType = $report->machine?->name;

        $masters = CheckSheetMasterDay::when($machineType, function ($query, $machineType) {
            $query->where('jenis_mesin', $machineType);
        })
        ->orderByRaw("
            CASE 
                WHEN LOWER(group_name) LIKE 'engine%' THEN 1
                WHEN LOWER(group_name) LIKE 'mekanik%' THEN 2
                WHEN LOWER(group_name) LIKE 'pneumatic%' THEN 3
                WHEN LOWER(group_name) LIKE 'hydraulic%' OR LOWER(group_name) LIKE 'hidrolik%' THEN 4
                WHEN LOWER(group_name) LIKE 'elektrik%' OR LOWER(group_name) LIKE 'electrical%' THEN 5
                WHEN LOWER(group_name) LIKE 'peralatan keselamatan%' THEN 6
                ELSE 7
            END
        ")->get();

      $existingResults = $report->checksheetday
        ? $report->checksheetday->dayresults->keyBy('check_sheet_master_day_id')
        : collect();

       $mergedResults = $masters->map(function ($master) use ($existingResults) {
       $result = $existingResults->get($master->id);

       return [
            'check_sheet_master_day_id' => $master->id,
            'group_name' => $master->group_name,
            'komponen' => $master->komponen,
            'rujukan' => $master->rujukan,
            'nilai_rujukan' => $master->nilai_rujukan,
            'satuan' => $master->satuan,
            'urutan' => $master->urutan,
            'cek' => $result ? (int) $result->cek : 0,
            'tambahan' => $result ? (int) $result->tambahan : 0,
            'ganti' => $result ? (int) $result->ganti : 0,
            'kiri_depan' => $result ? $result->kiri_depan : '',
            'kanan_depan' => $result ? $result->kanan_depan : '',
            'keterangan' => $result ? $result->keterangan : '',
        ];
      });
      $checksheetworkresult = $report->checksheetday?->checksheetworkresult()->first();

      return Inertia::render('WorkingReport/Detail')->with([
          'report'      => $report,
          'masters'     => $masters,
          'results'     => $mergedResults,
          'checksheet'  => $report->checksheet ?? null,
          'checksheetday'  => $report->checksheetday ?? null,
          'upload'      => $report->upload ?? null,
        //   'checksheetworkresult' => $report->checksheetday?->checksheetworkresult ?? null,
          'checksheetworkresult' => $checksheetworkresult,
          'warmingup'   => $report->warmingup ?? null,
          'warmingup_user'  => $report->warmingup_user ?? null,
          'workresult'  => $report->workresult ?? null,
          'workresult_user'  => $report->workresult_user ?? null,
        //   'machines'    => MasterMachine::select('id', 'name', 'type', 'no_sarana')->get(),        
          'machines'    => MasterMachine::with('region')->select('id', 'name', 'type', 'nomor', 'no_sarana', 'region_id')->get(),
          'regions'     => MasterRegion::select('id', 'name')->get(),
          'users'       => User::select('id', 'name', 'username')->get(),
      ]);
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create(WorkingReport $report)
  {
      return Inertia::render('WorkingReport/Create', [
          'report' => $report,
          'machines' => MasterMachine::with('region')->select('id', 'name', 'type', 'region_id')->get(),
          'regions' => MasterRegion::select('id', 'name')->get(),
          'users' => User::select('id', 'name')->get(),
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
      $validated = $request->validate([
          'machine_id'  => 'required|exists:master_machines,id',
          'region_id'   => 'required|exists:master_regions,id',
          'date'        => 'nullable|date',
          'has_trouble' => 'nullable',
          'status'      => 'nullable|in:draft,checksheet_done,warming_up_done,photo_uploaded,work_done,verification,finished',
      ]);

      $validated['created_by_id'] = auth()->id();
      $validated['status'] = $validated['status'] ?? 'draft';

      $report = WorkingReport::create($validated);
      
    return redirect()->route('working-reports.index')->with('success', 'Working Order berhasil disimpan.');
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
      $report = WorkingReport::findOrFail($id);

      return Inertia::render('WorkingReport/Update', [
          'report' => $report,
          'machines' => MasterMachine::with('region')->select('id', 'name', 'type', 'region_id')->get(),
          'regions' => MasterRegion::select('id', 'name')->get(),
          'users' => User::select('id', 'name')->get(),
      ]);
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
      $report = WorkingReport::findOrFail($id);

      $validated = $request->validate([
          'machine_id'        => 'required|exists:master_machines,id',
          'region_id'         => 'required|exists:master_regions,id',
          'date'              => 'nullable|date',
          'has_trouble'       => 'nullable',
          'status'            => 'nullable|in:draft,checksheet_done,warming_up_done,photo_uploaded,work_done,verification,finished',
      ]);

      $report->update($validated);

      return redirect()->route('working-reports.index')->with('success', 'Working Order berhasil diubah.');
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
    $region = WorkingReport::findOrFail($id);
    $region->delete();

    return redirect()->back()->with('success', __(
          'Data Working Order ":name" berhasil dihapus.',
          ['name' => $request->machine_id]
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

    $query = WorkingReport::where(function (Builder $query) use ($request) {
        $search = '%' . $request->search . '%';
        $model = $query->getModel();

        foreach ($model->getFillable() as $column) {
            $query->orWhere($column, 'like', $search);
        }
        
        $query->orWhereRelation('workresult', 'working_report_id', 'like', $search);
        $query->orWhereRelation('warmingup', 'working_report_id', 'like', $search);
        $query->orWhereRelation('checksheet', 'working_report_id', 'like', $search);
    })
    // ->when(!$user->hasRole(['superuser', 'it']), function (Builder $query) use ($user) {
    //     $query->whereHas('workresult', function (Builder $query) use ($user) {
    //         $query->where('created_by_id', $user->id);
    //     });
    // })

    ->when(!$user->hasRole(['superuser', 'it', 'admin']), function (Builder $query) use ($user) {
        $query->where(function (Builder $q) use ($user) {
            // Kondisi 1: Hanya data yang dibuat oleh user login
            $q->where('created_by_id', $user->id)
                // Kondisi 2: Atau user login menjadi salah satu operator di tabel check_sheet_work_results
                ->orWhereHas('checksheetday.checksheetworkresult', function (Builder $wr) use ($user) {
                    $wr->where(function ($sub) use ($user) {
                        $sub->where('operator_by1', $user->id)
                            ->orWhere('operator_by2', $user->id)
                            ->orWhere('operator_by3', $user->id)
                            ->orWhere('operator_by4', $user->id);
                    });
                })

                ->orWhereHas('workresult', function (Builder $wr2) use ($user) {
                    $wr2->where(function ($sub2) use ($user) {
                        $sub2->where('operator_by1', $user->id)
                            ->orWhere('operator_by2', $user->id)
                            ->orWhere('operator_by3', $user->id);
                    });
                });
        });
    })
    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
    // ->when(!$user->hasRole(['superuser', 'it']), fn (Builder $query) => 
    //     $query->where('created_by_id', $user->id)
    // )
    ->select([
      'id',
      'machine_id',
      'region_id',
      'status',
      'date'
    ]);

    $paginateData = $query->paginate($request->per_page ?: 10);

    return $paginateData;
  }

  public function fetch(Request $request, int $id)
    {
        $report = WorkingReport::where('id', $id)->first();

        $section = $request->input('section');
        // $section = $request->input();

        $validSections = ['workingreport', 'checksheet', 'warmingup', 'upload', 'workresult'];

        // $report->load('responsibles');
        // $report->load('responsible_process');

        $response = [];

        if (in_array($section, $validSections)) {
            $partial = $report->load($section)->$section;

            if ($section === 'workingreport') {
                // $responsibles = $deviation->responsibles->pluck('id')->toArray();
                // $responsible_process = $deviation->responsible_process->pluck('id')->toArray();

                $partial = array_merge($partial->toArray(), $this->workingreportAppends($report));
                // $partial = array_merge($partial->toArray(), $this->firstSectionAppends($deviation), ['responsible_process' => $responsible_process]);

            } else if ($section === 'checksheet') {

                $partial = array_merge((array)$partial, $this->checksheetAppends($report));
            } else if ($section === 'warmingup') {
                // $deviation->load('riskImpactAnalysis');

                // $riskImpact = $this->mapRiskImpact($deviation->riskImpactAnalysis);

                $partial = array_merge((array)$partial, $this->warmingupAppends($report));
            } else if ($section === 'upload') {

                $partial = array_merge((array)$partial, $this->uploadAppends($report));
            } else if ($section === 'workresult') {

                $partial = array_merge((array)$partial, $this->workresultAppends($report));
            }

            $response[$section] = $partial;
        }

        // Mengatur akses ke Section 2
        // if ($report->workingreport?->ampr_responded_at && $report->registration_number) {
        if ($report->workingreport = true) {
            // $report->sectionTwoOpen = true;
            // $report->sectionTwoNote = ''; // Tidak ada pesan peringatan
        } else {
            $report->warmingupOpen = false;
            $report->warmingupNote = 'testtt';
        // }

        // Mengatur akses ke Section 3
        // if (($report->secondSection?->reviewed_at && in_array($report->level, ['Minor', 'Mayor', 'Critical'])) || $report->furtherInvestigation?->where('unlock', 3)->first()) {
        //     $report->sectionThreeOpen = true;
        //     $report->sectionThreeNote = ''; // Tidak ada pesan peringatan
        // } else {
        //     $report->sectionThreeOpen = false;
        //     if ($report->level === 'RC') {
        //         $report->sectionThreeNote = 'Penyimpangan dengan level RC (Required Correction) tidak diwajibkan mengisi Section 3, terkecuali jika diminta oleh bagian penyimpangan.';
        //     } else {
        //         $report->sectionThreeNote = 'Mohon lengkapi section 2 terlebih dahulu.';
        //     }
        // }

        $this->loadInterlock($report);

        $response['report'] = $report->toArray();

        // return $this->response($response);
        return response()->json($response);
    }
    }

    private function workingreportAppends()
    {
        return [];
    }

    private function checksheetAppends()
    {
        return [];
    }

    private function warmingupAppends()
    {
        return [];
    }

    private function uploadAppends()
    {
        return [];
    }

    private function workresultAppends()
    {
        return [];
    }
}