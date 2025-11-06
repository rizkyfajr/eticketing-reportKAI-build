<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableRequest;
use App\Models\Report;
use App\Models\Upload;
use App\Models\WorkResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AttachmentController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    
  }
  
  /**
 * @param \App\Http\Requests\DataTableRequest $request
 * @return \Illuminate\Http\Response
 */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $columns = ['filename', 'size', 'description'];
    $type = $this->findModel($request->input('request.model_type'));

    return Attachment::where(function (Builder $query) use ($request, $columns) {
                          $search = '%' . $request->search . '%';

                          foreach ($columns as $column) {
                            $query->orWhere($column, 'like', $search);
                          }

                          $query->orWhereRelation('uploader', 'name', 'like', $search);
                        })
                        ->where('attachmentable_id', $request->input('request.model_id'))
                        ->where('attachmentable_type', $type)
                        ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.dir') ?: 'asc')
                        ->paginate($request->per_page ?: 10);
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function upload(Request $request)
  {
    $request->validate([
      'model_id' => 'required|integer',
      'model_type' => 'required|string',
      'attachment' => 'required',
    ]);

    $type = $this->findModel($request->model_type);

    if (is_null($type)) {
      return response()->json([
        'message' => 'Invalid model type.',
      ], 422);
    }

    $model = (new $type)->findOrFail($request->model_id);
    $path = sprintf('%s/%s/%s/', 
      date('Y'),
      last(explode('\\', $request->model_type)),
      str_pad($request->model_id, 4, '0', STR_PAD_LEFT)
    );

    DB::beginTransaction();

    try {
      foreach ($request->attachment as $i => $attachment) {
        $filename = sprintf('(%s) %s', date('YmdHis'), $attachment['name']);
        
        $created = $model->attachments()->create([
          'uploader_id' => $request->user()->id,
          'path' => $path,
          'filename' => $filename,
          'size' => $attachment['size'],
          'description' => array_key_exists('description', $attachment) ? $attachment['description'] : null,
        ]);

        $attachment['file']->storeAs($path, $filename, 'public');
      }

      DB::commit();

      return redirect()->back()->with([
        'success' => 'Lampiran berhasil di upload.',
      ]);
    } catch (\Exception $e) {
      DB::rollBack();

      return redirect()->back()->with([
        'error' => 'Lampiran gagal di upload. Coba lagi nanti / hubungi admin.',
        'message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Find the model
   * 
   * @param string $type
   */
  private function findModel($type)
  {
    switch ($type) {
      case 'Report': return Report::class; break;
      case 'Upload': return Upload::class; break;
      case 'WorkResult': return WorkResult::class; break;
      
      default: return null; break;
    }
  }
  
  /**
  * Display the specified resource.
  *
  * @param  \App\Models\Attachment  $attachment
  * @return \Illuminate\Http\Response
  */
  public function show(Attachment $attachment)
  {
    $filePath = storage_path('app/public/' . $attachment->path . $attachment->filename);

    if (! file_exists($filePath)) {
      return view('errors.404');
    }

    return response()->download($filePath);
  }
  
  /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Models\Attachment  $attachment
  * @return \Illuminate\Http\Response
  */
  public function edit(Attachment $attachment)
  {
    //
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Models\Attachment  $attachment
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Attachment $attachment)
  {
    //
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Models\Attachment  $attachment
  * @return \Illuminate\Http\Response
  */
  public function destroy(Attachment $attachment)
  {
    DB::beginTransaction();

    try {
      $attachment->delete();

      Storage::disk('public')->delete($attachment->path . $attachment->filename);

      DB::commit();

      return redirect()->back()->with('success', 'Lampiran berhasil dihapus.');
    } catch (Throwable $e) {
      DB::rollBack();

      return redirect()->back()->with([
        'error' => 'Lampiran gagal dihapus.',
        'message' => $e->getMessage(),
      ]);
    }
  }
}
