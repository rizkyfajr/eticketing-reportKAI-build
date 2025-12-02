<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceOrderNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_order_id',
        'user_id',
        'type',
        'title',
        'message',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Relasi ke MaintenanceOrder
     */
    public function maintenanceOrder()
    {
        return $this->belongsTo(MaintenanceOrder::class);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Mark notifikasi sebagai sudah dibaca
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }
}
