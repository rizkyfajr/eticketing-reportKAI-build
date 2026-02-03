<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MaintenanceOrderNotificationService;
use App\Models\MaintenanceOrderNotification;
use App\Models\WorkingReport;
use App\Models\User;
use Inertia\Inertia;

class MaintenanceOrderNotificationController extends Controller
{
    protected $notificationService;

    public function __construct(MaintenanceOrderNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Tampilkan daftar notifikasi untuk user yang sedang login
     */
    public function index(Request $request)
    {
        $user = auth()->user(); 
        $userId = auth()->id();
        $limit = $request->input('limit', 20);

        $kupt = User::where('division_id', $user->division_id)
            ->where('position_id', 1)
            ->first();

        $workingReportQuery = WorkingReport::forCurrentUserRegion()
            ->with(['machine', 'createdBy'])
            ->whereNull('kupt_at1') // Pastikan KUPT belum approve
            ->whereIn('status', ['work_done', 'warming_up_done'])
            ->where(function ($query) {
                $query->where(function ($q) {
                    // Skenario 1: Ada Operator 3 dan sudah approve
                    $q->whereNotNull('operator_by3')
                    ->whereNotNull('operator_at3');
                })->orWhere(function ($q) {
                    // Skenario 2: Tidak ada Operator 3, tapi Operator 2 sudah approve
                    $q->whereNull('operator_by3')
                    ->whereNotNull('operator_at2');
                });
            });

        if ($kupt && $userId === $kupt->id) {
            $notificationWorkingReport = $workingReportQuery
                ->whereHas('createdBy', function ($q) use ($user) {
                    $q->where('division_id', $user->division_id);
                })
                ->get();
        } else {
            $notificationWorkingReport = collect();
        }

        $notifications = MaintenanceOrderNotification::where('user_id', $userId)
            ->with(['maintenanceOrder.machine'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        $unreadCount = $this->notificationService->getUnreadCount($userId);

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'notificationWorkingReport' => $notificationWorkingReport,
        ]);
    }

    /**
     * Ambil notifikasi terbaru (untuk dropdown/bell icon)
     */
    public function recent(Request $request)
    {
        $user   = auth()->user();
        $userId = auth()->id();
        $limit = $request->input('limit', 10);

        $notifications = $this->notificationService->getUserNotifications($userId, $limit);
        $unreadCount = $this->notificationService->getUnreadCount($userId);

        $kupt = User::where('division_id', $user->division_id)
            ->where('position_id', 1)
            ->first();

        $workingReports = collect();

        if ($kupt && $userId === $kupt->id) {
            $workingReports = WorkingReport::forCurrentUserRegion()
                ->whereNull('kupt_at1') 
                ->whereIn('status', ['work_done', 'warming_up_done']) 
                ->where(function ($query) {
                    $query->where(function ($q) {
                        // Skenario 1: Ada Operator 3 dan sudah approve
                        $q->whereNotNull('operator_by3')
                        ->whereNotNull('operator_at3');
                    })->orWhere(function ($q) {
                        // Skenario 2: Tidak ada Operator 3, cukup Operator 2 sudah approve
                        $q->whereNull('operator_by3')
                        ->whereNotNull('operator_at2');
                    });
                })
                ->whereHas('createdBy', function ($q) use ($user) {
                    $q->where('division_id', $user->division_id);
                })
                ->latest()
                ->take($limit)
                ->get()
                ->map(function ($wr) {
                    return [
                        'id' => $wr->id,
                        'title' => 'Working Report Menunggu Verifikasi',
                        'message' => 'WR #' . $wr->id . ' siap diverifikasi KUPT',
                        'created_at' => $wr->created_at,
                        'url' => route('working-reports.detail', $wr->id),
                    ];
                });
        }

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'workingReports' => $workingReports,
        ]);
    }

    /**
     * Mark satu notifikasi sebagai sudah dibaca
     */
    public function markAsRead(MaintenanceOrderNotification $notification)
    {
        // Pastikan notifikasi milik user yang login
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark semua notifikasi user sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $userId = auth()->id();
        $this->notificationService->markAllAsRead($userId);

        return response()->json(['success' => true]);
    }

    /**
     * Hapus notifikasi
     */
    public function destroy(MaintenanceOrderNotification $notification)
    {
        // Pastikan notifikasi milik user yang login
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
