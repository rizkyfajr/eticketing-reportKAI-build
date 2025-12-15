<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait RegionalScope
 *
 * Trait ini digunakan untuk membatasi akses data berdasarkan wilayah (region)
 * - Super Admin: Melihat semua data (region_id = NULL)
 * - Admin Wilayah: Hanya melihat data di region yang ditugaskan
 * - User Biasa: Melihat semua data (tidak ada pembatasan)
 */
trait RegionalScope
{
    /**
     * Scope query untuk filter data berdasarkan region user yang login
     *
     * Usage: Model::forCurrentUserRegion()->get()
     */
    public function scopeForCurrentUserRegion(Builder $query)
    {
        $user = auth()->user();

        // Jika user tidak login, return query kosong untuk keamanan
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        // Super Admin atau user tanpa region_id: lihat semua data
        if (!$user->region_id) {
            return $query;
        }

        // Admin Wilayah: hanya lihat data di regionnya
        // Cek apakah user punya role admin-wilayah
        if ($user->hasRole('admin-wilayah')) {
            return $query->where('region_id', $user->region_id);
        }

        // User biasa: lihat semua (tidak ada pembatasan)
        return $query;
    }

    /**
     * Scope untuk memeriksa apakah user boleh mengakses data ini
     *
     * Usage:
     * if (!$machine->isAccessibleByUser()) {
     *     abort(403, 'Anda tidak memiliki akses ke data ini');
     * }
     */
    public function isAccessibleByUser($user = null)
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        // Super Admin atau user tanpa region_id: boleh akses semua
        if (!$user->region_id) {
            return true;
        }

        // Admin Wilayah: cek apakah data milik regionnya
        if ($user->hasRole('admin-wilayah')) {
            return $this->region_id === $user->region_id;
        }

        // User biasa: boleh akses semua
        return true;
    }

    /**
     * Helper method untuk mendapatkan region_id yang seharusnya digunakan
     * saat membuat data baru
     *
     * Usage: $machine->region_id = $machine->getRegionIdForCreate();
     */
    public static function getRegionIdForCreate()
    {
        $user = auth()->user();

        // Jika user adalah Admin Wilayah, gunakan region_id mereka
        if ($user && $user->region_id && $user->hasRole('admin-wilayah')) {
            return $user->region_id;
        }

        // Super Admin atau user lain: bisa pilih region apapun
        return null;
    }

    /**
     * Helper untuk cek apakah field region_id harus di-lock (disabled)
     *
     * Usage di Vue: :disabled="isRegionFieldLocked"
     */
    public static function isRegionFieldLocked()
    {
        $user = auth()->user();

        return $user && $user->region_id && $user->hasRole('admin-wilayah');
    }
}
