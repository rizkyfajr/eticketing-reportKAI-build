<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarmingUp;
use App\Models\WorkingReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarmingUpController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    //
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
    try {
        $validated = $request->validate([
            'working_report_id'  => 'required|exists:working_reports,id',
            'machine_id'         => 'nullable|exists:master_machines,id',
            'waktu_start_engine' => 'required|date',
            'jam_kerja'          => 'required|date_format:H:i:s',
            'jam_mesin'          => 'required|date_format:H:i:s',
            'jam_genset'         => 'required|date_format:H:i:s',
            'counter_pecok'      => 'required|integer',
            'oddometer'          => 'required|integer',
            'waktu_stop_engine'  => 'required|date',
            'penggunaan_hsd'     => 'required|numeric',
            'hsd_tersedia'       => 'required|numeric',
            'note'               => 'nullable|string|max:1000',
            'user_id'            => 'nullable|array',
            'user_id.*'          => 'exists:users,id',
        ]);

        $warmingup = WarmingUp::create([
            'working_report_id' => $validated['working_report_id'],
            'machine_id'        => $validated['machine_id'],
            'waktu_start_engine'=> $request->waktu_start_engine,
            'jam_kerja'         => $request->jam_kerja,
            'jam_mesin'         => $request->jam_mesin,
            'jam_genset'        => $request->jam_genset,
            'counter_pecok'     => $request->counter_pecok,
            'oddometer'         => $request->oddometer,
            'waktu_stop_engine' => $request->waktu_stop_engine,
            'penggunaan_hsd'    => $request->penggunaan_hsd,
            'hsd_tersedia'      => $request->hsd_tersedia,
            'note'              => $request->note,
            'created_by_id'     => auth()->id(),
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
        
        $workingReport = WorkingReport::find($validated['working_report_id']);
        if ($workingReport) {
            $workingReport->status = 'warming_up_done';
            $workingReport->save();
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
    DB::beginTransaction();

    try {
        $warmingup = WarmingUp::findOrFail($id);

        $validated = $request->validate([
            'machine_id'         => 'nullable|exists:master_machines,id',
            'waktu_start_engine' => 'required|date',
            'jam_kerja'          => 'required|date_format:H:i:s',
            'jam_mesin'          => 'required|date_format:H:i:s',
            'jam_genset'         => 'required|date_format:H:i:s',
            'counter_pecok'      => 'required|integer',
            'oddometer'          => 'required|integer',
            'waktu_stop_engine'  => 'required|date',
            'penggunaan_hsd'     => 'required|numeric',
            'hsd_tersedia'       => 'required|numeric',
            'note'               => 'nullable|string|max:1000',
            'user_id'            => 'nullable|array',
            'user_id.*'          => 'exists:users,id',
        ]);

        $warmingup->update([
            'machine_id'        => $validated['machine_id'],
            'waktu_start_engine'=> $validated['waktu_start_engine'] ?? null,
            'jam_kerja'         => $validated['jam_kerja'] ?? null,
            'jam_mesin'         => $validated['jam_mesin'] ?? null,
            'jam_genset'        => $validated['jam_genset'] ?? null,
            'counter_pecok'     => $validated['counter_pecok'] ?? null,
            'oddometer'         => $validated['oddometer'] ?? null,
            'waktu_stop_engine' => $validated['waktu_stop_engine'] ?? null,
            'penggunaan_hsd'    => $validated['penggunaan_hsd'] ?? null,
            'hsd_tersedia'      => $validated['hsd_tersedia'] ?? null,
            'note'              => $validated['note'] ?? null,
            'updated_by_id'     => auth()->id(),
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
  public function destroy($id)
  {
    //
  }
}
