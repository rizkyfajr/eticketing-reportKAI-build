<?php

namespace App\Services;

use App\Models\MaintenanceOrder;
use App\Models\MaintenanceOrderNotification;
use App\Models\User;

class MaintenanceOrderNotificationService
{
    /**
     * Kirim notifikasi ke KAOP/Teknisi BY berdasarkan flow
     *
     * Flow Notifikasi:
     * 1. Input Failure (Unplanned/Planned) -> Notifikasi ke KAOP/Teknisi BY
     * 2. Follow Up Plan -> Notifikasi ke Teknisi yang ditugaskan
     * 3. Start to Repair -> Notifikasi ke KAOP/Teknisi BY
     * 4. Repair Complete -> Notifikasi ke KAOP/Teknisi BY
     */

    /**
     * 1. Notifikasi saat Input Failure (Unplanned/Planned)
     * Dikirim ke: KAOP dan Teknisi BY
     */
    public function notifyInputFailure(MaintenanceOrder $order)
    {
        try {
            // Ambil user dengan posisi KAOP dan Teknisi BY
            $recipients = $this->getKaopAndTeknisiBy($order->machine->division_id ?? null);

            $type = $order->category === 'planned' ? 'Perawatan' : 'Gangguan';

            foreach ($recipients as $user) {
                MaintenanceOrderNotification::create([
                    'maintenance_order_id' => $order->id,
                    'user_id' => $user->id,
                    'type' => 'input_failure',
                    'title' => "[$type Baru] {$order->title}",
                    'message' => "Data {$type} baru pada mesin {$order->machine->name} - {$order->machine->nomor}. Segera lakukan follow up plan.",
                    'status' => 'BARU',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error notifyInputFailure: ' . $e->getMessage());
        }
    }

    /**
     * 2. Notifikasi saat Follow Up Plan dibuat
     * Dikirim ke: Teknisi yang ditugaskan di follow_up_by_id
     */
    public function notifyFollowUpPlan(MaintenanceOrder $order)
    {
        try {
            if (!$order->follow_up_by_id) {
                return;
            }

            MaintenanceOrderNotification::create([
                'maintenance_order_id' => $order->id,
                'user_id' => $order->follow_up_by_id,
                'type' => 'follow_up_plan',
                'title' => "[Tugas Baru] {$order->title}",
                'message' => "Anda ditugaskan untuk menangani pekerjaan ini. Rencana: {$order->follow_up_plan}. Estimasi selesai: " . ($order->follow_up_estimate_at ? date('d/m/Y H:i', strtotime($order->follow_up_estimate_at)) : '-'),
                'status' => 'DIPROSES',
            ]);

            // Notifikasi juga ke KAOP/Teknisi BY bahwa follow up sudah dibuat
            $supervisors = $this->getKaopAndTeknisiBy($order->machine->division_id ?? null);
            foreach ($supervisors as $user) {
                if ($user->id !== $order->follow_up_by_id) {
                    MaintenanceOrderNotification::create([
                        'maintenance_order_id' => $order->id,
                        'user_id' => $user->id,
                        'type' => 'follow_up_plan',
                        'title' => "[Follow Up] {$order->title}",
                        'message' => "Follow up plan telah dibuat oleh {$order->followUpBy->name}. Rencana: {$order->follow_up_plan}",
                        'status' => 'DIPROSES',
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error notifyFollowUpPlan: ' . $e->getMessage());
        }
    }

    /**
     * 3. Notifikasi saat Pekerjaan Mulai Dikerjakan
     * Dikirim ke: KAOP dan Teknisi BY
     */
    public function notifyStartRepair(MaintenanceOrder $order)
    {
        try {
            $recipients = $this->getKaopAndTeknisiBy($order->machine->division_id ?? null);

            $teknisi = $order->startRepairBy->name ?? 'Teknisi';

            foreach ($recipients as $user) {
                MaintenanceOrderNotification::create([
                    'maintenance_order_id' => $order->id,
                    'user_id' => $user->id,
                    'type' => 'start_repair',
                    'title' => "[Pekerjaan Dimulai] {$order->title}",
                    'message' => "Pekerjaan pada mesin {$order->machine->name} telah dimulai oleh {$teknisi}.",
                    'status' => 'DIKERJAKAN',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error notifyStartRepair: ' . $e->getMessage());
        }
    }

    /**
     * 4. Notifikasi saat Pekerjaan Selesai
     * Dikirim ke: KAOP dan Teknisi BY
     */
    public function notifyRepairComplete(MaintenanceOrder $order)
    {
        try {
            $recipients = $this->getKaopAndTeknisiBy($order->machine->division_id ?? null);

            $teknisi = $order->completeRepairBy->name ?? 'Teknisi';

            foreach ($recipients as $user) {
                MaintenanceOrderNotification::create([
                    'maintenance_order_id' => $order->id,
                    'user_id' => $user->id,
                    'type' => 'repair_complete',
                    'title' => "[Pekerjaan Selesai] {$order->title}",
                    'message' => "Pekerjaan pada mesin {$order->machine->name} telah diselesaikan oleh {$teknisi}. Catatan: {$order->complete_repair_notes}",
                    'status' => 'SELESAI',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error notifyRepairComplete: ' . $e->getMessage());
        }
    }

    /**
     * Ambil user dengan posisi KAOP atau Teknisi BY
     * Sesuaikan dengan data positions di database Anda
     */
    private function getKaopAndTeknisiBy($divisionId = null)
    {
        // Gunakan join karena relasi positions adalah hasOne
        // Posisi yang menerima notifikasi: Kepala UPT Mekanik dan Kepala Operator KPJR
        $query = User::join('positions', 'users.position_id', '=', 'positions.id')
            ->whereIn('positions.position', [
                'Kepala UPT Mekanik',
                'Kepala Operator KPJR'
            ])
            ->select('users.*');

        // Filter berdasarkan divisi jika ada
        if ($divisionId) {
            $query->where('users.division_id', $divisionId);
        }

        return $query->get();
    }

    /**
     * Ambil notifikasi untuk user tertentu
     */
    public function getUserNotifications($userId, $limit = 10)
    {
        return MaintenanceOrderNotification::where('user_id', $userId)
            ->with(['maintenanceOrder.machine'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Hitung jumlah notifikasi yang belum dibaca
     */
    public function getUnreadCount($userId)
    {
        return MaintenanceOrderNotification::where('user_id', $userId)
            ->unread()
            ->count();
    }

    /**
     * Mark semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead($userId)
    {
        MaintenanceOrderNotification::where('user_id', $userId)
            ->unread()
            ->update(['read_at' => now()]);
    }
}
