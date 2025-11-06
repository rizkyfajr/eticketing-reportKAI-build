<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceOrder;
use App\Models\MasterMachine;
use App\Models\WorkingReport;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MaintenanceOrderController extends Controller
{
    public function index()
    {
        $orders = MaintenanceOrder::with(['machine', 'workingReport'])->latest()->paginate(10);
        return Inertia::render('MaintenanceOrders/Index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $machines = MasterMachine::select('id','name','nomor','no_sarana')->get();
        $reports = WorkingReport::select('id')->get(); // nanti bisa tambah kolom lain

        return Inertia::render('MaintenanceOrders/Form', [
            'machines' => $machines,
            'reports' => $reports,
        ]);
    }
        public function edit(MaintenanceOrder $maintenanceOrder)
    {
        $maintenanceOrder->load(['machine:id,name,type,nomor,no_sarana','workingReport:id']);

        return Inertia::render('MaintenanceOrders/Form', [
            'order'    => $maintenanceOrder,
            'machines' => MasterMachine::select('id','name','nomor','no_sarana')->orderBy('name')->get(),
            'reports'  => WorkingReport::select('id')->latest()->take(100)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'working_report_id' => ['required', 'exists:working_reports,id'],
            'master_machine_id' => ['required', 'exists:master_machines,id'],
            'category'          => ['required', 'in:planned,unplanned'],
            'title'             => ['required', 'string', 'max:255'],

            // unplanned
            'trouble_at'   => ['nullable', 'required_if:category,unplanned', 'date'],
            'location'     => ['nullable', 'required_if:category,unplanned', 'string', 'max:255'],
            'problem_note' => ['nullable', 'required_if:category,unplanned', 'string'],

            // planned
            'plan_start_at' => ['nullable', 'required_if:category,planned', 'date'],
            'action_plan'   => ['nullable', 'required_if:category,planned', 'string'],
        ]);

        MaintenanceOrder::create($data);

        return redirect()->route('maintenance-orders.index')
            ->with('success', 'Maintenance Order berhasil disimpan.');
    }

    public function update(Request $request, MaintenanceOrder $maintenanceOrder)
    {
        $data = $request->validate([
            'master_machine_id' => ['required', 'exists:master_machines,id'],
            'category'          => ['required', 'in:planned,unplanned'],
            'title'             => ['required', 'string', 'max:255'],

            'trouble_at'   => ['nullable', 'required_if:category,unplanned', 'date'],
            'location'     => ['nullable', 'required_if:category,unplanned', 'string', 'max:255'],
            'problem_note' => ['nullable', 'required_if:category,unplanned', 'string'],

            'plan_start_at' => ['nullable', 'required_if:category,planned', 'date'],
            'action_plan'   => ['nullable', 'required_if:category,planned', 'string'],
        ]);

        $maintenanceOrder->update($data);

        return redirect()->route('maintenance-orders.index')
            ->with('success', 'Maintenance Order berhasil diperbarui.');
    }


  public function paginate(\Illuminate\Http\Request $request)
    {
        $q = \App\Models\MaintenanceOrder::with(['machine:id,name,type','workingReport:id']);

        // search/sort opsional
        if ($s = $request->input('search')) {
            $q->where(fn($w) => $w->where('title','like',"%{$s}%")
                                ->orWhere('problem_note','like',"%{$s}%"));
        }

        $sort = $request->input('sort', 'id');
        $dir  = $request->input('direction', 'desc');
        if (! in_array($sort, ['id','title','master_machine_id','working_report_id','category','trouble_at'])) {
            $sort = 'id'; $dir = 'desc';
        }

        $orders = $q->orderBy($sort, $dir === 'asc' ? 'asc' : 'desc')
                    ->paginate((int) $request->input('per_page', 10));

        // ambil array paginator bawaan laravel
        $arr = $orders->toArray();

        // Samakan label Next/Prev ke format Builder-mu (bukan teks bawaan laravel)
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
            'data' => $orders->items(),              // baris tabel
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
        $maintenanceOrder->load(['machine:id,name,type,nomor,no_sarana','workingReport:id']);

        return Inertia::render('MaintenanceOrders/Show', [
            'order' => $maintenanceOrder,
        ]);
    }




}
