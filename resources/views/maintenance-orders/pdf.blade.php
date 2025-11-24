<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Maintenance Order #{{ $order->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        .container { padding: 20px; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #000; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; }
        .header p { font-size: 10px; color: #666; }

        .section { margin-bottom: 15px; }
        .section-title { background: #333; color: white; padding: 6px 10px; font-size: 12px; font-weight: bold; margin-bottom: 8px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table th, table td { padding: 6px; text-align: left; border: 1px solid #ddd; }
        table th { background: #f0f0f0; font-weight: bold; font-size: 10px; }
        table td { font-size: 10px; }

        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 30%; font-weight: bold; padding: 4px 0; }
        .info-value { display: table-cell; width: 70%; padding: 4px 0; }

        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .status-baru { background: #3b82f6; color: white; }
        .status-diproses { background: #06b6d4; color: white; }
        .status-dikerjakan { background: #eab308; color: white; }
        .status-selesai { background: #16a34a; color: white; }

        .category-header { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 8px; font-weight: bold; margin: 10px 0 5px 0; }
        .item-box { border: 1px solid #e5e7eb; padding: 8px; margin-bottom: 8px; background: #f9fafb; }
        .item-title { font-weight: bold; margin-bottom: 5px; }

        .result-ok { color: #16a34a; font-weight: bold; }
        .result-ng { color: #dc2626; font-weight: bold; }

        .footer { margin-top: 30px; border-top: 2px solid #ccc; padding-top: 10px; font-size: 9px; text-align: center; color: #666; }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>LAPORAN MAINTENANCE ORDER</h1>
            <p>PT. KERETA API INDONESIA (Persero)</p>
            <p>Nomor: #{{ $order->id }} | Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
        </div>

        <!-- INFORMASI ORDER -->
        <div class="section">
            <div class="section-title">INFORMASI ORDER</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Status Pekerjaan:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ strtolower($order->status) }}">{{ $order->status }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Judul:</div>
                    <div class="info-value">{{ $order->title }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kategori:</div>
                    <div class="info-value">
                        <strong>{{ $order->category === 'planned' ? 'PLANNED (Perawatan)' : 'UNPLANNED (Gangguan)' }}</strong>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Mesin:</div>
                    <div class="info-value">{{ $order->machine->name }} - {{ $order->machine->nomor }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Region:</div>
                    <div class="info-value">{{ $order->machine->region->name ?? '-' }}</div>
                </div>

                @if($order->category === 'planned')
                <div class="info-row">
                    <div class="info-label">Pedoman Checklist:</div>
                    <div class="info-value">{{ $order->masterPedoman->kode_pedoman ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Rencana Mulai:</div>
                    <div class="info-value">{{ $order->plan_start_at ? \Carbon\Carbon::parse($order->plan_start_at)->format('d/m/Y H:i') : '-' }}</div>
                </div>
                @else
                <div class="info-row">
                    <div class="info-label">Waktu Gangguan:</div>
                    <div class="info-value">{{ $order->trouble_at ? \Carbon\Carbon::parse($order->trouble_at)->format('d/m/Y H:i') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Lokasi:</div>
                    <div class="info-value">{{ $order->location }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Catatan Gangguan:</div>
                    <div class="info-value">{{ $order->problem_note }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- TIMELINE PEKERJAAN -->
        @if($order->status !== 'BARU')
        <div class="section">
            <div class="section-title">TIMELINE PEKERJAAN</div>
            <table>
                <thead>
                    <tr>
                        <th>Tahapan</th>
                        <th>Waktu</th>
                        <th>Penanggung Jawab</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @if($order->follow_up_at)
                    <tr>
                        <td><strong>Follow Up Plan</strong></td>
                        <td>{{ \Carbon\Carbon::parse($order->follow_up_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->followUpBy->name ?? '-' }}</td>
                        <td>{{ $order->follow_up_plan }}</td>
                    </tr>
                    @endif

                    @if($order->start_repair_at)
                    <tr>
                        <td><strong>Mulai Perbaikan</strong></td>
                        <td>{{ \Carbon\Carbon::parse($order->start_repair_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->startRepairBy->name ?? '-' }}</td>
                        <td>{{ $order->start_repair_notes }}</td>
                    </tr>
                    @endif

                    @if($order->complete_repair_at)
                    <tr>
                        <td><strong>Selesai Perbaikan</strong></td>
                        <td>{{ \Carbon\Carbon::parse($order->complete_repair_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->completeRepairBy->name ?? '-' }}</td>
                        <td>{{ $order->complete_repair_notes }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @endif

        <!-- HASIL CHECKLIST -->
        @if($order->masterPedoman && $order->masterPedoman->categories && count($order->masterPedoman->categories) > 0)
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">HASIL CHECKLIST: {{ $order->masterPedoman->nama_pedoman }}</div>

            @foreach($order->masterPedoman->categories as $category)
            <div class="category-header">{{ $category->name }}</div>

            @foreach($category->items as $item)
            <div class="item-box">
                @if($item->tipe_input === 'image')
                    <div class="item-title">{{ $item->deskripsi }}</div>
                    @if($item->gambar_referensi_path)
                    <img src="{{ public_path('storage/' . $item->gambar_referensi_path) }}" style="max-width: 100%; height: auto; margin-top: 5px;">
                    @endif

                @elseif($item->tipe_input === 'table')
                    <div class="item-title">{{ $item->nomor_poin }} - {{ $item->deskripsi }}</div>
                    <table style="margin-top: 5px;">
                        <thead>
                            <tr>
                                <th>Posisi</th>
                                @foreach($item->extra_config['columns'] ?? [] as $col)
                                <th>{{ $col['name'] }}<br><small>Std: {{ $col['std'] }}</small></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->extra_config['rows_label'] ?? [] as $rIdx => $rowLabel)
                            <tr>
                                <td><strong>{{ $rowLabel }}</strong></td>
                                @foreach($item->extra_config['columns'] ?? [] as $cIdx => $col)
                                <td>{{ $checklistData[$item->id]['realisasi'][$rowLabel . '_' . $cIdx] ?? '-' }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="margin-top: 5px; font-size: 10px;">
                        <strong>Status:</strong>
                        <span class="{{ ($checklistData[$item->id]['status'] ?? 'OK') === 'OK' ? 'result-ok' : 'result-ng' }}">
                            {{ $checklistData[$item->id]['status'] ?? 'OK' }}
                        </span>
                        @if(!empty($checklistData[$item->id]['catatan']))
                        | <strong>Catatan:</strong> {{ $checklistData[$item->id]['catatan'] }}
                        @endif
                    </div>

                @else
                    <div class="item-title">{{ $item->nomor_poin }} - {{ $item->deskripsi }}</div>
                    <div style="font-size: 10px; color: #666; margin-bottom: 3px;">
                        Standard: {{ $item->standar_nilai ?? '-' }} {{ $item->satuan }}
                    </div>
                    <table style="margin-top: 5px;">
                        <tr>
                            <th style="width: 30%;">Realisasi</th>
                            <th style="width: 20%;">Status</th>
                            <th style="width: 50%;">Catatan</th>
                        </tr>
                        <tr>
                            <td>{{ $checklistData[$item->id]['realisasi'] ?? '-' }}</td>
                            <td>
                                <span class="{{ ($checklistData[$item->id]['status'] ?? 'OK') === 'OK' ? 'result-ok' : 'result-ng' }}">
                                    {{ $checklistData[$item->id]['status'] ?? 'OK' }}
                                </span>
                            </td>
                            <td>{{ $checklistData[$item->id]['catatan'] ?? '-' }}</td>
                        </tr>
                    </table>
                @endif
            </div>
            @endforeach
            @endforeach
        </div>
        @endif

        <!-- FOOTER -->
        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis dari sistem RAMCES (Railway Maintenance Centralized System)</p>
            <p>Dicetak pada: {{ date('d F Y, H:i:s') }} WIB</p>
        </div>
    </div>
</body>
</html>
