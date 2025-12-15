<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DataTableRequest;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use App\Models\ReadinessAssessmentMaster;
use App\Models\ReadinessAssessment;
use App\Models\HealthCertificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MasterReadinessAssessment extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(ReadinessAssessmentMaster $readines)
  {
    $user = Auth::user();

    // Cek apakah user punya role admin, superuser, Kepala UPT Mekanik, atau admin-wilayah
    $isAdminOrSupervisor = $user->hasAnyRole(['admin', 'superuser', 'Kepala UPT Mekanik', 'admin-wilayah']);

    return Inertia::render('ReadinessAssessment/Index', [
      'readines' => $readines,
      'isAdminOrSupervisor' => $isAdminOrSupervisor, // Flag untuk Vue
    ]);
  }  /**
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
    $user = Auth::user();
    $userId = Auth::id();
    $today = Carbon::today();

    // ===== VALIDASI SURAT KETERANGAN SEHAT =====
    // Cek apakah user punya sertifikat kesehatan yang masih valid
    $validCertificate = HealthCertificate::where('user_id', $userId)
      ->valid()
      ->first();

    if (!$validCertificate) {
      return redirect()->back()
        ->withErrors(['health_certificate' => 'Anda harus mengupload Surat Keterangan Sehat yang masih berlaku sebelum mengisi Daily Readiness Assessment.'])
        ->with('show_upload_modal', true);
    }

    // Validasi jawaban assessment
    $validated = $request->validate([
        'answers' => ['required', 'array'],
        'answers.*' => ['nullable', 'in:ya,tidak'],
    ]);

    $answers = $validated['answers'];

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

  /**
   * Upload Surat Keterangan Sehat
   */
  public function uploadHealthCertificate(Request $request)
  {
    $request->validate([
      'health_certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // Max 5MB
      'valid_from' => ['required', 'date', 'after_or_equal:today'],
    ]);

    $userId = Auth::id();
    $validFrom = Carbon::parse($request->valid_from);
    $validUntil = $validFrom->copy()->addDays(3); // Berlaku 4 hari (hari ini + 3 hari)
    $uploadDate = Carbon::today();

    DB::beginTransaction();

    try {
      // Expire sertifikat lama yang masih active
      HealthCertificate::where('user_id', $userId)
        ->where('status', 'active')
        ->update(['status' => 'expired']);

      // Upload file
      $file = $request->file('health_certificate');
      $fileName = 'health_cert_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
      $filePath = $file->storeAs('health_certificates', $fileName, 'public');

      // Simpan data sertifikat baru
      $certificate = HealthCertificate::create([
        'user_id' => $userId,
        'upload_date' => $uploadDate,
        'valid_from' => $validFrom,
        'valid_until' => $validUntil,
        'file_path' => $filePath,
        'status' => 'active',
        'notes' => $request->notes,
      ]);

      DB::commit();

      return redirect()->back()->with('success',
        'Surat Keterangan Sehat berhasil diupload! Berlaku dari ' .
        $validFrom->format('d/m/Y') . ' sampai ' . $validUntil->format('d/m/Y') . ' (4 hari).'
      );

    } catch (\Exception $e) {
      DB::rollback();

      return redirect()->back()
        ->withErrors(['upload' => 'Gagal mengupload sertifikat. Error: ' . $e->getMessage()]);
    }
  }

  /**
   * Get status sertifikat kesehatan user yang sedang login
   */
  public function getHealthCertificateStatus()
  {
    $userId = Auth::id();

    $certificate = HealthCertificate::where('user_id', $userId)
      ->valid()
      ->first();

    return response()->json([
      'has_valid_certificate' => $certificate !== null,
      'certificate' => $certificate ? [
        'valid_from' => $certificate->valid_from->format('Y-m-d'),
        'valid_until' => $certificate->valid_until->format('Y-m-d'),
        'days_remaining' => Carbon::today()->diffInDays($certificate->valid_until, false) + 1,
        'file_url' => asset('storage/' . $certificate->file_path),
      ] : null,
    ]);
  }

}
