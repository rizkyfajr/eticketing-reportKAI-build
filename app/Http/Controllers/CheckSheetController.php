<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CheckSheet;

class CheckSheetController extends Controller
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
    $request->validate([
      'working_report_id' => 'required|exists:working_reports,id',
      'upt_resor'         => 'nullable|string|max:255',
      'tanggal'           => 'nullable|date',
      'waktu'             => 'nullable',
      'region_id'         => 'required|exists:master_regions,id',
      'cuaca'             => 'nullable|string|max:50',
      'tipe_kpjr'         => 'nullable|string|max:50',
      'nomor_seri'        => 'nullable|string|max:100',
      'jam_mesin'         => 'nullable',
      'kilometer_mesin'   => 'nullable|integer',
      'counter_tamping'   => 'nullable|integer',
      'note'              => 'nullable|string',
      'approved_by'       => 'nullable|exists:users,id',
      'approved_at'       => 'nullable|date',
      'approved_by1'      => 'nullable|exists:users,id',
      'approved_at1'      => 'nullable|date',
      'approved_by2'      => 'nullable|exists:users,id',
      'approved_at2'      => 'nullable|date',
    ]);

    $checksheet = CheckSheet::create([
      'working_report_id' => $request->working_report_id,
      'upt_resor'         => $request->upt_resor,
      'tanggal'           => $request->tanggal,
      'waktu'             => $request->waktu,
      'region_id'         => $request->region_id,
      'cuaca'             => $request->cuaca,
      'tipe_kpjr'         => $request->tipe_kpjr,
      'nomor_seri'        => $request->nomor_seri,
      'jam_mesin'         => $request->jam_mesin,
      'kilometer_mesin'   => $request->kilometer_mesin,
      'counter_tamping'   => $request->counter_tamping,
      'note'              => $request->note,
      'approved_by'       => $request->approved_by,
      'approved_at'       => $request->approved_at,
      'approved_by1'      => $request->approved_by1,
      'approved_at1'      => $request->approved_at1,
      'approved_by2'      => $request->approved_by2,
      'approved_at2'      => $request->approved_at2,
      'created_by_id'     => auth()->id(),
    ]);

    if ($checksheet) {
      return redirect()->back()->with('success', __('Data berhasil ditambahkan.'));
    }

    return redirect()->back()->with('error', __('Gagal menambahkan Data.'));
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
    $checksheet = CheckSheet::findOrFail($id);

    $request->validate([
      'working_report_id' => 'required|exists:working_reports,id',
      'upt_resor'         => 'nullable|string|max:255',
      'tanggal'           => 'nullable|date',
      'waktu'             => 'nullable',
      'region_id'         => 'required|exists:master_regions,id',
      'cuaca'             => 'nullable|string|max:50',
      'tipe_kpjr'         => 'nullable|string|max:50',
      'nomor_seri'        => 'nullable|string|max:100',
      'jam_mesin'         => 'nullable',
      'kilometer_mesin'   => 'nullable|integer',
      'counter_tamping'   => 'nullable|integer',
      'note'              => 'nullable|string',
      'approved_by'       => 'nullable|exists:users,id',
      'approved_at'       => 'nullable|date',
      'approved_by1'      => 'nullable|exists:users,id',
      'approved_at1'      => 'nullable|date',
      'approved_by2'      => 'nullable|exists:users,id',
      'approved_at2'      => 'nullable|date',
    ]);

    $checksheet->update([
      'working_report_id' => $request->working_report_id,
      'upt_resor'         => $request->upt_resor,
      'tanggal'           => $request->tanggal,
      'waktu'             => $request->waktu,
      'region_id'         => $request->region_id,
      'cuaca'             => $request->cuaca,
      'tipe_kpjr'         => $request->tipe_kpjr,
      'nomor_seri'        => $request->nomor_seri,
      'jam_mesin'         => $request->jam_mesin,
      'kilometer_mesin'   => $request->kilometer_mesin,
      'counter_tamping'   => $request->counter_tamping,
      'note'              => $request->note,
      'approved_by'       => $request->approved_by,
      'approved_at'       => $request->approved_at,
      'approved_by1'      => $request->approved_by1,
      'approved_at1'      => $request->approved_at1,
      'approved_by2'      => $request->approved_by2,
      'approved_at2'      => $request->approved_at2,
      'updated_by_id'     => auth()->id(),
    ]);

    return redirect()->back()->with('success', __('Data berhasil diperbarui.'));
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
