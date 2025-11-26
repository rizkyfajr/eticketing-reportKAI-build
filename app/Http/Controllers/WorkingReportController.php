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
use App\Models\MgLurusanAwal;
use App\Models\MgLengkunganAwal;
use App\Models\MgWeselAwal;
use App\Models\PemeriksaanSilangKpjr;
use App\Models\PemeriksaanSilangLahan;
use App\Models\PerekamanAwal;
use App\Models\MgLurusanAkhir;
use App\Models\MgLengkunganAkhir;
use App\Models\MgWeselAkhir;
use App\Models\PerekamanAkhir;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Storage;

class WorkingReportController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request, WorkingReport $report)
  {

      $user = $request->user();

    //   // Ada report hari ini yang belum approve KUPT
    //   $hasPendingKUPT = WorkingReport::whereDate('date', today())
    //       ->where('created_by_id', $user->id)
    //       ->whereNull('approved_at1') // atau kupt_at1
    //       ->exists();

    //   // User sudah membuat 1 report hari ini
    //   $hasCreatedToday = WorkingReport::whereDate('date', today())
    //       ->where('created_by_id', $user->id)
    //       ->exists();
          
      return Inertia::render('WorkingReport/Index', [
          'report'      => $report,
          'checksheet'  => $report->checksheet,
          'warmingup'   => $report->warmingup,
          'workresult'  => $report->workresult,
        //   'machines'    => MasterMachine::select('id', 'name', 'type')->get(),
          'machines'    => MasterMachine::with('region')->select('id', 'name', 'type', 'region_id')->get(),
          'regions'     => MasterRegion::select('id', 'name')->get(),
          'users'       => User::select('id', 'name', 'username')->get(),
        //   'hasPendingKUPT' => $hasPendingKUPT,
        //   'hasCreatedToday' => $hasCreatedToday,
      ]);
  }

  public function detail(DataTableRequest $request, WorkingReport $report, WorkResult $workresult)
  {
    //   $report->load('checksheetday.dayresults');
        $report->load(
            'checksheetday.dayresults', 
            'checksheetday.checksheetworkresult', 
            'machine',
            'mglurusanawal.attachments',
            'mglengkunganawal.attachments',
            'mgweselawal.attachments',
            'pemeriksaansilangkpjr.attachments',
            'pemeriksaansilanglahan.attachments',
            'perekamanawal.attachments',
            'mglurusanakhir.attachments',
            'mglengkunganakhir.attachments',
            'mgweselakhir.attachments',
            'perekamanakhir.attachments',
        );

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
      
      $user = auth()->user();
      
      // Menampilkan data user yang login sesuai daop division
      $userQuery = User::select('id', 'name', 'username', 'division_id');
      if ($user->division_id) {
          $userQuery->where('division_id', $user->division_id);
      }
      // Menampilkan data user yang login sesuai daop division

      return Inertia::render('WorkingReport/Detail')->with([
          'report'      => $report,
          'masters'     => $masters,
          'results'     => $mergedResults,
          'checksheet'  => $report->checksheet ?? null,
          'checksheetday'  => $report->checksheetday ?? null,
          'upload'      => $report->upload ?? null,
          'checksheetworkresult' => $checksheetworkresult,
          'warmingup'   => $report->warmingup ?? null,
          'warmingup_user'  => $report->warmingup_user ?? null,
          'workresult'  => $report->workresult ?? null,
          'workresult_user'  => $report->workresult_user ?? null,    
          'machines'    => MasterMachine::with('region')->select('id', 'name', 'type', 'nomor', 'no_sarana', 'region_id')->get(),
          'regions'     => MasterRegion::select('id', 'name')->get(),
        //   'users'       => User::select('id', 'name', 'username')->get(),
          'users' => $userQuery->get(),
          'mglurusanawal' => $report->mglurusanawal,
          'mglengkunganawal' => $report->mglengkunganawal,
          'mgweselawal' => $report->mgweselawal,
          'pemeriksaansilangkpjr' => $report->pemeriksaansilangkpjr,
          'pemeriksaansilanglahan' => $report->pemeriksaansilanglahan,
          'perekamanawal' => $report->perekamanawal,
          'mglurusanakhir' => $report->mglurusanakhir,
          'mglengkunganakhir' => $report->mglengkunganakhir,
          'mgweselakhir' => $report->mgweselakhir,
          'perekamanakhir' => $report->perekamanakhir,
          'mglurusanawal_attachments' => $report->mglurusanawal?->attachments ?? collect(),
          'mglengkunganawal_attachments' => $report->mglengkunganawal?->attachments ?? collect(),
          'mgweselawal_attachments' => $report->mgweselawal?->attachments ?? collect(),
          'pemeriksaansilangkpjr_attachments' => $report->pemeriksaansilangkpjr?->attachments ?? collect(),
          'pemeriksaansilanglahan_attachments' => $report->pemeriksaansilanglahan?->attachments ?? collect(),
          'perekamanawal_attachments' => $report->perekamanawal?->attachments ?? collect(),
          'mglurusanakhir_attachments' => $report->mglurusanakhir?->attachments ?? collect(),
          'mglengkunganakhir_attachments' => $report->mglengkunganakhir?->attachments ?? collect(),
          'mgweselakhir_attachments' => $report->mgweselakhir?->attachments ?? collect(),
          'perekamanakhir_attachments' => $report->perekamanakhir?->attachments ?? collect(),
      ]);
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create(WorkingReport $report)
  {
      $report->load(
        'mglurusanawal.attachments',
        'mglengkunganawal.attachments',
        'mgweselawal.attachments',
        'pemeriksaansilangkpjr.attachments',
        'pemeriksaansilanglahan.attachments',
        'perekamanawal.attachments'
      );

      // Menampilkan data mesin sesuai daop user
      $user = auth()->user();

      $machineQuery = MasterMachine::with('region')->select('id', 'name', 'type', 'nomor', 'no_sarana', 'region_id');

      if ($user->division_id == 4) {
        $machineQuery->where('region_id', 2);
      } elseif (in_array($user->division_id, [1, 3])) {
        $machineQuery->where('region_id', 1);
      }
      // Menampilkan data mesin sesuai daop users

      // Menampilkan data user yang login sesuai daop division
      $userQuery = User::select('id', 'name', 'username', 'division_id');
      if ($user->division_id) {
          $userQuery->where('division_id', $user->division_id);
      }
      // Menampilkan data user yang login sesuai daop division
      
      return Inertia::render('WorkingReport/Create', [
          'report' => $report,
        //   'report' => $report->load('mglurusanawal'),
        //   'report' => $report->load('mglengkunganawal'),
        //   'report' => $report->load('mgweselawal'),
        //   'report' => $report->load('pemeriksaansilangkpjr'),
        //   'report' => $report->load('pemeriksaansilanglahan'),
        //   'report' => $report->load('perekamanawal'),
        'mglurusanawal' => $report->mglurusanawal,
        'mglengkunganawal' => $report->mglengkunganawal,
        'mgweselawal' => $report->mgweselawal,
        'pemeriksaansilangkpjr' => $report->pemeriksaansilangkpjr,
        'pemeriksaansilanglahan' => $report->pemeriksaansilanglahan,
        'perekamanawal' => $report->perekamanawal,
        'mglurusanawal_attachments' => $report->mglurusanawal?->attachments ?? collect(),
        'mglengkunganawal_attachments' => $report->mglengkunganawal?->attachments ?? collect(),
        'mgweselawal_attachments' => $report->mgweselawal?->attachments ?? collect(),
        'pemeriksaansilangkpjr_attachments' => $report->pemeriksaansilangkpjr?->attachments ?? collect(),
        'pemeriksaansilanglahan_attachments' => $report->pemeriksaansilanglahan?->attachments ?? collect(),
        'perekamanawal_attachments' => $report->perekamanawal?->attachments ?? collect(),
        // 'machines'    => MasterMachine::with('region')->select('id', 'name', 'type', 'nomor', 'no_sarana', 'region_id')->get(),
        'machines' => $machineQuery->get(),
        'regions' => MasterRegion::select('id', 'name')->get(),
        'users' => $userQuery->get(),
        // 'users' => User::select('id', 'name', 'username')->get(),
        //   'mglurusanawal' => $report->mglurusanawal,
        //   'mglengkunganawal' => $report->mglengkunganawal,
        //   'mgweselawal' => $report->mgweselawal,
        //   'pemeriksaansilangkpjr' => $report->pemeriksaansilangkpjr,
        //   'pemeriksaansilanglahan' => $report->pemeriksaansilanglahan,
        //   'perekamanawal' => $report->perekamanawal,
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
          'region_id'   => 'nullable|exists:master_regions,id',
          'date'        => 'nullable|date',
          'has_trouble' => 'nullable',
          'status'      => 'nullable|in:draft,checksheet_done,warming_up_done,photo_uploaded,work_done,verification,finished',
          'cuaca'                => 'nullable|string',
          'jenis_kpjr'           => 'nullable|string',
          'nomor_mesin'          => 'nullable|string',
          'nomor_sarana'         => 'nullable|string',
          'waktu_start_engine'   => 'nullable',
          'jam_traveling_awal'   => 'nullable|string',
          'jam_kerja_awal'       => 'nullable',
          'jam_mesin_awal'       => 'nullable|string',
          'jam_generator_awal'   => 'nullable|string',
          'counter_tamping_awal' => 'nullable|integer',
          'oddometer_awal'       => 'nullable|integer',
          'hsd_awal_kerja'       => 'nullable|integer',
          'mode'                 => 'nullable|string',
          'operator_by1'         => 'nullable|exists:users,id',
          'operator_at1'         => 'nullable|date',
          'operator_by2'         => 'nullable|exists:users,id',
          'operator_at2'         => 'nullable|date',
          'operator_by3'         => 'nullable|exists:users,id',
          'operator_at3'         => 'nullable|date',
          'approved_by'          => 'nullable|exists:users,id',
          'approved_at'          => 'nullable|date',
          'approved_by1'         => 'nullable|exists:users,id',
          'approved_at1'         => 'nullable|date',
          'note'                 => 'nullable|string',
          'nama_pengawal'        => 'nullable|string',
          'nipp'                 => 'nullable|integer',
          'nama_pengawal1'       => 'nullable|string',
          'nipp1'                => 'nullable|integer',
      ]);

      $validated['created_by_id'] = auth()->id();
      $validated['status'] = $validated['status'] ?? 'draft';

      $report = WorkingReport::create($validated);

      if ($report->mode === 'warmingup') {
        // Jika mode adalah WARMING UP, tidak perlu membuat record opname (MG1-MG6)
        // dan langsung redirect ke halaman detail.
        return redirect()
            ->route('working-reports.detail', $report->id)
            ->with('success', 'Working report (Warming Up) berhasil disimpan.');
            
    } else {
        // Jika mode adalah WORKING atau tidak diisi/lainnya,
        // buat semua record opname awal (MG1-MG6) dan lanjutkan ke langkah pengisian form.
        MgLurusanAwal::create(['working_report_id' => $report->id]);
        MgLengkunganAwal::create(['working_report_id' => $report->id]);
        MgWeselAwal::create(['working_report_id' => $report->id]);
        PemeriksaanSilangKpjr::create(['working_report_id' => $report->id]);
        PemeriksaanSilangLahan::create(['working_report_id' => $report->id]);
        PerekamanAwal::create(['working_report_id' => $report->id]);
        
        // Redirect ke halaman pengisian form (create.withid)
        return redirect()
            ->route('working-reports.create.withid', $report->id)
            ->with('success', 'Working report berhasil disimpan. Lanjutkan pengisian data opname awal.');
    }

        // MgLurusanAwal::create([
        //     'working_report_id' => $report->id,
        // ]);

        // MgLengkunganAwal::create([
        //     'working_report_id' => $report->id,
        // ]);

        // MgWeselAwal::create([
        //     'working_report_id' => $report->id,
        // ]);

        // PemeriksaanSilangKpjr::create([
        //     'working_report_id' => $report->id,
        // ]);

        // PemeriksaanSilangLahan::create([
        //     'working_report_id' => $report->id,
        // ]);

        // PerekamanAwal::create([
        //     'working_report_id' => $report->id,
        // ]);
    
        // return redirect()
        // ->route('working-reports.create.withid', $report->id)
        // ->with('success', 'Working report berhasil disimpan.');
  }

  public function submitForm(Request $request)
  {
    $wr_id = $request->working_report_id;

    if ($request->mg1) {
        MgLurusanAwal::updateOrCreate(
            ['working_report_id' => $wr_id],
            ['ada' => $request->mg1['ada'], 'tidak' => $request->mg1['tidak']]
        );
    }

    if ($request->mg2) {
        MgLengkunganAwal::updateOrCreate(
            ['working_report_id' => $wr_id],
            ['ada' => $request->mg2['ada'], 'tidak' => $request->mg2['tidak']]
        );
    }

    if ($request->mg3) {
        MgWeselAwal::updateOrCreate(
            ['working_report_id' => $wr_id],
            ['ada' => $request->mg3['ada'], 'tidak' => $request->mg3['tidak']]
        );
    }

    if ($request->silang_kpjr) {
        PemeriksaanSilangKpjr::updateOrCreate(
            ['working_report_id' => $wr_id],
            ['ada' => $request->silang_kpjr['ada'], 'tidak' => $request->silang_kpjr['tidak']]
        );
    }

    if ($request->silang_lahan) {
        PemeriksaanSilangLahan::updateOrCreate(
            ['working_report_id' => $wr_id],
            ['ada' => $request->silang_lahan['ada'], 'tidak' => $request->silang_lahan['tidak']]
        );
    }

    if ($request->perekaman) {
        PerekamanAwal::updateOrCreate(
            ['working_report_id' => $wr_id],
            ['ada' => $request->perekaman['ada'], 'tidak' => $request->perekaman['tidak']]
        );
    }

    return response()->json(['success' => true,'redirect' => route('working-reports.detail', $wr_id)]);
  }

  public function approve($reportId, $level)
  {
    $report = WorkingReport::findOrFail($reportId);

    if ($level == 1) {
        $report->operator_at1 = now();
    }

    if ($level == 2) {
        if (!$report->operator_at1) {
            return response()->json(['error' => 'Approve 1 belum dilakukan'], 400);
        }
        $report->operator_at2 = now();
    }

    if ($level == 3) {
        if (!$report->operator_at2) {
            return response()->json(['error' => 'Approve 2 belum dilakukan'], 400);
        }
        $report->operator_at3 = now();
    }

    $report->save();

    return response()->json([
        'success' => true,
        'message' => "Approve Operator $level berhasil."
    ]);
  }

  public function approvePengawal($reportId, $level)
  {
    $report = WorkingReport::findOrFail($reportId);

    if ($level == 1) {
        $report->approved_at = now();
    }

    if ($level == 2) {
        if (!$report->approved_at) {
            return response()->json(['error' => 'Approve 1 belum dilakukan'], 400);
        }
        $report->approved_at1 = now();
    }

    $report->save();

    return response()->json([
        'success' => true,
        'message' => "Approve Pengawal $level berhasil."
    ]);
  }

  public function approveKUPT(Request $request)
  {
    $request->validate([
        'id' => 'required|exists:working_reports,id',
    ]);

    $user = $request->user();

    if (!$user->hasRole(['admin', 'Kepala UPT Mekanik'])) {
        return response()->json([
            'message' => 'Anda tidak memiliki akses untuk approve.'
        ], 403);
    }

    $report = WorkingReport::findOrFail($request->id);

    $report->status = 'finished';
    $report->kupt_by1 = $user->id;
    $report->kupt_at1 = now();

    $report->save();

    return response()->json([
        'message' => 'Laporan berhasil di-approve (KUPT).',
        'data' => $report,
    ]);
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
          'machines'    => MasterMachine::with('region')->select('id', 'name', 'type', 'nomor', 'no_sarana', 'region_id')->get(),
          'regions' => MasterRegion::select('id', 'name')->get(),
          'users' => User::select('id', 'name', 'username')->get(),
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
          'region_id'         => 'nullable|exists:master_regions,id',
          'date'              => 'nullable|date',
          'has_trouble'       => 'nullable',
          'status'            => 'nullable|in:draft,checksheet_done,warming_up_done,photo_uploaded,work_done,verification,finished',
          'cuaca'                => 'nullable|string',
          'jenis_kpjr'           => 'nullable|string',
          'nomor_mesin'          => 'nullable|string',
          'nomor_sarana'         => 'nullable|string',
          'waktu_start_engine'   => 'nullable',
          'jam_traveling_awal'   => 'nullable|string',
          'jam_kerja_awal'       => 'nullable',
          'jam_mesin_awal'       => 'nullable|string',
          'jam_generator_awal'   => 'nullable|string',
          'counter_tamping_awal' => 'nullable|integer',
          'oddometer_awal'       => 'nullable|integer',
          'hsd_awal_kerja'       => 'nullable|integer',
          'mode'                 => 'nullable|string',
          'operator_by1'         => 'nullable|exists:users,id',
          'operator_at1'         => 'nullable|date',
          'operator_by2'         => 'nullable|exists:users,id',
          'operator_at2'         => 'nullable|date',
          'operator_by3'         => 'nullable|exists:users,id',
          'operator_at3'         => 'nullable|date',
          'approved_by'          => 'nullable|exists:users,id',
          'approved_at'          => 'nullable|date',
          'approved_by1'         => 'nullable|exists:users,id',
          'approved_at1'         => 'nullable|date',
          'note'                 => 'nullable|string',
          'nama_pengawal'        => 'nullable|string',
          'nipp'                 => 'nullable|integer',
          'nama_pengawal1'       => 'nullable|string',
          'nipp1'                => 'nullable|integer',
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

    ->when(!$user->hasRole(['superuser', 'it', 'admin', 'Kepala UPT Mekanik']), function (Builder $query) use ($user) {
        $query->where(function (Builder $q) use ($user) {
            // Operator yang buat
            $q->where('created_by_id', $user->id)
                // Operator Checksheet
                // ->orWhereHas('checksheetday.checksheetworkresult', function (Builder $wr) use ($user) {
                //     $wr->where(function ($sub) use ($user) {
                //         $sub->where('operator_by1', $user->id)
                //             ->orWhere('operator_by2', $user->id)
                //             ->orWhere('operator_by3', $user->id)
                //             ->orWhere('operator_by4', $user->id);
                //     });
                // });

                // // Operator work result
                // ->orWhereHas('workresult', function (Builder $wr2) use ($user) {
                //     $wr2->where(function ($sub2) use ($user) {
                //         $sub2->where('operator_by1', $user->id)
                //             ->orWhere('operator_by2', $user->id)
                //             ->orWhere('operator_by3', $user->id);
                //     });
                // });
                 ->orWhere('approved_by', $user->id);
        });
    })
    // jika role kupt jakarta
    ->when(
        $user->hasRole('Kepala UPT Mekanik') 
        && $user->position_id == 1
        && in_array($user->division_id, [1, 3]),
        function (Builder $query) {
            $query->where('region_id', 1);
        }
    )

    // jika role kupt bandung
    ->when(
        $user->hasRole('Kepala UPT Mekanik') 
        && $user->position_id == 1
        && in_array($user->division_id, [4]),
        function (Builder $query) {
            $query->where('region_id', 2);
        }
    )
    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
    ->select([
      'id',
      'machine_id',
      'region_id',
      'date',
      'has_trouble',
      'status',
      'cuaca',
      'jenis_kpjr',
      'nomor_mesin',
      'nomor_sarana',
      'waktu_start_engine',
      'jam_traveling_awal',
      'jam_kerja_awal',
      'jam_mesin_awal',
      'jam_generator_awal',
      'counter_tamping_awal',
      'oddometer_awal',
      'hsd_awal_kerja',
      'mode',
      'operator_by1',
      'operator_at1',
      'operator_by2',
      'operator_at2',
      'operator_by3',
      'operator_at3',
      'approved_by',
      'approved_at',
      'approved_by1',
      'approved_at1',
      'kupt_by1',
      'kupt_at1',
      'created_by_id',
      'updated_by_id',
    ]);

    $paginateData = $query->paginate($request->per_page ?: 10);

    return $paginateData;
  }

  public function fetch(Request $request, int $id)
    {
        $report = WorkingReport::where('id', $id)->first();

        $section = $request->input('section');

        $validSections = ['workingreport', 'checksheet', 'warmingup', 'upload', 'workresult'];

        $response = [];

        if (in_array($section, $validSections)) {
            $partial = $report->load($section)->$section;

            if ($section === 'workingreport') {

                $partial = array_merge($partial->toArray(), $this->workingreportAppends($report));

            } else if ($section === 'checksheet') {

                $partial = array_merge((array)$partial, $this->checksheetAppends($report));
            } else if ($section === 'warmingup') {

                $partial = array_merge((array)$partial, $this->warmingupAppends($report));
            } else if ($section === 'upload') {

                $partial = array_merge((array)$partial, $this->uploadAppends($report));
            } else if ($section === 'workresult') {

                $partial = array_merge((array)$partial, $this->workresultAppends($report));
            }

            $response[$section] = $partial;
        }

        if ($report->workingreport = true) {
        } else {
            $report->warmingupOpen = false;
            $report->warmingupNote = 'testtt';

        $this->loadInterlock($report);

        $response['report'] = $report->toArray();

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

    public function downloadReport(WorkingReport $report)
    {
        try {

            $report->load([
                'machine',
                'workresult',
                'checksheetday',
                'mglurusanawal.attachments',
                'mglengkunganawal.attachments',
                'mgweselawal.attachments',
                'pemeriksaansilangkpjr.attachments',
                'pemeriksaansilanglahan.attachments',
                'perekamanawal.attachments',
                'mglurusanakhir.attachments',
                'mglengkunganakhir.attachments',
                'mgweselakhir.attachments',
                'perekamanakhir.attachments',
                'operator1',
                'operator2',
                'operator3',
            ]);
            // dd($report->mglurusanawal);

            $getAttachmentUrl = function ($attachment) {
                $fullPath = $attachment->path . $attachment->filename;
                return storage_path('app/public/' . $fullPath); 
            };
            
            $pdf = PDF::loadView('working_report_1',  compact('report', 'getAttachmentUrl'))->setPaper('A4', 'portrait');

            return $pdf->download('working-report-'.$report->id.'.pdf');

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error terjadi',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }


}