<?php

namespace App\Http\Controllers;

use App\Models\CheckSheetWorkResult;
use Illuminate\Http\Request;

class CheckSheetWorkResultController extends Controller
{
    /**
     * Autosave data CheckSheetWorkResult
     */
    // public function autosave(Request $request)
    // {
    //     $validated = $request->validate([
    //         'working_report_id' => 'required|integer|exists:working_reports,id',
    //         'check_sheet_day_id' => 'required|integer|exists:check_sheet_days,id',
    //         'catatan_gangguan' => 'nullable|string',
    //         'lokasi_dan_jam1' => 'nullable|string',
    //         'hu_hi_1' => 'nullable|string',
    //         'jumlah_1' => 'nullable|string',
    //         'lokasi_dan_jam2' => 'nullable|string',
    //         'hu_hi_2' => 'nullable|string',
    //         'jumlah_2' => 'nullable|string',
    //         'lokasi_dan_jam3' => 'nullable|string',
    //         'hu_hi_3' => 'nullable|string',
    //         'jumlah_3' => 'nullable|string',
    //         'operator_by1' => 'nullable|integer',
    //         'operator_by2' => 'nullable|integer',
    //         'operator_by3' => 'nullable|integer',
    //         'operator_by4' => 'nullable|integer',
    //         'validasi1' => 'nullable|string',
    //         'validasi2' => 'nullable|string',
    //         'validasi3' => 'nullable|string',
    //         'validasi4' => 'nullable|string',
    //     ]);

    //     // Jika ID dikirim → update berdasarkan ID
    //     if (!empty($request->id)) {
    //         $workResult = ChecksheetWorkResult::find($request->id);
    //         $workResult->update($validated);
    //     } else {
    //         // Jika belum ada ID → create atau update berdasarkan check_sheet_day_id
    //         $workResult = ChecksheetWorkResult::updateOrCreate(
    //             ['check_sheet_day_id' => $request->check_sheet_day_id],
    //             $validated
    //         );
    //     }

    //     return response()->json([
    //         'message' => 'Data berhasil disimpan otomatis',
    //         'data' => $workResult
    //     ]);
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'working_report_id' => 'required|integer|exists:working_reports,id',
            'check_sheet_day_id' => 'required|integer|exists:check_sheet_days,id',
            'catatan_gangguan' => 'nullable|string',
            'lokasi_dan_jam1' => 'nullable|string',
            'hu_hi_1' => 'nullable|string',
            'jumlah_1' => 'nullable|string',
            'lokasi_dan_jam2' => 'nullable|string',
            'hu_hi_2' => 'nullable|string',
            'jumlah_2' => 'nullable|string',
            'lokasi_dan_jam3' => 'nullable|string',
            'hu_hi_3' => 'nullable|string',
            'jumlah_3' => 'nullable|string',
            'operator_by1' => 'nullable|integer',
            'operator_by2' => 'nullable|integer',
            'operator_by3' => 'nullable|integer',
            'operator_by4' => 'nullable|integer',
            'validasi1' => 'nullable|string',
            'validasi2' => 'nullable|string',
            'validasi3' => 'nullable|string',
            'validasi4' => 'nullable|string',
        ]);

        $result = ChecksheetWorkResult::create($validated);

        return redirect()->back()->with('success', __('Data berhasil disimpan.'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_gangguan' => 'nullable|string',
            'lokasi_dan_jam1' => 'nullable|string',
            'hu_hi_1' => 'nullable|string',
            'jumlah_1' => 'nullable|string',
            'lokasi_dan_jam2' => 'nullable|string',
            'hu_hi_2' => 'nullable|string',
            'jumlah_2' => 'nullable|string',
            'lokasi_dan_jam3' => 'nullable|string',
            'hu_hi_3' => 'nullable|string',
            'jumlah_3' => 'nullable|string',
            'operator_by1' => 'nullable|integer',
            'operator_by2' => 'nullable|integer',
            'operator_by3' => 'nullable|integer',
            'operator_by4' => 'nullable|integer',
            'validasi1' => 'nullable|string',
            'validasi2' => 'nullable|string',
            'validasi3' => 'nullable|string',
            'validasi4' => 'nullable|string',
        ]);

        $result = ChecksheetWorkResult::findOrFail($id);
        $result->update($validated);

        return redirect()->back()->with('success', __('Data berhasil diperbarui.'));
    }

    public function approve(Request $request)
{
    $user = auth()->user();
    $result = CheckSheetWorkResult::find($request->id);

    if (!$result) {
        return response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }

    $now = now();

    if ($result->operator_by1 == $user->id) {
        $result->operator_at1 = $now;
    } elseif ($result->operator_by2 == $user->id) {
        $result->operator_at2 = $now;
    } elseif ($result->operator_by3 == $user->id) {
        $result->operator_at3 = $now;
    } elseif ($result->operator_by4 == $user->id) {
        $result->operator_at4 = $now;
    } else {
        return response()->json(['message' => 'Anda tidak memiliki akses untuk approve data ini.'], 403);
    }

    $result->save();

    return response()->json(['message' => 'Data berhasil disetujui.']);
}




}
