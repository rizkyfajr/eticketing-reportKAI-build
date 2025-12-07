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
use App\Models\MaintenanceOrder;
use App\Models\MasterMachine;
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

    // --- FILTER STATUS WORKING ORDER BERDASARKAN USER DI KEDUDUKANNYA ---
    $userDivisionId = $user->division_id;
    $allowedRegionId = null; 

    if (in_array($userDivisionId, [1, 3])) {
        $allowedRegionId = 1;
    } elseif ($userDivisionId === 4) {
        $allowedRegionId = 2;
    } elseif ($userDivisionId === 5) {
        $allowedRegionId = 3;
    }

    $reports = WorkingReport::with([
        'machine:id,name,nomor,type',
        'warmingup',
        'workresult',
    ])->get();

    if ($allowedRegionId !== null) {
        $reports = $reports->filter(function ($report) use ($allowedRegionId) {
            return $report->region_id === $allowedRegionId;
        })->values(); 
    }

    $statusCounts = $reports->countBy('status');

    $dashboardStats = [
        'draft' => $statusCounts['draft'] ?? 0,
        'checksheet_done' => $statusCounts['checksheet_done'] ?? 0,
        'warming_up_done' => $statusCounts['warming_up_done'] ?? 0,
        'work_done' => $statusCounts['work_done'] ?? 0,
        'finished' => $statusCounts['finished'] ?? 0,
    ];

    // $allMachines = MasterMachine::select('id', 'name', 'nomor', 'type')->get();

    // $machineNames = $allMachines->map(function ($machine) {
    //     return ' [' . $machine->nomor .'] ' . $machine->name . ' - ' . $machine->type . '';
    // })->unique()->toArray();

    // --- FILTER DATA MESIN BERDASARKAN KEDUDUKAN USER YANG LOGIN ---
    $userDivisionId = $user->division_id;

    $machineQuery = MasterMachine::select('id', 'name', 'nomor', 'type', 'region_id');

    if (in_array($userDivisionId, [1, 3])) {
        $machineQuery->where('region_id', 1); 
    } 
    elseif ($userDivisionId === 4) {
        $machineQuery->where('region_id', 2);
    } 
    elseif ($userDivisionId === 5) {
        $machineQuery->where('region_id', 3);
    }

    $allMachines = $machineQuery->get();

    $machineNames = $allMachines->map(function ($machine) {
        return ' [' . $machine->nomor .'] ' . $machine->name . ' - ' . $machine->type . '';
    })->unique()->toArray();

    $totalPerMesin = array_fill_keys($machineNames, 0);
    $totalJamGenerator = array_fill_keys($machineNames, 0);
    $totalCounterTamping = array_fill_keys($machineNames, 0);
    $totalOdometer = array_fill_keys($machineNames, 0);
    $totalHSD = array_fill_keys($machineNames, 0);
        
        foreach ($reports as $wr) {

            $machine = $wr->machine;

            if (!$machine) {
                continue; 
            }
            $machineName = ' [' . $machine->nomor .'] ' . $machine->name . ' - ' . $machine->type . '';

            if (!isset($totalPerMesin[$machineName])) {
                continue; 
                // $totalPerMesin[$machineName] = 0;
                // $totalJamGenerator[$machineName] = 0;
                // $totalCounterTamping[$machineName] = 0;
                // $totalOdometer[$machineName] = 0;
                // $totalHSD[$machineName] = 0;
                // $machineNames[] = $machineName; 
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

            if ($hsdStart !== null && is_numeric($hsdStart)) {
                $totalHSD[$machineName] += $hsdStart;
            }

            if ($hsdStop !== null && is_numeric($hsdStop)) {
                $totalHSD[$machineName] += $hsdStop;
            }
        }
        
        $formatted = [];
        $formattedGenerator = [];
        $formattedTamping = [];
        $formattedOdometer = [];
        $formattedHSD = [];

        foreach ($machineNames as $mesin) {
            
            $menit = $totalPerMesin[$mesin] ?? 0;
            if ($menit > 0) {
                $formatted[$mesin] = floor($menit / 60) . " Jam " . ($menit % 60) . " Menit";
            } else {
                $formatted[$mesin] = null; 
            }

            $jamGen = $totalJamGenerator[$mesin] ?? 0;
            if ($jamGen > 0) {
                $formattedGenerator[$mesin] = $jamGen . " Jam";
            } else {
                $formattedGenerator[$mesin] = null;
            }

            $counter = $totalCounterTamping[$mesin] ?? 0;
            if ($counter > 0) {
                $formattedTamping[$mesin] = number_format($counter, 0, ',', '.') . " Counter ";
            } else {
                $formattedTamping[$mesin] = null;
            }

            $odo = $totalOdometer[$mesin] ?? 0;
            if ($odo > 0) {
                $formattedOdometer[$mesin] = number_format($odo, 0, ',', '.') . " Km ";
            } else {
                $formattedOdometer[$mesin] = null;
            }

            $hsd = $totalHSD[$mesin] ?? 0;
            if ($hsd > 0) {
                $formattedHSD[$mesin] = number_format($hsd, 2, ',', '.') . " % ";
            } else {
                $formattedHSD[$mesin] = null;
            }
        }

    // dd($formatted, $formattedGenerator, $formattedTamping, $formattedOdometer, $formattedHSD);

    // Dashboard Maintenance Order
    $maintenanceStats = [
        'total_failures' => MaintenanceOrder::count(),
        'pending_followup' => MaintenanceOrder::where(function($q) {
            $q->whereIn('status', ['OPEN', 'DIPROSES'])
              ->orWhereNull('status');
        })->count(),
        'in_progress' => MaintenanceOrder::where('status', 'DIKERJAKAN')->count(),
        'completed' => MaintenanceOrder::where('status', 'SELESAI')->count(),
        'critical_failures' => MaintenanceOrder::where('severity', 'critical')->count(),
    ];

    // Hitung rata-rata waktu perbaikan (MTTR - Mean Time To Repair)
    // MTTR = Waktu dari mulai repair sampai selesai repair (bukan dari trouble_at)
    $completedOrders = MaintenanceOrder::where('status', 'SELESAI')
        ->whereNotNull('start_repair_at')
        ->whereNotNull('complete_repair_at')
        ->get();

    $totalRepairMinutes = 0;
    $repairCount = 0;

    foreach ($completedOrders as $order) {
        try {
            $startDate = Carbon::parse($order->start_repair_at);
            $completeDate = Carbon::parse($order->complete_repair_at);

            // Pastikan complete_repair_at lebih besar dari start_repair_at
            if ($completeDate->greaterThan($startDate)) {
                $totalRepairMinutes += $startDate->diffInMinutes($completeDate);
                $repairCount++;
            }
        } catch (\Exception $e) {
            // Skip jika parsing gagal
        }
    }

    $avgRepairTime = $repairCount > 0 ? floor($totalRepairMinutes / $repairCount) : 0;
    $maintenanceStats['avg_repair_hours'] = floor($avgRepairTime / 60);
    $maintenanceStats['avg_repair_minutes'] = $avgRepairTime % 60;

    // Hitung rata-rata Response Time (dari kerusakan sampai mulai perbaikan)
    $respondedOrders = MaintenanceOrder::where('status', 'SELESAI')
        ->whereNotNull('trouble_at')
        ->whereNotNull('start_repair_at')
        ->get();

    $totalResponseMinutes = 0;
    $responseCount = 0;

    foreach ($respondedOrders as $order) {
        try {
            $troubleDate = Carbon::parse($order->trouble_at);
            $startDate = Carbon::parse($order->start_repair_at);

            if ($startDate->greaterThan($troubleDate)) {
                $totalResponseMinutes += $troubleDate->diffInMinutes($startDate);
                $responseCount++;
            }
        } catch (\Exception $e) {
            // Skip
        }
    }

    $avgResponseTime = $responseCount > 0 ? floor($totalResponseMinutes / $responseCount) : 0;
    $maintenanceStats['avg_response_hours'] = floor($avgResponseTime / 60);
    $maintenanceStats['avg_response_minutes'] = $avgResponseTime % 60;

    // Recent Maintenance Orders (5 terbaru)
    $recentMaintenanceOrders = MaintenanceOrder::with(['machine:id,name,nomor', 'user:id,name'])
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($order) {
            return [
                'id' => $order->id,
                'machine_name' => $order->machine ? '[' . $order->machine->nomor . '] ' . $order->machine->name : 'N/A',
                'failure_description' => \Str::limit($order->problem_note ?? $order->title ?? '-', 50),
                'status' => $order->status,
                'severity' => $order->severity,
                'created_by' => $order->user->name ?? 'Unknown',
                'created_at' => $order->created_at->format('d M Y H:i'),
            ];
        });

    return Inertia::render('Dashboard', [
      'report' => $dashboardStats,
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
      'maintenanceStats' => $maintenanceStats,
      'recentMaintenanceOrders' => $recentMaintenanceOrders,
      'isAdminOrSupervisor' => $user->hasAnyRole(['admin', 'superuser', 'Kepala UPT Mekanik']),
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
