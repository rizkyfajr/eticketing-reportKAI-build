<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarmingUp;
use App\Models\WorkingReport;
use App\Models\MasterMachine;
use App\Models\MasterRegion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Http\Requests\DataTableRequest;
use Illuminate\Database\Eloquent\Builder;

class WarmingUpController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(WarmingUp $warmingup)
  {
    return Inertia::render('WarmingUp/Index', [
      'warmingup'       => $warmingup,
      'warmingup_user'  => $warmingup->warmingup_user ?? null,
    ]);
  }

  
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function detail(DataTableRequest $request, WarmingUp $warmingup)
  {
    $warmingup->load([
        'machine.region',
        'warmingup_user.user', 
    ]);
    return Inertia::render('WarmingUp/Detail', [
      'warmingup'       => $warmingup,
      'warmingup_user'  => $warmingup->warmingup_user ?? null,
      'machines'        => MasterMachine::with('region')->select('id', 'name', 'type', 'region_id')->get(),
      'regions'         => MasterRegion::select('id', 'name')->get(),
      'users'           => User::select('id', 'name')->get(),
    ]);
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create(WarmingUp $warmingup)
  {
    return Inertia::render('WarmingUp/Create', [
        'warmingup'       => $warmingup,
        'warmingup_user'  => $warmingup->warmingup_user ?? null,
        'machines'        => MasterMachine::with('region')->select('id', 'name', 'type', 'nomor', 'no_sarana', 'region_id')->get(),
        'regions'         => MasterRegion::select('id', 'name')->get(),
        'users'           => User::select('id', 'name')->get(),
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
    try {
        $validated = $request->validate([
            'working_report_id'     => 'required|exists:working_reports,id',
            'tanggal'               => 'nullable|date',
            'cuaca'                 => 'nullable|string',
            'jenis_kpjr'            => 'nullable|string',
            'nomor_mesin'           => 'nullable|string',
            'nomor_sarana'          => 'nullable|string',
            'waktu_start_engine'    => 'nullable|date_format:H:i',
            'jam_traveling_awal'    => 'nullable|string',
            'jam_kerja_awal'        => 'nullable|date_format:H:i',
            'jam_mesin_awal'        => 'nullable|string',
            'jam_generator_awal'    => 'nullable|string',
            'counter_tamping_awal'  => 'nullable|integer',
            'oddometer_awal'        => 'nullable|integer',
            'hsd_awal_kerja'        => 'nullable|integer',
            'konsumsi_hsd'          => 'nullable|string',
            'waktu_stop_engine'     => 'nullable|date_format:H:i',
            'jam_traveling_akhir'   => 'nullable|string',
            'jam_kerja_akhir'       => 'nullable|date_format:H:i',
            'jam_mesin_akhir'       => 'nullable|string',
            'jam_generator_akhir'   => 'nullable|string',
            'counter_tamping_akhir' => 'nullable|integer',
            'oddometer_akhir'       => 'nullable|integer',
            'hsd_akhir_kerja'       => 'nullable|integer',
            'operator_by1'          => 'nullable|exists:users,id',
            'operator_by2'          => 'nullable|exists:users,id',
            'operator_by3'          => 'nullable|exists:users,id',
            'approved_by'           => 'nullable|exists:users,id',
            'approved_at'           => 'nullable|date',
            'approved_by1'          => 'nullable|exists:users,id',
            'approved_at1'          => 'nullable|date',
            'note'                  => 'nullable|string',
            'user_id'               => 'nullable|array',
            'user_id.*'             => 'exists:users,id',
        ]);

        $warmingup = WarmingUp::create([
            'working_report_id'     => $validated['working_report_id'],
            'tanggal'               => $request->tanggal,
            'cuaca'                 => $request->cuaca,
            'jenis_kpjr'            => $request->jenis_kpjr,
            'nomor_mesin'           => $request->nomor_mesin,
            'nomor_sarana'          => $request->nomor_sarana,
            'waktu_start_engine'    => $request->waktu_start_engine,
            'jam_traveling_awal'    => $request->jam_traveling_awal,
            'jam_kerja_awal'        => $request->jam_kerja_awal,
            'jam_mesin_awal'        => $request->jam_mesin_awal,
            'jam_generator_awal'    => $request->jam_generator_awal,
            'counter_tamping_awal'  => $request->counter_tamping_awal,
            'oddometer_awal'        => $request->oddometer_awal,
            'hsd_awal_kerja'        => $request->hsd_awal_kerja,
            'konsumsi_hsd'          => $request->konsumsi_hsd,
            'waktu_stop_engine'     => $request->waktu_stop_engine,
            'jam_traveling_akhir'   => $request->jam_traveling_akhir,
            'jam_kerja_akhir'       => $request->jam_kerja_akhir,
            'jam_mesin_akhir'       => $request->jam_mesin_akhir,
            'jam_generator_akhir'   => $request->jam_generator_akhir,
            'counter_tamping_akhir' => $request->counter_tamping_akhir,
            'oddometer_akhir'       => $request->oddometer_akhir,
            'hsd_akhir_kerja'       => $request->hsd_akhir_kerja,
            'operator_by1'          => $request->operator_by1,
            'operator_by2'          => $request->operator_by2,
            'operator_by3'          => $request->operator_by3,
            'approved_by'           => $request->approved_by,
            'approved_by1'          => $request->approved_by1,
            'note'                  => $request->note,
            'created_by_id'         => auth()->id(),
        ]);

        if (!empty($validated['user_id'])) {
            $crewPivotData = collect($validated['user_id'])->map(function ($userId) use ($warmingup) {
                return [
                    'warming_up_id'  => $warmingup->id,
                    'user_id'        => $userId,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            })->toArray();

            DB::table('warmingup_user')->insert($crewPivotData);
        }
        
        DB::commit();

        return redirect()->back()->with('success', 'Data berhasil disimpan.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
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
    $warmingup = WarmingUp::findOrFail($id);

    return Inertia::render('WarmingUp/Update', [
        'warmingup' => $warmingup,
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
    DB::beginTransaction();

    try {
        $warmingup = WarmingUp::findOrFail($id);

        $validated = $request->validate([
            'working_report_id'     => 'required|exists:working_reports,id',
            'tanggal'               => 'nullable|date',
            'cuaca'                 => 'nullable|string',
            'jenis_kpjr'            => 'nullable|string',
            'nomor_mesin'           => 'nullable|string',
            'nomor_sarana'          => 'nullable|string',
            'waktu_start_engine'    => 'nullable|date_format:H:i',
            'jam_traveling_awal'    => 'nullable|string',
            'jam_kerja_awal'        => 'nullable|date_format:H:i',
            'jam_mesin_awal'        => 'nullable|string',
            'jam_generator_awal'    => 'nullable|string',
            'counter_tamping_awal'  => 'nullable|integer',
            'oddometer_awal'        => 'nullable|integer',
            'hsd_awal_kerja'        => 'nullable|integer',
            'konsumsi_hsd'          => 'nullable|string',
            'waktu_stop_engine'     => 'nullable|date_format:H:i',
            'jam_traveling_akhir'   => 'nullable|string',
            'jam_kerja_akhir'       => 'nullable|date_format:H:i',
            'jam_mesin_akhir'       => 'nullable|string',
            'jam_generator_akhir'   => 'nullable|string',
            'counter_tamping_akhir' => 'nullable|integer',
            'oddometer_akhir'       => 'nullable|integer',
            'hsd_akhir_kerja'       => 'nullable|integer',
            'operator_by1'          => 'nullable|exists:users,id',
            'operator_by2'          => 'nullable|exists:users,id',
            'operator_by3'          => 'nullable|exists:users,id',
            'approved_by'           => 'nullable|exists:users,id',
            'approved_at'           => 'nullable|date',
            'approved_by1'          => 'nullable|exists:users,id',
            'approved_at1'          => 'nullable|date',
            'note'                  => 'nullable|string',
            'user_id'               => 'nullable|array',
            'user_id.*'             => 'exists:users,id',
        ]);

        $warmingup->update([
            'working_report_id'     => $validated['working_report_id'],
            'tanggal'               => $request->tanggal,
            'cuaca'                 => $request->cuaca,
            'jenis_kpjr'            => $request->jenis_kpjr,
            'nomor_mesin'           => $request->nomor_mesin,
            'nomor_sarana'          => $request->nomor_sarana,
            'waktu_start_engine'    => $request->waktu_start_engine,
            'jam_traveling_awal'    => $request->jam_traveling_awal,
            'jam_kerja_awal'        => $request->jam_kerja_awal,
            'jam_mesin_awal'        => $request->jam_mesin_awal,
            'jam_generator_awal'    => $request->jam_generator_awal,
            'counter_tamping_awal'  => $request->counter_tamping_awal,
            'oddometer_awal'        => $request->oddometer_awal,
            'hsd_awal_kerja'        => $request->hsd_awal_kerja,
            'konsumsi_hsd'          => $request->konsumsi_hsd,
            'waktu_stop_engine'     => $request->waktu_stop_engine,
            'jam_traveling_akhir'   => $request->jam_traveling_akhir,
            'jam_kerja_akhir'       => $request->jam_kerja_akhir,
            'jam_mesin_akhir'       => $request->jam_mesin_akhir,
            'jam_generator_akhir'   => $request->jam_generator_akhir,
            'counter_tamping_akhir' => $request->counter_tamping_akhir,
            'oddometer_akhir'       => $request->oddometer_akhir,
            'hsd_akhir_kerja'       => $request->hsd_akhir_kerja,
            'operator_by1'          => $request->operator_by1,
            'operator_by2'          => $request->operator_by2,
            'operator_by3'          => $request->operator_by3,
            'approved_by'           => $request->approved_by,
            'approved_by1'          => $request->approved_by1,
            'note'                  => $request->note,
        ]);

        if ($request->has('user_id')) {
        $existingUserIds = $warmingup->users()->pluck('users.id')->toArray();
        $newUserIds = $validated['user_id'] ?? [];

        sort($existingUserIds);
        sort($newUserIds);

        if ($existingUserIds !== $newUserIds) {
                $pivotData = [];
                foreach ($newUserIds as $userId) {
                    $pivotData[$userId] = [
                        'updated_at' => now(),
                    ];
                }

                $warmingup->users()->sync($pivotData);
            }
        }

        DB::commit();

        return redirect()->back()->with('success', 'Data berhasil diubah.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
    $warmingup = WarmingUp::findOrFail($id);
    $warmingup->delete();

    return redirect()->back()->with('success', __(
          'Data berhasil dihapus.'
      ));
  }

  public function approve(Request $request)
  {
      $request->validate([
          'id' => 'required|exists:warming_up,id',
          'index' => 'required|in:1,2,3',
      ]);

      $result = WarmingUp::find($request->id);
      $now = now();

      if ($request->index == 1) {
          $result->operator_at1 = $now;
      } elseif ($request->index == 2) {
          $result->operator_at2 = $now;
      } elseif ($request->index == 3) {
          $result->operator_at3 = $now;
      }

      $result->save();

      return response()->json(['message' => 'Berhasil disetujui.']);
  }


  /**
  * @param \App\Http\Requests\DataTableRequest $request
  * @return \Illuminate\Http\Response
  */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    $region = WarmingUp::where(function (Builder $query) use ($request) {
        $search = '%' . $request->search . '%';
        $model = $query->getModel();

        foreach ($model->getFillable() as $column) {
            $query->orWhere($column, 'like', $search);
        }
    })
    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
    // ->when(!$user->hasRole(['superuser', 'it', 'admin']), fn (Builder $query) => 
    //     $query->where('created_by_id', $user->id)
    // )
    ->select([
        'id',
        'working_report_id',
        'machine_id',
        'waktu_start_engine',
        'jam_kerja',
        'jam_mesin',
        'jam_genset',
        'counter_pecok',
        'oddometer',
        'waktu_stop_engine',
        'penggunaan_hsd',
        'hsd_tersedia',
        'note',
        'approved_by',
        'approved_at',
        'created_by_id',
        'updated_by_id',
    ])
    ->paginate($request->per_page ?: 10);

    return response()->json($region);
  }
}
