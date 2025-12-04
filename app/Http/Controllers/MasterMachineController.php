<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DataTableRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\MasterMachine;
use App\Models\MasterRegion;
use App\Models\MasterClassification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class MasterMachineController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index(MasterMachine $machine)
  {
    return Inertia::render('Machine/Index', [
      'machine'      => $machine,
      'regions'     => MasterRegion::select('id', 'name')->get(),
      'classifications' => MasterClassification::select('id', 'name')->get(),
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
        'region_id' => 'required|exists:master_regions,id',
        'classification_id' => 'nullable|exists:master_classifications,id',
        'name'      => 'required|string|max:255',
        'type'      => 'nullable|string|max:255',
        'nomor'     => 'nullable|string|max:255',
        'tahun_md'  => 'nullable|integer|',
        'umur'      => 'nullable|integer|',
        'no_sarana' => 'nullable|string|max:255',
        'keterangan'=> 'nullable|string|max:255',
        'hierarchy_code' => 'nullable|string|max:255',

    ]);

    $machine = MasterMachine::create([
        'region_id' => $request['region_id'],
        'classification_id' => $request->classification_id,
        'name'      => $request->name,
        'type'      => $request->type,
        'hierarchy_code' => $request->hierarchy_code,
        'nomor'     => $request->nomor,
        'tahun_md'  => $request->tahun_md,
        'umur'      => $request->umur,
        'no_sarana' => $request->no_sarana,
        'keternagan'=> $request->keternagan,
    ]);

    if ($machine) {
        return redirect()->back()->with('success', __(
            'Mesin ":name" berhasil ditambahkan.',
            ['name' => $request->name]
        ));
    }

      return redirect()->back()->with('error', __('Gagal menambahkan mesin.'));
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
      $machine = MasterMachine::findOrFail($id);

      $validated = $request->validate([
          'region_id' => 'required|exists:master_regions,id',
          'classification_id' => 'nullable|exists:master_classifications,id',
          'name'      => 'required|string|max:255',
          'type'      => 'nullable|string|max:255',
          'nomor'     => 'nullable|string|max:255',
          'tahun_md'  => 'nullable|integer|',
          'umur'      => 'nullable|integer|',
          'no_sarana' => 'nullable|string|max:255',
          'keterangan'=> 'nullable|string|max:255',
      ]);

      $machine->update($validated);

      return redirect()->back()->with('success', __(
            'Mesin ":name" berhasil diperbarui.',
            ['name' => $request->name]
      ));
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
      $machine = MasterMachine::findOrFail($id);
      $machine->delete();

      return redirect()->back()->with('success', __(
            'Mesin ":name" berhasil dihapus.',
            ['name' => $request->name]
        ));
  }

  /**
  * @return \Illuminate\Http\Response
  */
  public function get()
  {
    return MasterMachine::get();
  }

  /**
  * @param \App\Http\Requests\DataTableRequest $request
  * @return \Illuminate\Http\Response
  */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    $machines = MasterMachine::where(function (Builder $query) use ($request) {
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
    ->select(['id', 'region_id', 'classification_id', 'name', 'type', 'nomor', 'tahun_md', 'umur', 'umur', 'no_sarana', 'keterangan'])
    ->paginate($request->per_page ?: 10);

    return response()->json($machines);
  }

  /**
  * Generate QR Code for a machine
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function generateQrCode($id)
  {
    $machine = MasterMachine::findOrFail($id);

    // Generate QR code data - JSON dengan info mesin
    $qrData = json_encode([
      'machine_id' => $machine->id,
      'name' => $machine->name,
      'type' => $machine->type,
      'nomor' => $machine->nomor,
      'no_sarana' => $machine->no_sarana,
      'region_id' => $machine->region_id,
      'classification_id' => $machine->classification_id,
    ]);

    // Generate QR code filename
    $fileName = 'qr_machine_' . $machine->id . '.svg';

    // Save QR code ke storage
    $qrCode = QrCode::size(300)
      ->format('svg')
      ->generate($qrData);

    Storage::put('public/qrcodes/' . $fileName, $qrCode);

    // Update machine dengan path QR code
    $machine->update(['qr_code' => 'qrcodes/' . $fileName]);

    return redirect()->back()->with('success', __('QR Code berhasil dibuat.'));
  }

  /**
  * Download QR Code
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function downloadQrCode($id)
  {
    $machine = MasterMachine::findOrFail($id);

    if (!$machine->qr_code) {
      return redirect()->back()->with('error', __('QR Code belum dibuat.'));
    }

    $filePath = storage_path('app/public/' . $machine->qr_code);

    if (!file_exists($filePath)) {
      return redirect()->back()->with('error', __('File QR Code tidak ditemukan.'));
    }

    return response()->download($filePath, 'QR_' . $machine->no_sarana . '.svg');
  }

  /**
  * View QR Code
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function viewQrCode($id)
  {
    $machine = MasterMachine::with(['region', 'classification'])->findOrFail($id);

    // Auto-generate if not exists
    if (!$machine->qr_code) {
      $qrData = json_encode([
        'machine_id' => $machine->id,
        'name' => $machine->name,
        'type' => $machine->type,
        'nomor' => $machine->nomor,
        'no_sarana' => $machine->no_sarana,
        'region_id' => $machine->region_id,
        'classification_id' => $machine->classification_id,
      ]);

      $fileName = 'qr_machine_' . $machine->id . '.svg';
      $qrCode = QrCode::size(300)->format('svg')->generate($qrData);
      Storage::put('public/qrcodes/' . $fileName, $qrCode);
      $machine->update(['qr_code' => 'qrcodes/' . $fileName]);
    }

    return Inertia::render('Machine/QrCode', [
      'machine' => $machine,
      'qrCodeUrl' => asset('storage/' . $machine->qr_code),
    ]);
  }
}
