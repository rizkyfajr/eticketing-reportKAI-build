<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CheckSheetDayResult;

class CheckSheetDayResultController extends Controller
{

  public function autosave(Request $request)
  {
      $validated = $request->validate([
          'check_sheet_day_id' => 'required|integer|exists:check_sheet_days,id',
          'check_sheet_master_day_id' => 'required|integer|exists:check_sheet_master_days,id',
          'cek' => 'nullable|integer|in:0,1',
          'tambahan' => 'nullable|integer|in:0,1',
          'ganti' => 'nullable|integer|in:0,1',
          'kiri_depan' => 'nullable|string',
          'kanan_depan' => 'nullable|string',
          'keterangan' => 'nullable|string',
      ]);

      $result = CheckSheetDayResult::where('check_sheet_day_id', $validated['check_sheet_day_id'])
          ->where('check_sheet_master_day_id', $validated['check_sheet_master_day_id'])
          ->first();

      if ($result) {
          $result->update([
              'cek' => $validated['cek'] ?? 0,
              'tambahan' => $validated['tambahan'] ?? 0,
              'ganti' => $validated['ganti'] ?? 0,
              'kiri_depan' => $validated['kiri_depan'] ?? '',
              'kanan_depan' => $validated['kanan_depan'] ?? '',
              'keterangan' => $validated['keterangan'] ?? '',
          ]);
      } else {
          $result = CheckSheetDayResult::create([
              'check_sheet_day_id' => $validated['check_sheet_day_id'],
              'check_sheet_master_day_id' => $validated['check_sheet_master_day_id'],
              'cek' => $validated['cek'] ?? 0,
              'tambahan' => $validated['tambahan'] ?? 0,
              'ganti' => $validated['ganti'] ?? 0,
              'kiri_depan' => $validated['kiri_depan'] ?? '',
              'kanan_depan' => $validated['kanan_depan'] ?? '',
              'keterangan' => $validated['keterangan'] ?? '',
          ]);
      }

      return response()->json([
          'success' => true,
          'data' => $result,
      ]);
  }

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
    //
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
    //
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
