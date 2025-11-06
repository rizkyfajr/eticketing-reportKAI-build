<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Joblist;
use App\Models\Verifikasi;
use App\Http\Requests\DataTableRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Throwable;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use App\Models\DivisionModels;
use App\Models\FeedbackModels;
use App\Models\SystemSectionModels;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Report $report)
  {
    $systemSections = SystemSectionModels::all();
    $divisionSections = DivisionModels::all();

    return Inertia::render('Report/Index', [
      'report'           => $report,
      'systemSections'   => $systemSections,
      'divisionSections' => $divisionSections,
      'users'            => User::get(['id', 'name']),
      'reportuser'       => $report->reportuser(),
    ]);
  }
  public function indexterkirim(Report $report)
  {

    return Inertia::render('Report/Terkirim', [
      'report'      => $report,
      'users'       => User::get(['id', 'name']),
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
    $request->validate([
      'kode'             => 'nullable|unique:report',
      'bagian_sistem'    => 'nullable|string',
      'bagian_hardware'  => 'nullable|string',
      'bagian_id'        => 'nullable|int',
      'tanggal'          => 'nullable|date',
      'bagian_pelapor'   => 'nullable|string',
      'kategori'         => 'nullable|string',
      'kendala'          => 'nullable|string',
      'dampak'           => 'nullable|string',
      'kontak'           => 'nullable|string',
      'url'              => 'nullable|string',
      'catatan'          => 'nullable|string',
      'catatan1'         => 'nullable|string',
      'jenis'            => 'nullable|string',
    ]);

    $kode = '';
    $index = Report::whereMonth('created_at', date('m'))
      ->whereYear('created_at', date('Y'))
      ->whereNotNull('kode')
      ->count();

    $lastRisk = Report::whereMonth('created_at', date('m'))
      ->whereYear('created_at', date('Y'))
      ->orderBy('id', 'desc')
      ->first();

    $bulan = date('m');
    $tahun = substr(date('Y'), -2);
    $bagian_id = $request->bagian_id;
    $section = SystemSectionModels::find($bagian_id);

    if ($bagian_id == null) {
      $kategori = $request->kategori;

      if ($kategori == "Hardware") {
        $bagianSistem = "HARD";
      } elseif ($kategori == "Jaringan") {
        $bagianSistem = "JAR";
      } else {
        $bagianSistem = "HJ";
      }
    } elseif ($section->bagian_sistem != '') {
      $bagianSistem = 'HJ';
    } else {
      $bagianSistem = $section->singkatan;
    }

    $totalLaporan = str_pad($index + 1, 3, '0', STR_PAD_LEFT);

    $kode = sprintf(
      'L-%s-%s-%s-%s',
      $bulan,
      $tahun,
      $bagianSistem,
      $totalLaporan
    );

    if ($lastRisk && $lastRisk->kode === $kode) {
      $index = $index - 1;
      $totalLaporan = str_pad($index + 1, 3, '0', STR_PAD_LEFT);

      $kode = sprintf(
        'L-%s-%s-%s-%s',
        $bulan,
        $tahun,
        $bagianSistem,
        $totalLaporan
      );
    }

    $report = Report::create([
      'kode'             => $kode,
      'bagian_sistem'    => $request->bagian_sistem,
      'bagian_hardware'  => $request->bagian_hardware1 ?? $request->bagian_hardware,
      'bagian_id'        => $request->bagian_id,
      'tanggal'          => $request->tanggal,
      'bagian_pelapor'   => $request->bagian_pelapor,
      'kategori'         => $request->kategori,
      'kendala'          => $request->kendala,
      'dampak'           => $request->dampak,
      'kontak'           => $request->kontak,
      'url'              => $request->url,
      'catatan'          => $request->catatan,
      'catatan1'         => $request->catatan1,
      'jenis'            => $request->jenis,
      'created_by_id'    => $request->user()->id,
    ]);

    // if ($report) {
    //   if ($request->jenis == 'Penambahan Fitur Sistem') {
    //     $job = JobList::create([
    //         'catatan' => 'Penambahan Fitur Sistem',
    //         'report_id'     => $report->id,
    //         'created_by_id' => $request->user()->id,
    //     ]);
    // }
    return redirect()->back()->with('success', __(
      'Data report Berhasil ditambah',
    ));
    // }

    return redirect()->back()->with('error', __(
      'can\'t create report',
    ));
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
  public function update(Request $request, Report $report)
  {
    $request->validate([
      'kode'             => ['nullable', Rule::unique('reports')->ignore($report->id)],
      // 'kode'             => 'nullable|unique:report',
      'bagian_sistem'    => 'nullable|string',
      'bagian_hardware'  => 'nullable|string',
      'bagiain_id'       => 'nullable|int',
      'tanggal'          => 'nullable|date',
      'bagian_pelapor'   => 'nullable|string',
      'kategori'         => 'nullable|string',
      'kendala'          => 'nullable|string',
      'dampak'           => 'nullable|string',
      'kontak'           => 'nullable|string',
      'url'              => 'nullable|string',
      'catatan'          => 'nullable|string',
      'catatan1'         => 'nullable|string',
      'jenis'            => 'nullable|string',
      // 'status'           => 'nullable|int',
    ]);

    $kode = '';
    $index = Report::whereMonth('created_at', date('m'))
      ->whereYear('created_at', date('Y'))
      ->whereNotNull('kode')
      ->count();

    $lastRisk = Report::whereMonth('created_at', date('m'))
      ->whereYear('created_at', date('Y'))
      ->orderBy('id', 'desc')
      ->first();

    $bulan = date('m');
    $tahun = substr(date('Y'), -2);
    $bagian_id = $request->bagian_id;
    $section = SystemSectionModels::find($bagian_id);

    if ($bagian_id == null) {
      $kategori = $request->kategori;

      if ($kategori == "Hardware") {
        $bagianSistem = "HARD";
      } elseif ($kategori == "Jaringan") {
        $bagianSistem = "JAR";
      } else {
        $bagianSistem = "HJ";
      }
    } elseif ($section->bagian_sistem != '') {
      $bagianSistem = 'HJ';
    } else {
      $bagianSistem = $section->singkatan;
    }

    $totalLaporan = str_pad($index + 1, 3, '0', STR_PAD_LEFT);

    $kode = sprintf(
      'L-%s-%s-%s-%s',
      $bulan,
      $tahun,
      $bagianSistem,
      $totalLaporan
    );

    if ($lastRisk && $lastRisk->kode === $kode) {
      $index = $index - 1;
      $totalLaporan = str_pad($index + 1, 3, '0', STR_PAD_LEFT);

      $kode = sprintf(
        'L-%s-%s-%s-%s',
        $bulan,
        $tahun,
        $bagianSistem,
        $totalLaporan
      );
    }

    DB::beginTransaction();

    try {

      $report->update([
        // 'kode'             => $kode,
        // 'kode'             => $request->kode,
        'bagian_sistem'    => $request->bagian_sistem,
        'bagian_hardware'  => $request->bagian_hardware1 ?? $request->bagian_hardware,
        'bagian_id'        => $request->bagian_id,
        'tanggal'          => $request->tanggal,
        'bagian_pelapor'   => $request->bagian_pelapor,
        'kategori'         => $request->kategori,
        'kendala'          => $request->kendala,
        'dampak'           => $request->dampak,
        'kontak'           => $request->kontak,
        'url'              => $request->url,
        'catatan'          => $request->catatan,
        'catatan1'          => $request->catatan1,
        'jenis'             => $request->jenis,
        //   'status'           => $status,
        'created_by_id'    => $request->user()->id,
      ]);

      DB::commit();

      return redirect()->back()->with('success', __(
        'report dengan kode `:kode` Berhasil diubah',
        [
          'kode' => $request->kode,
        ]
      ));
    } catch (\Exception $e) {
      DB::rollBack();

      return redirect()->back()->with('error', __(
        'can\'t update report',
      ));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Report $report)
  {
    if ($report->delete()) {
      return redirect()->back()->with('success', __(
        'report `:kendala` berhasil dihapus',
        [
          'kendala' => $report->kendala,
        ]
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t delete report',
    ));
  }

  /**
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return Report::get();
  }

  /**
   * @param \App\Http\Requests\DataTableRequest $request
   * @return \Illuminate\Http\Response
   */
  public function paginate(DataTableRequest $request)
    {
    $request->validated();
    $user = $request->user();

    return Report::where(function (Builder $query) use ($request) {
                        $search = '%' . $request->search . '%';
                        $model = $query->getModel();

                        foreach ($model->getFillable() as $column){
                            $query->orWhere($column, ' like', $search);
                        }
                        
                        $query->orWhereRelation('createdBy', 'name', 'like', $search);
                        $query->orWhereRelation('bagian', 'nama', 'like', $search)->with('bagian', 'singkatan', 'like', $search);
                        })
                    ->with('feedbacks')
                    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
                    // ->when(!$user->hasRole(['superuser', 'it']), fn (Builder $query) => $query->where('created_by_id', $user->id))
                    // ->when(!$user->hasRole(['superuser']), fn (Builder $query) => $query->where('created_by_id', $user->id)->orWhereRelation('users', 'users.id', $user->id))
                    ->when(fn (Builder $query) => $query->where('created_by_id', $user->id)->orWhereRelation('users', 'users.id', $user->id))
                    ->select([
                        'id',
                        'kode',
                        'bagian_sistem',
                        'bagian_hardware',
                        'bagian_id',
                        'tanggal',
                        'bagian_pelapor',
                        'kategori',
                        'kendala',
                        'dampak',
                        'kontak',
                        'url',
                        'status',
                        'catatan',
                        'catatan1',
                        'jenis',
                        'created_by_id',
                        'created_at',
                        'updated_at',

                    ])
                        ->paginate($request->per_page ?: 10);
    }
  public function paginate1(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    return Report::where(function (Builder $query) use ($request) {
      $search = '%' . $request->search . '%';
      $model = $query->getModel();

      foreach ($model->getFillable() as $column) {
        $query->orWhere($column, ' like', $search);
      }

      $query->orWhereRelation('createdBy', 'name', 'like', $search);
    })
      ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
      // ->when(!$user->hasRole(['superuser', 'it']), fn (Builder $query) => $query->where('created_by_id', $user->id))
      ->when(!$user->hasRole(['superuser']), fn (Builder $query) => $query->where('created_by_id', $user->id)->orWhereRelation('users', 'users.id', $user->id))
      ->select([
        'id',
        'kode',
        'bagian_sistem',
        'bagian_hardware',
        'tanggal',
        'bagian_pelapor',
        'kategori',
        'kendala',
        'dampak',
        'kontak',
        'url',
        'status',
        'catatan',
        'created_by_id',
        'created_at',
        'updated_at',

      ])->where('status', '1')
      ->paginate($request->per_page ?: 10);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function detail(Report $report)
  {
    return Inertia::render('Report/Detail', [
      'report'      => $report,
      'users'       => User::get(['id', 'name']),
    ]);
  }
  public function balas(Report $report)
  {
    return Inertia::render('Report/Balas', [
      'report'      => $report,
      'users'       => User::get(['id', 'name']),
    ]);
  }

  public function repair(Request $request)
  {
    $report = Report::find($request->report);
    $report->status = 2;
    $report->catatan = $request->catatan;
    $report->save();

    return redirect()->back()->with('success', __('Berhasil diupdate'));
  }

  public function reject(Request $request)
  {
    $report = Report::find($request->report);

    $report->status = 4;
    $report->catatan1 = $request->catatan1;
    $report->save();

    return redirect()->back()->with('success', __('Berhasil diupdate'));
  }

  public function done(Request $request)
  {
    $report = Report::find($request->report);

    $report->status = 3;
    $report->save();

    $verifikasi = new Verifikasi;
    $verifikasi->report_id = $report->id;
    $verifikasi->tanggal = $request->tanggal;
    $verifikasi->catatan = $request->catatan;
    $verifikasi->created_by_id = Auth::id();
    $verifikasi->save();

    return redirect()->back()->with('success', __('Berhasil diupdate'));
  }



  public function stored(Request $request)
  {
    $request->validate([
      'report_id'        => 'required|integer|exists:reports,id',
      'kode'             => 'nullable|unique:feedback',
      'bagian_sistem'    => 'nullable|string',
      'tanggal'          => 'nullable|date',
      'bagian_pelapor'   => 'nullable|string',
      'kategori'         => 'nullable|string',
      'kendala'          => 'nullable|string',
      'dampak'           => 'nullable|string',
      'kontak'           => 'nullable|string',
      'url'              => 'nullable|string',
      'catatan'          => 'nullable|string',
      'balasan'          => 'nullable|string',
      'balasan1'         => 'nullable|string',
    ]);

    $feedback = FeedbackModels::create([
      'report_id'        => $request->report_id,
      'kode'             => $request->kode,
      'bagian_sistem'    => $request->bagian_sistem,
      'tanggal'          => $request->tanggal,
      'bagian_pelapor'   => $request->bagian_pelapor,
      'kategori'         => $request->kategori,
      'kendala'          => $request->kendala,
      'dampak'           => $request->dampak,
      'kontak'           => $request->kontak,
      'url'              => $request->url,
      'catatan'          => $request->catatan,
      'balasan'          => $request->balasan,
      'balasan1'         => $request->balasan1,
      'created_by_id'    => $request->user()->id,
    ]);

    if ($feedback) {
      return redirect()->route('feedback.index')->with('success', __(
        'feedback `:balasan` Berhasil ditambah',
        [
          'balasan' => $request->balasan,
        ]
      ));
    }

    // public function balasan(Request $request, $id)
    // {
    //     $tanggal = $request->tanggal;
    //     $catatan = $request->catatan;
    //     $status = $request->status;

    //     $verif = new Verifikasi;
    //     $verif->report_id = $id;
    //     $verif->tanggal = $tanggal;
    //     $verif->catatan = $catatan;
    //     $verif->created_by_id = Auth::id();
    //     $verif->save();

    //     // Jika status diselesaikan, ubah status menjadi 3 pada tabel report
    //     if ($status == 3) {
    //         $report = Report::findOrFail($id);
    //         $report->status = 3;
    //         $report->save();
    //     }

    //     return redirect()->back()->with('success', __('Data berhasil disimpan ke tabel verifikasi'));
    // }

  }

  public function sync(Request $request, Report $report)
  {
    $request->validate([
      'users.*' => 'required|exists:users,id',
    ]);

    $user = User::role(['admin'])->pluck('id')->toArray();

    DB::beginTransaction();

    try {
      $report->users()->sync($user);

      $report->status = 1;
      $report->save();

      DB::commit();

      return redirect()->back()->with([
        'success' => __('Data berhasil dikirim ke tim laporin'),
      ]);
    } catch (Throwable $e) {
      DB::commit();

      throw $e;
    }
  }

  public function sync1(Request $request, Report $report)
  {
    $request->validate([
      'users.*' => 'required|exists:users,id',
    ]);

    DB::beginTransaction();

    try {
      $report->users()->syncWithoutDetaching($request->users);

      if ($report->jenis == 'Penambahan Fitur Sistem') {
        $created_by_id = $report->created_by_id;

        $joblist = new JobList;
        $joblist->catatan = 'Penambahan Fitur Sistem';
        $joblist->report_id = $report->id;
        $joblist->created_by_id = $created_by_id;
        $joblist->save();
      }

      DB::commit();

      return redirect()->back()->with([
        'success' => __('Data berhasil dikirim ke tim IT'),
      ]);
    } catch (Throwable $e) {
      DB::rollback();

      return throw $e;
    }
  }
}
