<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Report;
use App\Models\WorkingReport;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ReadinessAssessmentMaster;
use App\Models\ReadinessAssessment;
use App\Models\ReportUser;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
  /**
   * @return \Illuminate\Http\Response
   */
  public function index(Report $report)
  {

    $user = auth()->user();
    $isSuperuserOrAdmin = $this->hasRole($user, ['superuser', 'admin']);
    $today = Carbon::today()->toDateString();

    // Cek apakah user sudah mengisi assessment hari ini (Raidnes Assessments)
    $hasCompletedAssessment = ReadinessAssessment::where('user_id', $user->id)
        ->whereDate('assessment_date', $today)
        ->count() === ReadinessAssessmentMaster::count();

    $assessmentData = null;

    if (!$hasCompletedAssessment) {
        $masterQuestions = ReadinessAssessmentMaster::orderBy('urutan')
            ->orderBy('group_name') 
            ->get();

        $existingAssessments = ReadinessAssessment::where('user_id', $user->id)
            ->whereDate('assessment_date', $today)
            ->get()
            ->keyBy('assessment_master_id');

        $groupedQuestions = $masterQuestions->groupBy('group_name')->map(function ($group) use ($existingAssessments) {
            return $group->map(function ($question) use ($existingAssessments) {
                $assessment = $existingAssessments->get($question->id);
                $question->ya = $assessment ? $assessment->ya : null;
                $question->tidak = $assessment ? $assessment->tidak : null;
                return $question;
            });
        });

        $assessmentData = [
            'groupedQuestions' => $groupedQuestions,
            'today' => $today,
        ];
    }
    // Cek apakah user sudah mengisi assessment hari ini (Raidnes Assessments)

    // Dashboard Working Order
    $reports = WorkingReport::with([
        'machine:id,name,nomor,type',
        'warmingup',
        'workresult',
    ])->get();

    $totalPerMesin = [];
    $totalJamGenerator = [];
    $totalCounterTamping = [];
    $totalOdometer = [];
    $totalHSD = [];

    foreach ($reports as $wr) {

        $machine = $wr->machine;

        if ($machine) {
            $machineName = ' [' . $machine->nomor .'] ' . $machine->name . ' - ' . $machine->type . '';
        } else {
            $machineName = 'UNKNOWN';
        }

        $start = $wr->waktu_start_engine;
        $stopWarm = $wr->warmingup?->waktu_stop_engine;
        $stopWork = $wr->workresult?->waktu_stop_engine;

        $stop = null;
        if ($stopWarm && $stopWork) {
            $stop = max($stopWarm, $stopWork);
        } elseif ($stopWarm) {
            $stop = $stopWarm;
        } elseif ($stopWork) {
            $stop = $stopWork;
        }

       if ($start && $stop) {
            try {
                $startTime = Carbon::createFromTimeString($start);
                $stopTime  = Carbon::createFromTimeString($stop);

                if ($stopTime->lessThan($startTime)) {
                    $stopTime->addDay();
                }

                $diffMinutes = $stopTime->diffInMinutes($startTime);

                if (!isset($totalPerMesin[$machineName])) {
                    $totalPerMesin[$machineName] = 0;
                }
                $totalPerMesin[$machineName] += $diffMinutes;

            } catch (\Exception $e) {
            }
        }

        $genStart = $wr->jam_generator_awal;
        $genStopWarm = $wr->warmingup?->jam_generator_akhir;
        $genStopWork = $wr->workresult?->jam_generator_akhir;

        $genStop = null;
        if ($genStopWarm !== null && $genStopWork !== null) {
            $genStop = max($genStopWarm, $genStopWork);
        } elseif ($genStopWarm !== null) {
            $genStop = $genStopWarm;
        } elseif ($genStopWork !== null) {
            $genStop = $genStopWork;
        }

        if (!isset($totalJamGenerator[$machineName])) {
            $totalJamGenerator[$machineName] = 0;
        }

        if ($genStart !== null && is_numeric($genStart)) {
            $totalJamGenerator[$machineName] += $genStart;
        }

        if ($genStop !== null && is_numeric($genStop)) {
            $totalJamGenerator[$machineName] += $genStop;
        }

        $tampStart = $wr->counter_tamping_awal;
        $tampStopWarm = $wr->warmingup?->counter_tamping_akhir;
        $tampStopWork = $wr->workresult?->counter_tamping_akhir;

        $tampStop = null;
        if ($tampStopWarm !== null && $tampStopWork !== null) {
            $tampStop = max($tampStopWarm, $tampStopWork);
        } elseif ($tampStopWarm !== null) {
            $tampStop = $tampStopWarm;
        } elseif ($tampStopWork !== null) {
            $tampStop = $tampStopWork;
        }

        if (!isset($totalCounterTamping[$machineName])) {
            $totalCounterTamping[$machineName] = 0;
        }

        if ($tampStart !== null && is_numeric($tampStart)) {
            $totalCounterTamping[$machineName] += $tampStart;
        }

        if ($tampStop !== null && is_numeric($tampStop)) {
            $totalCounterTamping[$machineName] += $tampStop;
        }

        $odoStart = $wr->oddometer_awal;
        $odoStopWarm = $wr->warmingup?->oddometer_akhir;
        $odoStopWork = $wr->workresult?->oddometer_akhir;

        $odoStop = null;
        if ($odoStopWarm !== null && $odoStopWork !== null) {
            $odoStop = max($odoStopWarm, $odoStopWork);
        } elseif ($odoStopWarm !== null) {
            $odoStop = $odoStopWarm;
        } elseif ($odoStopWork !== null) {
            $odoStop = $odoStopWork;
        }

        if (!isset($totalOdometer[$machineName])) {
            $totalOdometer[$machineName] = 0;
        }

        if ($odoStart !== null && is_numeric($odoStart)) {
            $totalOdometer[$machineName] += $odoStart;
        }

        if ($odoStop !== null && is_numeric($odoStop)) {
            $totalOdometer[$machineName] += $odoStop;
        }

        $hsdStart = $wr->hsd_awal_kerja;
        $hsdStopWarm = $wr->warmingup?->hsd_akhir_kerja;
        $hsdStopWork = $wr->workresult?->hsd_akhir_kerja;

        $hsdStop = null;
        if ($hsdStopWarm !== null && $hsdStopWork !== null) {
            $hsdStop = max($hsdStopWarm, $hsdStopWork);
        } elseif ($hsdStopWarm !== null) {
            $hsdStop = $hsdStopWarm;
        } elseif ($hsdStopWork !== null) {
            $hsdStop = $hsdStopWork;
        }

        if (!isset($totalHSD[$machineName])) {
            $totalHSD[$machineName] = 0;
        }

        if ($hsdStart !== null && is_numeric($hsdStart)) {
            $totalHSD[$machineName] += $hsdStart;
        }

        if ($hsdStop !== null && is_numeric($hsdStop)) {
            $totalHSD[$machineName] += $hsdStop;
        }
    }
    
    $formatted = [];
    foreach ($totalPerMesin as $mesin => $menit) {
        $formatted[$mesin] = floor($menit / 60) . " Jam " . ($menit % 60) . " Menit";
    }

    $formattedGenerator = [];
    foreach ($totalJamGenerator as $mesin => $jam) {
        $formattedGenerator[$mesin] = $jam . " Jam";
    }

    $formattedTamping = [];
    foreach ($totalCounterTamping as $mesin => $counter) {
        $formattedTamping[$mesin] = number_format($counter, 0, ',', '.') . " Counter "; 
    }

    $formattedOdometer = [];
    foreach ($totalOdometer as $mesin => $odo) {
        $formattedOdometer[$mesin] = number_format($odo, 0, ',', '.') . " Km "; 
    }

    $formattedHSD = [];
    foreach ($totalHSD as $mesin => $hsd) {
        $formattedHSD[$mesin] = number_format($hsd, 2, ',', '.') . " % "; 
    }

    // dd($formatted, $formattedGenerator, $formattedTamping, $formattedOdometer, $formattedHSD);

    return Inertia::render('Dashboard', [
      'formatted_mesin_total' => $formatted,
      'formatted_generator_total' => $formattedGenerator,
      'formatted_counter_total' => $formattedTamping,
      'formatted_oddometer_total' => $formattedOdometer,
      'formatted_hsd_total' => $formattedHSD,
      'hasCompletedAssessment' => $hasCompletedAssessment,
      'assessmentData' => $assessmentData,
      'users' => auth()->user()->load([
          'divisions:id,division_name',
          'positions:id,position',
      ]),
    ]);
  }

  public function get()
  {
    return Report::get();
  }

  private function hasRole($user, $roles)
    {
        return $user->roles()->whereIn('name', $roles)->exists();
    }
}
