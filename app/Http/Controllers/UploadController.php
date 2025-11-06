<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\WorkingReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
  public function autosave(Request $request)
  {
      $validated = $request->validate([
          'working_report_id' => 'required|integer|exists:working_reports,id',
          'date'              => 'nullable|date',
      ]);

      $result = Upload::where('working_report_id', $validated['working_report_id'])->first();

      if ($result) {
          $result->update([
              'date' => $validated['date'] ?? '',
          ]);
      } else {
          $result = Upload::create([
              'working_report_id' => $validated['working_report_id'],
              'date' => $validated['date'] ?? '',
          ]);
      }

      $workingReport = WorkingReport::find($validated['working_report_id']);
      if ($workingReport) {
          $workingReport->status = 'photo_uploaded';
          $workingReport->save();
      }

      DB::commit(); 

      return response()->json([
          'success' => true,
          'data' => $result,
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
    try {
        $validated = $request->validate([
            'working_report_id'  => 'required|exists:working_reports,id',
            'date'               => 'nullable|date',
        ]);

        $upload = Upload::create([
            'working_report_id' => $validated['working_report_id'],
            'date'              => $request->date,
        ]);

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
  // public function update(Request $request, $id)
  //   {
  //       try {
  //           $validated = $request->validate([
  //               'working_report_id'  => 'required|exists:working_reports,id',
  //               'date'               => 'nullable|date',
  //           ]);

  //           DB::beginTransaction();

  //           $upload = Upload::findOrFail($id);

  //           $upload->update([
  //               'working_report_id' => $validated['working_report_id'],
  //               'date'              => $request->date,
  //           ]);

  //           DB::commit();

  //           return redirect()->back()->with('success', 'Data berhasil diperbarui.');

  //       } catch (\Illuminate\Validation\ValidationException $e) {
  //           return redirect()->back()->withErrors($e->errors())->withInput();
  //       } catch (\Exception $e) {
  //           DB::rollBack();
  //           return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
  //       }
  //   }
  
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
