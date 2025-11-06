<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobList;
use App\Models\Report;
use App\Http\Requests\DataTableRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Throwable;
use PDF;

class JobListController extends Controller
{

  /**
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return JobList::get();
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(JobList $joblist, Report $report)
  {
      return Inertia::render('List/Index', [
          'report'      => $report,
          'joblist'     => $joblist,
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
          'report_id' => 'required|integer|exists:reports,id',
          'catatan' => 'nullable|string',
      ]);

      $joblist = Joblist::create([
          'report_id' => $request->report_id,
          'catatan' => $request->catatan,
          'created_by_id' => $request->user()->id,
      ]);

      if ($joblist) {
          $report = Report::find($request->report_id);
          if ($report) {
              $report->update(['status' => 5]);
          }
          return redirect()->back()->with('success', __(
              'Data Pekerjaan berhasil ditambahkan'
          ));
      }

      return redirect()->back()->with('error', __(
          'can\'t create Pekerjaan',
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
  public function update(Request $request, JobList $joblist)
  {
      $request->validate([
          'report_id'          => 'required|integer|exists:reports,id',
          'catatan'            => 'nullable|string',
        ]);

        if ($joblist->update([ 
            'catatan' => $request->catatan, 
            ])) {

            return redirect()->back()->with('success', __(
                'Data Pekerjaan `:catatan` berhasil diubah', [
                    'catatan' => $request->catatan,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update pekerjaan',
        ));
    }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Joblist $joblist)
    {
        if ($joblist->delete()) {
            return redirect()->back()->with('success', __(
                'Data Pekerjaan `:catatan` berhasil dihapus', [
                    'catatan' => $joblist->catatan,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t delete Pekerjaan',
        ));
    }

    public function paginate(DataTableRequest $request)
    {
      $request->validated();
      $user = $request->user();

      return Joblist::where(function (Builder $query) use ($request) {
                          $search = '%' . $request->search . '%';
                          $model = $query->getModel();

                          foreach ($model->getFillable() as $column){
                            $query->orWhere($column, ' like', $search);
                          }
                          
                          $query->orWhereRelation('createdBy', 'name', 'like', $search);
                        })
                    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
                    // ->when(function (Builder $query) use ($user) {
                    //     $query->where(function (Builder $query) use ($user) {
                    //         $query->where('created_by_id', $user->id)
                    //             ->orWhere(function (Builder $query) {
                    //                 $query->whereHas('report', function (Builder $query) {
                    //                     $query->whereColumn('reports.id', 'job_lists.report_id')
                    //                         ->whereExists(function ($query) {
                    //                             $query->select(DB::raw(1))
                    //                                 ->from('report_user')
                    //                                 ->whereColumn('report_user.report_id', 'reports.id');
                    //                         });
                    //                 });
                    //             });
                    //     });
                    // })
                    ->when(!$user->hasRole(['admin']), function (Builder $query) use ($user) {
                        $query->where(function (Builder $query) use ($user) {
                            $query->where('created_by_id', $user->id)
                                  ->orWhereHas('report', function (Builder $query) use ($user) {
                                      $query->whereColumn('reports.id', 'job_lists.report_id')
                                            ->where(function ($query) use ($user) {
                                                $query->whereExists(function ($query) use ($user) {
                                                    $query->select(DB::raw(1))
                                                          ->from('report_user')
                                                          ->whereColumn('report_user.report_id', 'reports.id')
                                                          ->where('report_user.user_id', $user->id);
                                                });
                                            });
                                  });
                        });
                    })
                    
                    
                    
                    ->with('report')
                    ->select([
                        'id',
                        'report_id',
                        'catatan',
                        'created_by_id',
                        'created_at',
                        'updated_at',
                    ])
                    // ->whereHas('report', function (Builder $query) {
                    //     $query->where('status', '!=', 3);
                    // })
                    ->paginate($request->per_page ?: 10);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function detail(Joblist $joblist, Report $report)
    {
        return Inertia::render('List/Detail', [
            'joblist'         => $joblist,
            'report'          => $report,
            'users'           => User::get(['id', 'name']),
        ]);
    }
}
