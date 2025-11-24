<?php

namespace App\Http\Controllers;

use App\Models\MachineComponent;
use App\Models\MasterMachine;
use Illuminate\Http\Request;

class MachineComponentController extends Controller
{
    // API untuk form MO (punya kamu kemarin)
    public function index(Request $request)
    {
        $request->validate([
            'machine_type' => ['required', 'string'],
            'parent_id'    => ['nullable', 'integer'],
        ]);

        $query = MachineComponent::query()
            ->where('machine_type', $request->machine_type);

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        } else {
            $query->whereNull('parent_id');
        }

        return response()->json(
            $query->orderBy('code')->get(['id','code','name','parent_id','machine_type','level'])
        );
    }

    public function indexPage(Request $request)
    {
        $machines = \App\Models\MasterMachine::orderBy('name')->get(['id','name','type','hierarchy_code','nomor']);

        $query = \App\Models\MachineComponent::query()
            ->with('parent')
            ->orderBy('machine_type')
            ->orderBy('level')
            ->orderBy('code');

        if ($request->filled('machine_type')) {
            $query->where('machine_type', $request->machine_type);
        }

        $components = $query->get(['id','code','name','level','parent_id','machine_type']);

        return inertia('MachineComponents/Index', [
            'machines' => $machines,
            'components' => $components,
            'filterMachine' => $request->machine_type ?? null,
        ]);
    }

    public function create()
    {
        // kirim daftar mesin ke Vue
        $machines = MasterMachine::orderBy('name')->get(['id','name','type','hierarchy_code','nomor']);
        return inertia('MachineComponents/Create', [
            'machines' => $machines,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_machine_id' => ['required','integer','exists:master_machines,id'],
            'machine_type'      => ['nullable','string','max:255'],
            'level'             => ['required','integer','min:1','max:5'],
            'code'              => ['nullable','string','max:50'],
            'name'              => ['required','string','max:255'],
            'parent_id'         => ['nullable','integer','exists:machine_components,id'],
        ]);

        // ambil mesin
        $machine = MasterMachine::findOrFail($data['master_machine_id']);

        // pastikan machine_type keisi dengan pola data kamu:
        // pakai hierarchy_code dulu, kalau kosong baru type
        $data['machine_type'] = $machine->hierarchy_code ?: $machine->type;

        // kalau level 1 → parent null
        if ((int)$data['level'] === 1) {
            $data['parent_id'] = null;
        }

        MachineComponent::create($data);

        return redirect()
            ->route('machine-components.create')
            ->with('success', 'Komponen berhasil ditambahkan');
    }
}
