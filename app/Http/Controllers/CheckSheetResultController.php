<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CheckSheetResult;

class CheckSheetResultController extends Controller
{

  public function autosave(Request $request)
  {
      $validated = $request->validate([
          'check_sheet_id' => 'required|integer|exists:check_sheets,id',
          'check_sheet_master_id' => 'required|integer|exists:check_sheet_masters,id',
          'hasil' => 'nullable|integer|in:0,1',
          'keterangan' => 'nullable|string',
      ]);

      $result = CheckSheetResult::where('check_sheet_id', $validated['check_sheet_id'])
          ->where('check_sheet_master_id', $validated['check_sheet_master_id'])
          ->first();

      if ($result) {
          $result->update([
              'hasil' => $validated['hasil'] ?? 0,
              'keterangan' => $validated['keterangan'] ?? '',
          ]);
      } else {
          $result = CheckSheetResult::create([
              'check_sheet_id' => $validated['check_sheet_id'],
              'check_sheet_master_id' => $validated['check_sheet_master_id'],
              'hasil' => $validated['hasil'] ?? 0,
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
