<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MaintenanceOrderNotificationService;
use App\Models\MaintenanceOrderNotification;
use App\Models\WorkingReport;
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
        $userId = auth()->id();
        $limit = $request->input('limit', 20);

        $notificationWorkingReport = WorkingReport::forCurrentUserRegion()
            ->whereNotNull('operator_at3')
            ->whereNull('kupt_at1')
            ->get();

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
        $userId = auth()->id();
        $limit = $request->input('limit', 10);

        $notifications = $this->notificationService->getUserNotifications($userId, $limit);
        $unreadCount = $this->notificationService->getUnreadCount($userId);

        $workingReports = WorkingReport::forCurrentUserRegion()
        ->whereNotNull('operator_at3')
        ->whereNull('kupt_at1')
        ->latest()
        ->take($limit)
        ->get()
        ->map(function ($wr) {
            return [
                'id' => $wr->id,
                'title' => 'Working Report Menunggu Verifikasi',
                'message' => 'WR #' . $wr->id . ' siap diverifikasi KUPT',
                'created_at' => $wr->created_at,
                'url' => route('working-reports.show', $wr->id),
            ];
        });

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
