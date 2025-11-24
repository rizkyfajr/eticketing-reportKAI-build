<?php

namespace App\Http\Controllers;

use App\Models\MasterPedoman;
use App\Models\MasterPedomanCategory;
use App\Models\MasterPedomanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MasterPedomanController extends Controller
{
    public function index()
    {
        $pedomans = MasterPedoman::latest()->paginate(10);
        return Inertia::render('MasterPedoman/Index', ['pedomans' => $pedomans]);
    }

    public function create()
    {
        return Inertia::render('MasterPedoman/Form');
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }

    public function edit($id)
    {
        // Load relasi lengkap untuk diedit
        $pedoman = MasterPedoman::with('categories.items')->findOrFail($id);
        return Inertia::render('MasterPedoman/Form', ['pedoman' => $pedoman]);
    }

    public function update(Request $request, $id)
    {
        return $this->saveData($request, $id);
    }

    public function destroy($id)
    {
        $pedoman = MasterPedoman::findOrFail($id);
        $pedoman->delete(); // Cascade delete akan menghapus kategori & item otomatis
        return redirect()->route('master-pedoman.index')->with('success', 'Pedoman berhasil dihapus.');
    }

    // --- FUNGSI UTAMA PENYIMPANAN (DIGUNAKAN STORE & UPDATE) ---
    private function saveData(Request $request, $id = null)
    {
        // Validasi dasar
        $request->validate([
            'kode_pedoman' => 'required|string|max:50',
            'nama_pedoman' => 'required|string|max:255',
            'categories'   => 'array|nullable', // Array kategori
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan/Update Induk
            if ($id) {
                $pedoman = MasterPedoman::findOrFail($id);
                $pedoman->update([
                    'kode_pedoman' => $request->kode_pedoman,
                    'nama_pedoman' => $request->nama_pedoman,
                ]);

                // Hapus data lama (Cara brutal tapi aman untuk struktur kompleks: Hapus anak, buat ulang)
                // Note: Jika butuh performa tinggi atau mempertahankan ID lama, logikanya harus lebih rumit (sync).
                // Untuk admin panel internal, cara delete-recreate ini biasanya oke.
                $pedoman->categories()->delete();
            } else {
                $pedoman = MasterPedoman::create([
                    'kode_pedoman' => $request->kode_pedoman,
                    'nama_pedoman' => $request->nama_pedoman,
                ]);
            }

            // 2. Loop Categories
            if ($request->categories) {
                foreach ($request->categories as $catIndex => $catData) {
                    $category = $pedoman->categories()->create([
                        'name'  => $catData['name'],
                        'order' => $catIndex + 1,
                    ]);

                    // 3. Loop Items di dalam Category
                    if (isset($catData['items'])) {
                        foreach ($catData['items'] as $itemIndex => $itemData) {

                            $imagePath = null;
                            // Cek apakah ada file gambar baru di-upload
                            if (isset($itemData['gambar_file']) && $itemData['gambar_file'] instanceof \Illuminate\Http\UploadedFile) {
                                $imagePath = $itemData['gambar_file']->store('pedoman_images', 'public');
                            }
                            // Jika tidak ada file baru, tapi ada path lama (saat edit), pakai yg lama
                            elseif (isset($itemData['gambar_referensi_path'])) {
                                $imagePath = $itemData['gambar_referensi_path'];
                            }

                            // Handle JSON Config untuk Table
                            $extraConfig = null;
                            if ($itemData['tipe_input'] === 'table' && isset($itemData['extra_config'])) {
                                // Jika dikirim sebagai string JSON (dari frontend), decode dulu, atau simpan langsung array
                                $extraConfig = is_string($itemData['extra_config']) ? json_decode($itemData['extra_config']) : $itemData['extra_config'];
                            }

                            $category->items()->create([
                                'nomor_poin' => $itemData['nomor_poin'] ?? null,
                                'deskripsi'  => $itemData['deskripsi'],
                                'tipe_input' => $itemData['tipe_input'],
                                'standar_nilai' => $itemData['standar_nilai'] ?? null,
                                'satuan'        => $itemData['satuan'] ?? null,
                                'gambar_referensi_path' => $imagePath,
                                'extra_config'  => $extraConfig,
                                'urutan'        => $itemIndex + 1,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('master-pedoman.index')->with('success', 'Data Pedoman berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
