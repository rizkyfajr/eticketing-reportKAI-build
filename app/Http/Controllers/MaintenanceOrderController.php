<?php

namespace App\Http\Controllers;

// Blok 'use' Anda sudah benar
use App\Models\MaintenanceOrder;
use App\Models\MasterMachine;
use App\Models\WorkingReport;
use App\Models\MasterPedoman;
use App\Models\MaintenanceOrderResult;
use Illuminate\Support\Facades\DB;
use App\Models\MasterPedomanItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class MaintenanceOrderController extends Controller
{
    /**
     * Halaman Index (Daftar Order)
     */
    public function index()
    {
        $orders = MaintenanceOrder::with(['machine', 'workingReport'])->latest()->paginate(10);
        return Inertia::render('MaintenanceOrders/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Halaman Form 'Create'
     */
    public function create()
    {
        $machines = MasterMachine::with('region:id,name')->select('id','name','type','nomor','hierarchy_code','no_sarana','region_id')->get();
        $reports = WorkingReport::select('id')->get();
        $pedoman = MasterPedoman::select('id', 'kode_pedoman')->orderBy('kode_pedoman')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('MaintenanceOrders/Form', [
            'machines' => $machines,
            'reports' => $reports,
            'pedoman' => $pedoman,
            'users' => $users,
        ]);
    }

    public function edit(MaintenanceOrder $maintenanceOrder)
    {
        $maintenanceOrder->load(['machine:id,name,type,nomor,no_sarana','workingReport:id']);

        return Inertia::render('MaintenanceOrders/Form', [
            'order'    => $maintenanceOrder,
            'machines' => MasterMachine::with('region:id,name')->select('id','name','type','nomor','hierarchy_code','no_sarana','region_id')->get(),
            'reports'  => WorkingReport::select('id')->latest()->take(100)->get(),
            'pedoman' => MasterPedoman::select('id', 'kode_pedoman')->orderBy('kode_pedoman')->get(),
            'users' => User::select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'working_report_id' => ['nullable', 'exists:working_reports,id'],
            'master_machine_id' => ['required', 'exists:master_machines,id'],
            'category'          => ['required', 'in:planned,unplanned'],
            'title'             => ['required', 'string', 'max:255'],
            'component_lv1_id'  => ['nullable','exists:machine_components,id'],
            'component_lv2_id'  => ['nullable','exists:machine_components,id'],
            'component_lv3_id'  => ['nullable','exists:machine_components,id'],
            'trouble_at'   => ['nullable', 'required_if:category,unplanned', 'date'],
            'location'     => ['nullable', 'required_if:category,unplanned', 'string', 'max:255'],
            'problem_note' => ['nullable', 'required_if:category,unplanned', 'string'],
            'plan_start_at' => ['nullable', 'required_if:category,planned', 'date'],
            'action_plan'   => ['nullable', 'string'],
            'lampiran'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // Max 5MB
            'master_pedoman_id' => ['nullable', 'required_if:category,planned', 'exists:master_pedoman,id'],
            'machine_hours' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($request->hasFile('lampiran')) {
            // Simpan file di 'storage/app/public/lampiran_orders'
            $path = $request->file('lampiran')->store('lampiran_orders', 'public');
            $data['lampiran_path'] = $path;
        }
        $newOrder = MaintenanceOrder::create($data);

        // Load relasi master_pedoman untuk checksheet
        $newOrder->load([
            'masterPedoman.categories.items' => function ($query) {
                $query->orderBy('nomor_poin');
            }
        ]);

        // Return dengan render ulang form dengan order yang baru dibuat (untuk wizard flow)
        return Inertia::render('MaintenanceOrders/Form', [
            'machines' => MasterMachine::with('region:id,name')->select('id','name','type','nomor','hierarchy_code','no_sarana','region_id')->get(),
            'reports' => WorkingReport::select('id')->get(),
            'pedoman' => MasterPedoman::select('id', 'kode_pedoman')->orderBy('kode_pedoman')->get(),
            'users' => User::select('id', 'name')->orderBy('name')->get(),
            'newOrder' => $newOrder, // Kirim order yang baru dibuat
        ])->with('success', 'Maintenance Order berhasil disimpan.');
    }

    public function update(Request $request, MaintenanceOrder $maintenanceOrder)
    {
        $data = $request->validate([
            'working_report_id' => ['nullable', 'exists:working_reports,id'],
            'master_machine_id' => ['required', 'exists:master_machines,id'],
            'category'          => ['required', 'in:planned,unplanned'],
            'title'             => ['required', 'string', 'max:255'],
            'component_lv1_id'  => ['nullable','exists:machine_components,id'],
            'component_lv2_id'  => ['nullable','exists:machine_components,id'],
            'component_lv3_id'  => ['nullable','exists:machine_components,id'],
            'trouble_at'   => ['nullable', 'required_if:category,unplanned', 'date'],
            'location'     => ['nullable', 'required_if:category,unplanned', 'string', 'max:255'],
            'problem_note' => ['nullable', 'required_if:category,unplanned', 'string'],
            'plan_start_at' => ['nullable', 'required_if:category,planned', 'date'],
            'action_plan'   => ['nullable', 'string'],
            'lampiran'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'master_pedoman_id' => ['nullable', 'required_if:category,planned', 'exists:master_pedoman,id'],
            'machine_hours' => ['nullable', 'numeric', 'min:0'],
        ]);
        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('lampiran_orders', 'public');
            $data['lampiran_path'] = $path;
        }
        $maintenanceOrder->update($data);

        return redirect()->route('maintenance-orders.index')
            ->with('success', 'Maintenance Order berhasil diperbarui.');
    }


    public function paginate(\Illuminate\Http\Request $request)
    {
        $q = \App\Models\MaintenanceOrder::with(['machine:id,name,type','workingReport:id']);

        if ($s = $request->input('search')) {
            $q->where(fn($w) => $w->where('title','like',"%{$s}%")
                                ->orWhere('problem_note','like',"%{$s}%"));
        }

        $sort = $request->input('sort', 'id');
        $dir  = $request->input('direction', 'desc');

        if (! in_array($sort, [
            'id','title','master_machine_id','working_report_id',
            'category','trouble_at',
            'status', 'plan_start_at'
        ])) {
            $sort = 'id'; $dir = 'desc';
        }

        $orders = $q->orderBy($sort, $dir === 'asc' ? 'asc' : 'desc')
                    ->paginate((int) $request->input('per_page', 10));

        $arr = $orders->toArray();
        $links = collect($arr['links'])->map(function ($link) {
            $label = $link['label'];
            if ($label === '&laquo; Previous') $label = 'pagination.previous';
            if ($label === 'Next &raquo;')     $label = 'pagination.next';
            return [
                'url'    => $link['url'],
                'label'  => is_numeric($label) ? intval($label) : $label,
                'active' => (bool) $link['active'],
            ];
        })->values();

        return response()->json([
            'data' => $orders->items(),
            'paginator' => [
                'current_page'  => $arr['current_page'],
                'from'          => $arr['from'],
                'to'            => $arr['to'],
                'total'         => $arr['total'],
                'per_page'      => $arr['per_page'],
                'prev_page_url' => $arr['prev_page_url'],
                'next_page_url' => $arr['next_page_url'],
                'links'         => $links,
            ],
        ]);
    }


    public function show(MaintenanceOrder $maintenanceOrder)
    {
        $maintenanceOrder->load(['machine:id,name,type,nomor,no_sarana','workingReport:id', 'masterPedoman.categories.items', 'results']);
        $users = User::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('MaintenanceOrders/Show', [
            'order' => $maintenanceOrder,
            'users' => $users,
        ]);
    }


    public function storeResults(Request $request, MaintenanceOrder $maintenanceOrder)
    {

        if ($maintenanceOrder->status !== 'DIKERJAKAN') {
            return redirect()->back()->with('error', 'Hanya bisa menyimpan checklist jika status pekerjaan "DIKERJAKAN".');
        }


        $request->validate([
            'results' => ['required', 'array'],
            'results.*.realisasi' => ['nullable', 'string', 'max:255'],
            'results.*.status' => ['required', 'string', 'in:OK,NG,N/A'],
            'results.*.catatan' => ['nullable', 'string'],
        ]);

        $resultsData = $request->input('results');


        $now = (new \DateTime())->format('Y-m-d H:i:s');

        try {
            DB::beginTransaction();

            foreach ($resultsData as $itemId => $result) {
                MaintenanceOrderResult::updateOrCreate(
                    [
                        'maintenance_order_id' => $maintenanceOrder->id,
                        'master_pedoman_item_id' => $itemId,
                    ],
                    [
                        'realisasi' => $result['realisasi'],
                        'status' => $result['status'],
                        'catatan' => $result['catatan'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan hasil: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Hasil checklist berhasil disimpan.');
    }

    public function followUp(Request $request, MaintenanceOrder $maintenanceOrder)
    {
        $data = $request->validate([
            'follow_up_by_id' => ['required', 'exists:users,id'],
            'follow_up_plan' => ['required', 'string'],
            'follow_up_estimate_at' => ['required', 'date'],
        ]);

        $data['status'] = 'DIPROSES'; // <-- UBAH STATUS
        $data['follow_up_at'] = (new \DateTime())->format('Y-m-d H:i:s');

        $maintenanceOrder->update($data);

        return redirect()->back()->with('success', 'Follow Up Plan berhasil disimpan.');
    }

    public function startRepair(Request $request, MaintenanceOrder $maintenanceOrder)
    {
        $data = $request->validate([
            'start_repair_by_id' => ['required', 'exists:users,id'],
            'start_repair_notes' => ['required', 'string'],
            'start_repair_photo' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('start_repair_photo')) {
            $path = $request->file('start_repair_photo')->store('repair_photos', 'public');
            $data['start_repair_photo'] = $path;
        }

        $data['status'] = 'DIKERJAKAN';
        $data['start_repair_at'] = (new \DateTime())->format('Y-m-d H:i:s');

        $maintenanceOrder->update($data);

        return redirect()->back()->with('success', 'Pekerjaan telah dimulai.');
    }

    public function complete(Request $request, MaintenanceOrder $maintenanceOrder)
    {
        if ($maintenanceOrder->status !== 'DIKERJAKAN') {
            return redirect()->back()->with('error', 'Hanya pekerjaan berstatus "DIKERJAKAN" yang bisa diselesaikan.');
        }

        $data = $request->validate([
            'complete_repair_by_id' => ['required', 'exists:users,id'],
            'complete_repair_notes' => ['required', 'string'],
            'complete_repair_photo' => ['nullable', 'file', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('complete_repair_photo')) {
            $path = $request->file('complete_repair_photo')->store('repair_photos', 'public');
            $data['complete_repair_photo'] = $path;
        }

        $data['status'] = 'SELESAI';
        $data['complete_repair_at'] = (new \DateTime())->format('Y-m-d H:i:s');

        $maintenanceOrder->update($data);

        return redirect()->route('maintenance-orders.index')
            ->with('success', 'Pekerjaan Order #'.$maintenanceOrder->id.' telah Selesai.');
    }

    public function printPDF(MaintenanceOrder $maintenanceOrder)
    {
        // Load semua relasi yang diperlukan
        $maintenanceOrder->load([
            'machine.region',
            'masterPedoman.categories.items' => function ($query) {
                $query->orderBy('nomor_poin');
            },
            'results',
            'workingReport',
            'followUpBy:id,name',
            'startRepairBy:id,name',
            'completeRepairBy:id,name'
        ]);

        // Parse hasil checksheet
        $checklistData = [];
        if ($maintenanceOrder->masterPedoman && $maintenanceOrder->masterPedoman->categories) {
            foreach ($maintenanceOrder->masterPedoman->categories as $category) {
                foreach ($category->items as $item) {
                    $result = $maintenanceOrder->results->firstWhere('master_pedoman_item_id', $item->id);

                    $realisasi = $result->realisasi ?? '';

                    // Parse JSON untuk tipe table
                    if ($item->tipe_input === 'table' && $realisasi) {
                        try {
                            $realisasi = json_decode($realisasi, true);
                        } catch (\Exception $e) {
                            $realisasi = [];
                        }
                    }

                    $checklistData[$item->id] = [
                        'realisasi' => $realisasi,
                        'status' => $result->status ?? 'OK',
                        'catatan' => $result->catatan ?? '',
                    ];
                }
            }
        }

        $pdf = \PDF::loadView('maintenance-orders.pdf', [
            'order' => $maintenanceOrder,
            'checklistData' => $checklistData
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan-Maintenance-Order-'.$maintenanceOrder->id.'.pdf');
    }
}
