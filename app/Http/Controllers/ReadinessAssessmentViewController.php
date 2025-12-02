<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ReadinessAssessment;
use App\Models\ReadinessAssessmentMaster;
use App\Models\User;
use App\Models\HealthCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReadinessAssessmentViewController extends Controller
{
  /**
   * Halaman History Assessment untuk User sendiri
   */
  public function myHistory(Request $request)
  {
    $userId = Auth::id();
    $dateFilter = $request->input('date', Carbon::today()->format('Y-m-d'));

    try {
      // Ambil data assessment untuk tanggal tertentu
      $assessments = ReadinessAssessment::with('masterQuestion')
        ->where('user_id', $userId)
        ->where('assessment_date', $dateFilter)
        ->get();

      // Ambil semua pertanyaan master untuk grouping
      $masterQuestions = ReadinessAssessmentMaster::orderBy('urutan')
        ->orderBy('nomor')
        ->get();

      // Group pertanyaan berdasarkan group_name
      $groupedData = [];
      if ($masterQuestions->count() > 0) {
        $grouped = $masterQuestions->groupBy('group_name');
        foreach ($grouped as $groupName => $questions) {
          $groupedData[$groupName] = $questions->map(function ($question) use ($assessments) {
            $answer = $assessments->firstWhere('assessment_master_id', $question->id);
            return [
              'id' => $question->id,
              'urutan' => $question->urutan,
              'komponen' => $question->komponen,
              'pertanyaan' => $question->pertanyaan,
              'jawaban' => $answer ? ($answer->ya ? 'Ya' : ($answer->tidak ? 'Tidak' : '-')) : 'Belum Diisi',
              'note' => $answer?->note,
            ];
          })->values()->toArray();
        }
      }

      // Ambil tanggal-tanggal yang pernah diisi
      $filledDates = ReadinessAssessment::where('user_id', $userId)
        ->select('assessment_date')
        ->distinct()
        ->orderBy('assessment_date', 'desc')
        ->limit(30)
        ->pluck('assessment_date')
        ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
        ->toArray();

      // Cek sertifikat kesehatan
      $healthCert = HealthCertificate::where('user_id', $userId)
        ->where('status', 'active')
        ->where('valid_from', '<=', Carbon::today())
        ->where('valid_until', '>=', Carbon::today())
        ->first();

      return Inertia::render('ReadinessAssessment/MyHistory', [
        'groupedData' => $groupedData,
        'selectedDate' => $dateFilter,
        'filledDates' => $filledDates,
        'hasAssessment' => $assessments->isNotEmpty(),
        'healthCertificate' => $healthCert ? [
          'valid_from' => $healthCert->valid_from->format('d/m/Y'),
          'valid_until' => $healthCert->valid_until->format('d/m/Y'),
          'days_remaining' => Carbon::today()->diffInDays($healthCert->valid_until, false) + 1,
          'file_url' => asset('storage/' . $healthCert->file_path),
        ] : null,
      ]);
    } catch (\Exception $e) {
      \Log::error('Error in myHistory: ' . $e->getMessage());
      \Log::error($e->getTraceAsString());

      return Inertia::render('ReadinessAssessment/MyHistory', [
        'groupedData' => [],
        'selectedDate' => $dateFilter,
        'filledDates' => [],
        'hasAssessment' => false,
        'healthCertificate' => null,
        'error' => $e->getMessage()
      ]);
    }
  }

  /**
   * Halaman Monitoring Assessment untuk Admin/Atasan
   */
  public function adminMonitoring(Request $request)
  {
    $dateFilter = $request->input('date', Carbon::today()->format('Y-m-d'));
    $userFilter = $request->input('user_id');

    try {
      // Query base
      $query = ReadinessAssessment::with(['user:id,name,username', 'masterQuestion'])
        ->where('assessment_date', $dateFilter);

      if ($userFilter) {
        $query->where('user_id', $userFilter);
      }

      $assessments = $query->get();

      // Group by user
      $userAssessments = [];
      if ($assessments->count() > 0) {
        $grouped = $assessments->groupBy('user_id');
        $totalQuestions = ReadinessAssessmentMaster::count();

        foreach ($grouped as $userId => $userAnswers) {
          $user = $userAnswers->first()->user;
          $answeredQuestions = $userAnswers->count();
          $yesCount = $userAnswers->where('ya', 1)->count();
          $noCount = $userAnswers->where('tidak', 1)->count();

          $userAssessments[] = [
            'user_id' => $userId,
            'user_name' => $user->name,
            'username' => $user->username,
            'total_questions' => $totalQuestions,
            'answered' => $answeredQuestions,
            'completion_percentage' => $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100, 2) : 0,
            'yes_count' => $yesCount,
            'no_count' => $noCount,
            'status' => $answeredQuestions === $totalQuestions ? 'complete' : 'incomplete',
          ];
        }
      }

      // Ambil daftar user untuk filter
      $users = User::select('id', 'name', 'username')
        ->whereHas('roles', function($q) {
          $q->whereNotIn('name', ['admin', 'superuser', 'Kepala UPT Mekanik']);
        })
        ->orderBy('name')
        ->get()
        ->toArray();

      $userAssessmentsCollection = collect($userAssessments);

      // Statistik summary
      $summary = [
        'total_users' => count($users),
        'users_completed' => $userAssessmentsCollection->where('status', 'complete')->count(),
        'users_incomplete' => $userAssessmentsCollection->where('status', 'incomplete')->count(),
        'users_not_started' => count($users) - count($userAssessments),
        'completion_rate' => count($users) > 0
          ? round(($userAssessmentsCollection->where('status', 'complete')->count() / count($users)) * 100, 2)
          : 0,
      ];

      return Inertia::render('ReadinessAssessment/AdminMonitoring', [
        'userAssessments' => $userAssessments,
        'users' => $users,
        'selectedDate' => $dateFilter,
        'selectedUserId' => $userFilter,
        'summary' => $summary,
      ]);
    } catch (\Exception $e) {
      \Log::error('Error in adminMonitoring: ' . $e->getMessage());
      \Log::error($e->getTraceAsString());

      return Inertia::render('ReadinessAssessment/AdminMonitoring', [
        'userAssessments' => [],
        'users' => [],
        'selectedDate' => $dateFilter,
        'selectedUserId' => $userFilter,
        'summary' => [
          'total_users' => 0,
          'users_completed' => 0,
          'users_incomplete' => 0,
          'users_not_started' => 0,
          'completion_rate' => 0,
        ],
        'error' => $e->getMessage()
      ]);
    }
  }

  /**
   * Detail Assessment User untuk Admin
   */
  public function userDetail(Request $request, $userId)
  {
    $dateFilter = $request->input('date', Carbon::today()->format('Y-m-d'));

    try {
      $user = User::findOrFail($userId);

      // Ambil assessment user untuk tanggal tertentu
      $assessments = ReadinessAssessment::with('masterQuestion')
        ->where('user_id', $userId)
        ->where('assessment_date', $dateFilter)
        ->get();

      // Ambil semua pertanyaan master
      $masterQuestions = ReadinessAssessmentMaster::orderBy('urutan')
        ->orderBy('nomor')
        ->get();

      // Group pertanyaan - manual conversion
      $groupedData = [];
      if ($masterQuestions->count() > 0) {
        $grouped = $masterQuestions->groupBy('group_name');
        foreach ($grouped as $groupName => $questions) {
          $groupedData[$groupName] = $questions->map(function ($question) use ($assessments) {
            $answer = $assessments->firstWhere('assessment_master_id', $question->id);
            return [
              'id' => $question->id,
              'urutan' => $question->urutan,
              'komponen' => $question->komponen,
              'pertanyaan' => $question->pertanyaan,
              'jawaban' => $answer ? ($answer->ya ? 'Ya' : ($answer->tidak ? 'Tidak' : '-')) : 'Belum Diisi',
              'note' => $answer?->note,
            ];
          })->values()->toArray();
        }
      }

      // Cek sertifikat kesehatan user
      $healthCert = HealthCertificate::where('user_id', $userId)
        ->where('status', 'active')
        ->where('valid_until', '>=', Carbon::today())
        ->where('valid_from', '<=', Carbon::today())
        ->first();

      // History tanggal pengisian
      $filledDates = ReadinessAssessment::where('user_id', $userId)
        ->select('assessment_date')
        ->distinct()
        ->orderBy('assessment_date', 'desc')
        ->limit(30)
        ->pluck('assessment_date')
        ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
        ->toArray();

      return Inertia::render('ReadinessAssessment/UserDetail', [
        'user' => [
          'id' => $user->id,
          'name' => $user->name,
          'username' => $user->username,
        ],
        'groupedData' => $groupedData,
        'selectedDate' => $dateFilter,
        'filledDates' => $filledDates,
        'hasAssessment' => $assessments->isNotEmpty(),
        'healthCertificate' => $healthCert ? [
          'valid_from' => $healthCert->valid_from->format('d/m/Y'),
          'valid_until' => $healthCert->valid_until->format('d/m/Y'),
          'days_remaining' => Carbon::today()->diffInDays($healthCert->valid_until, false) + 1,
          'file_url' => asset('storage/' . $healthCert->file_path),
        ] : null,
      ]);
    } catch (\Exception $e) {
      \Log::error('Error in userDetail: ' . $e->getMessage());
      \Log::error($e->getTraceAsString());

      return Inertia::render('ReadinessAssessment/UserDetail', [
        'user' => [
          'id' => $userId,
          'name' => '-',
          'username' => '-',
        ],
        'groupedData' => [],
        'selectedDate' => $dateFilter,
        'filledDates' => [],
        'hasAssessment' => false,
        'healthCertificate' => null,
        'error' => $e->getMessage()
      ]);
    }
  }
}
