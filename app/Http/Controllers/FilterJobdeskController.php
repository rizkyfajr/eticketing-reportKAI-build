<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
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

use App\Models\FeedbackModels;
use App\Models\ReportUser;
use Illuminate\Support\Facades\Auth;

class FilterJobdeskController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $jobs = ReportUser::with('user:id,name', 'report:id,kode,status')->get(); // Retrieve report users with associated user information

    return Inertia::render('Filter/Index', [
      'jobs' => $jobs,
    ]);
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
 

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
 

  /**
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return ReportUser::get();
  }

  /**
   * @param \App\Http\Requests\DataTableRequest $request
   * @return \Illuminate\Http\Response
   */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = auth()->user();

    $query = ReportUser::with('user:id,name', 'report:id,kode,status'); // Eager load user and report information

    $search = '%' . $request->search . '%';
    $query->where(function ($query) use ($search) {
      $query->where('report_id', 'like', $search)
        ->orWhere('user_id', 'like', $search)
        ->orWhereHas('user', function ($query) use ($search) {
          $query->where('name', 'like', $search); // Filter by user name
        })
          ->orWhereHas('report', function ($query) use ($search) {
            $query->where('kode', 'like', $search) // Filter by report name
              ->orWhere('status', 'like', $search);
        });
    });

    if (!$user->hasRole(['superuser', 'admin'])) {
      $query->whereHas('report', function ($query) use ($user) {
          $query->whereColumn('reports.id', 'report_user.report_id')
                ->where('report_user.user_id', $user->id);
      });
    }
    $query->where('user_id', '!=', 3);
    $orderByKey = $request->input('order.key') ?: 'created_at';
    $orderByDirection = $request->input('order.by') ?: 'desc';
    $perPage = $request->per_page ?: 10;
    $results = $query->orderBy($orderByKey, $orderByDirection)->paginate($perPage);

    return $results;
  }
  
}
