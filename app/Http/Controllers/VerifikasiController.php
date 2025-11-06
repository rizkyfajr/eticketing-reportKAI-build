<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Verifikasi;
use App\Models\Report;
use App\Http\Requests\DataTableRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Throwable;
use PDF;

class VerifikasiController extends Controller
{

  /**
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return Verifikasi::get();
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Verifikasi $verifikasi, Report $report)
  {
      return Inertia::render('Report/Verifikasi', [
          'report'      => $report,
          'verifikasi'  => $verifikasi,
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
    $validated = $request->validate([
      'report_id'         => 'required|integer|exists:reports,id',
      'tanggal'           => 'nullable|date',
      'catatan'           => 'nullable|string',
    ]);
    
    $verifikasi = Verifikasi::create([
      'report_id'         => $request->report_id,
      'tanggal'           => $request->tanggal,
      'catatan'           => $request->catatan,
      'created_by_id'     => $request->user()->id,
    ]);

    if ($verifikasi) {
        return redirect()->back()->with('success', __(
            'Data verifikasi berhasil ditambahkan'
        ));
    }

    return redirect()->back()->with('error', __(
        'can\'t create verifikasi',
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
  public function update(Request $request, Verifikasi $verifikasi)
  {
      $request->validate([
          'report_id'          => 'required|integer|exists:reports,id',
          'tanggal'            => 'nullable|date',
          'catatan'            => 'nullable|string',
        ]);

        if ($verifikasi->update([ 
            'tanggal' => $request->tanggal, 
            'catatan' => $request->catatan, 
            ])) {

            return redirect()->back()->with('success', __(
                'Data verifikasi `:tanggal` berhasil diubah', [
                    'tanggal' => $request->tanggal,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update verifikasi',
        ));
    }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Verifikasi $verifikasi)
    {
        if ($verifikasi->delete()) {
            return redirect()->back()->with('success', __(
                'Data verifikasi `:tanggal` berhasil dihapus', [
                    'tanggal' => $verifikasi->tanggal,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t delete verifikasi',
        ));
    }

    public function paginate(DataTableRequest $request)
    {
      $request->validated();
      $user = $request->user();

      return Verifikasi::where(function (Builder $query) use ($request) {
                          $search = '%' . $request->search . '%';
                          $model = $query->getModel();

                          foreach ($model->getFillable() as $column){
                            $query->orWhere($column, ' like', $search);
                          }
                          
                          $query->orWhereRelation('createdBy', 'name', 'like', $search);
                        })
                    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
                    ->when(!$user->hasRole(['superuser', 'admin']), function (Builder $query) use ($user) {
                        $query->where(function (Builder $query) use ($user) {
                            $query->where('created_by_id', $user->id)
                                ->orWhereHas('report', function (Builder $query) use ($user) {
                                    $query->whereColumn('reports.id', 'verifications.report_id')
                                        ->where('created_by_id', $user->id);
                                });
                        });
                    })
                    
                    ->with('report')
                    ->select([
                        'id',
                        'report_id',
                        'tanggal',
                        'catatan',
                        'created_by_id',
                        'created_at',
                        'updated_at',
                    ])
                    ->paginate($request->per_page ?: 10);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function detail(Verifikasi $verifikasi, Report $report)
    {
        return Inertia::render('Closed/Detail', [
            'verifikasi'      => $verifikasi,
            'report'          => $report,
            'users'           => User::get(['id', 'name']),
        ]);
    }
}
