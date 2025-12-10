<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Working Report - {{ $report->id }}</title>
    <style>
        .page-break {
            page-break-before: always;
        }
        .table-bottom {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        }
        .check {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; font-size: 8pt; margin: 5mm; padding: 0;">
<!-- <div style="border: 1px solid black; padding: 5mm; width: auto; box-sizing: border-box;"> -->
    <div style="width: 100%; font-family: Arial, sans-serif; font-size: 10pt;">
    
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="
                    width: 75%; 
                    text-align: center; 
                    vertical-align: middle;
                    border: 1px solid black; 
                    padding: 5px 0;
                ">
                    <p style="margin: 0; font-size: 11pt; font-weight: bold;">LAPORAN PEMERIKSAAN HARIAN KPJR</p>
                    <p style="margin: 2px 0; font-size: 11pt; font-weight: bold;">{{ strtoupper(optional($report->region)->name ?? '-') }}</p>
                </td>

                <td style="
                    width: 25%; 
                    text-align: center; 
                    vertical-align: middle;
                    border: 1px solid black;
                ">
                    <img 
                        src="{{ public_path('assets/img/logo-kai-baru.png') }}" 
                        alt="Logo KAI"
                        style="height: 50px; width: 100px; padding: 0;"
                    >
                </td>
            </tr>
        </table>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px; margin-top: 5px;">
        <tr>
            <td style="width: 20%; font-weight: bold; padding: 1px 3px 1px 0;">Klasifikasi</td>
            <td style="width: 1%; text-align: center; font-weight: bold;">:</td>
            <td style="width: 33%; border-bottom: 1px solid black; padding: 1px 3px;">{{ $report->klasifikasi ?? '-' }}</td>

            <td style="width: 20%; font-weight: bold; padding: 1px 3px 1px 0;">Type</td>
            <td style="width: 1%; text-align: center; font-weight: bold;">:</td>
            <td style="width: 33%; border-bottom: 1px solid black; padding: 1px 3px;">{{ $report->type ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; padding: 1px 3px 1px 0;">Merk</td>
            <td style="text-align: center; font-weight: bold;">:</td>
            <td style="border-bottom: 1px solid black; padding: 1px 3px;">{{ $report->jenis_kpjr ?? '-' }}</td>

            <td style="font-weight: bold; padding: 1px 3px 1px 0;">Nomor Sarana</td>
            <td style="text-align: center; font-weight: bold;">:</td>
            <td style="border-bottom: 1px solid black; padding: 1px 3px;">{{ $report->nomor_sarana ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; padding: 1px 3px 1px 0;">Nomor Mesin</td>
            <td style="text-align: center; font-weight: bold;">:</td>
            <td style="border-bottom: 1px solid black; padding: 1px 3px;">{{ $report->nomor_mesin ?? '-' }}</td>

            <td style="font-weight: bold; padding: 1px 3px 1px 0;">Hari / Tanggal</td>
            <td style="text-align: center; font-weight: bold;">:</td>
            <td style="border-bottom: 1px solid black; padding: 1px 3px;">{{ \Carbon\Carbon::parse($report->date)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; padding: 1px 3px 1px 0;">Cuaca</td>
            <td style="text-align: center; font-weight: bold;">:</td>
            <td style="border-bottom: 1px solid black; padding: 1px 3px;">{{ $report->cuaca ?? '-' }}</td>

            <td colspan="3"></td> 
        </tr>
    </table>

    @if ($report->mode === 'working')
      <div style="font-weight: bold; padding: 3px 0; border-top: 1px solid black; border-bottom: 1px solid black; background-color: #f0f0f0; margin-top: 5px; margin-bottom: 5px; background-color: #ccc;">A. DATA PEKERJAAN</div>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 3px;">
          <tr>
              <td style="width: 20%; font-weight: bold; padding: 1px 3px 1px 0;">Wilayah Resor</td>
              <td style="width: 1%; text-align: center; font-weight: bold;">:</td>
              <td style="width: 30%; border-bottom: 1px solid black; padding: 1px 3px;">{{ optional($report->workresult)->wilayah ?? '-' }}</td>
              <td style="width: 5px;"></td>

              <td style="width: 20%; font-weight: bold; padding: 1px 3px 1px 0;">Lokasi Stabling Awal</td>
              <td style="width: 1%; text-align: center; font-weight: bold;">:</td>
              <td style="width: 33%; border-bottom: 1px solid black; padding: 1px 3px;">{{ optional($report->workresult)->lokasi_stabling_awal ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold; padding: 1px 3px 1px 0;">Petak Jalan</td>
              <td style="text-align: center; font-weight: bold;">:</td>
              <td style="border-bottom: 1px solid black; padding: 1px 3px;">{{ optional($report->workresult)->petak_jalan ?? '-' }}</td>
              <td></td>

              <td style="font-weight: bold; padding: 1px 3px 1px 0;">Lokasi Stabling Akhir</td>
              <td style="text-align: center; font-weight: bold;">:</td>
              <td style="border-bottom: 1px solid black; padding: 1px 3px;">{{ optional($report->workresult)->lokasi_stabling_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold; padding: 1px 3px 1px 0;">Kelas Jalan</td>
              <td style="text-align: center; font-weight: bold;">:</td>
              <td style="border-bottom: 1px solid black; padding: 1px 3px;">{{ optional($report->workresult)->kelas_jalan ?? '-' }}</td>
              <td></td>
              
              <td colspan="3"></td>
          </tr>
      </table>

      <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
          <tr>
              <td style="width: 20%; font-weight: bold;">Lokasi (Km/Hm)</td>
              <td style="width: 1%;">:</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->lokasi_awal1 ?? '-' }}</td>
              <td style="width: 2%; text-align: center;">s/d</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->lokasi_akhir1 ?? '-' }}</td>
              <td style="width: 5%;">{{ optional($report->workresult)->hu_hi1 ?? '-' }}</td>
              
              <td style="width: 15%; text-align: right;">Jumlah (Km/Hm)</td>
              <td style="width: 1%;">:</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->jumlah1 ?? '' }} </td>
              <td>M'sp</td>
          </tr>
          <tr>
              <td style="width: 15%;"></td>
              <td style="width: 1%;">:</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->lokasi_awal2 ?? '-' }}</td>
              <td style="text-align: center;">s/d</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->lokasi_akhir2 ?? '-' }}</td>
              <td style="width: 5%;">{{ optional($report->workresult)->hu_hi2 ?? '-' }}</td>
              
              <td style="text-align: right;">Jumlah (Km/Hm)</td>
              <td>:</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->jumlah2 ?? '' }} </td>
              <td>M'sp</td>
          </tr>
          <tr>
              <td style="width: 15%;"></td>
              <td style="width: 1%;">:</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->lokasi_awal3 ?? '-' }}</td>
              <td style="text-align: center;">s/d</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->lokasi_akhir3 ?? '-' }}</td>
              <td style="width: 5%;">{{ optional($report->workresult)->hu_hi3 ?? '-' }}</td>

              <td style="text-align: right;">Jumlah (Km/Hm)</td>
              <td>:</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->jumlah3 ?? '' }} </td>
              <td>M'sp</td>
              
          </tr>
          <tr>          
              <td style="width: 15%;"></td>
              <td style="width: 1%;"></td>
              <td style=""></td>
              <td style="text-align: center;"></td>
              <td style=""></td>
              <td></td>

              <td style="text-align: right;">Total (Km/Hm)</td>
              <td>:</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->total_distance ?? '' }} </td>
              <td>M'sp</td>
          </tr>
      </table>

      <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
          <tr>
              <td style="width: 20%; font-weight: bold;">No. Wesel</td>
              <td style="width: 1%;">:</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->no_wesel1 ?? '-' }}</td>
              <td style="width: 2%; text-align: center;">Km/Hm</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->km_hm1 ?? '-' }}</td>
              <td style="width: 5%;">{{ optional($report->workresult)->hu_hi4 ?? '-' }}</td>
              
              <td style="width: 15%; text-align: right;">Jumlah Wesel</td>
              <td style="width: 1%;">:</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->jumlah_wesel1 ?? '' }}</td>
              <td>Unit</td>
          </tr>
          <tr>
              <td style="width: 15%;"></td>
              <td style="width: 1%;">:</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->no_wesel2 ?? '-' }}</td>
              <td style="width: 2%; text-align: center;">Km/Hm</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->km_hm2 ?? '-' }}</td>
              <td style="width: 5%;">{{ optional($report->workresult)->hu_hi5 ?? '-' }}</td>
              
              <td style="text-align: right;">Jumlah Wesel</td>
              <td>:</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->jumlah_wesel2 ?? '' }} </td>
              <td>Unit</td>
          </tr>
          <tr>
              <td style="width: 15%;"></td>
              <td style="width: 1%;">:</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->no_wesel3 ?? '-' }}</td>
              <td style="width: 2%; text-align: center;">Km/Hm</td>
              <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->km_hm3 ?? '-' }}</td>
              <td style="width: 5%;">{{ optional($report->workresult)->hu_hi6 ?? '-' }}</td>
              
              <td style="text-align: right;">Jumlah Wesel</td>
              <td>:</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->jumlah_wesel3 ?? '' }} </td>
              <td>Unit</td>
          </tr>
          <tr>          
              <td style="width: 15%;"></td>
              <td style="width: 1%;"></td>
              <td style=""></td>
              <td style="text-align: center;"></td>
              <td style=""></td>
              <td></td>

              <td style="text-align: right;">Total Wesel</td>
              <td>:</td>
              <td style="border-bottom: 1px solid black; text-align: center;">{{ optional($report->workresult)->total_wesel ?? '' }} </td>
              <td>Unit</td>
          </tr>
      </table>
      
      <br>

      <div style="font-weight: bold; margin-bottom: 3px;">1. Data Opname Resor Jalan Rel (Awal):</div>
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
          <tr>
              <td style="width: 5px;">a.</td>
              <td style="width: 250px;">IP 2 (Lurusan)</td>
              <td style="width: 80px; text-align: right; white-space: nowrap;">Ada 
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mglurusanawal)->ada == 1 ? '&#10003;' : '&nbsp;&nbsp;&nbsp;' !!}
                  </span>
              </td>
              
              <td style="width: 100px; text-align: left; white-space: nowrap;">Tidak ada
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mglurusanawal)->ada == 0 ? '&#10003;' : '&nbsp;&nbsp;&nbsp;' !!}
                  </span>
              </td>
          </tr>
          <tr>
              <td>b.</td>
              <td>IG 2 (Lengkung)</td>
              
              <td style="text-align: right; white-space: nowrap;">Ada 
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mglengkunganawal)->ada == 1 ? '&#10003;' : '&nbsp;&nbsp;&nbsp;' !!}
                  </span>
              </td>
              
              <td style="text-align: left; white-space: nowrap;">Tidak ada
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mglengkunganawal)->ada == 0 ? '&#10003;' : '&nbsp;&nbsp;&nbsp;' !!}
                  </span>
              </td>
          </tr>
          <tr>
              <td>c.</td>
              <td>IG 3 (Wesel)</td>
              
              <td style="text-align: right; white-space: nowrap;">Ada 
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mgweselawal)->ada == 1 ? '&#10003;' : '&nbsp;&nbsp;&nbsp;' !!}
                  </span>
              </td>
              
              <td style="text-align: left; white-space: nowrap;">Tidak ada
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mgweselawal)->ada == 0 ? '&#10003;' : '&nbsp;&nbsp;&nbsp;' !!}
                  </span>
              </td>
          </tr>
      </table>

      <div style="font-weight: bold; margin-bottom: 3px;">2. Data Pemeriksaan Silang</div>
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
          <tr>
              <td style="width: 5px;">a.</td>
              <td style="width: 250px;">KPJR</td>
              
              <td style="width: 80px; text-align: right; white-space: nowrap;">Ada 
                  {{-- Kotak Ceklis Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->pemeriksaansilangkpjr)->ada == 1 ? '&#10003;' : '' !!}
                  </span>
              </td>
              
              <td style="width: 100px; text-align: left; white-space: nowrap;">Tidak ada
                  {{-- Kotak Ceklis Tidak Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->pemeriksaansilangkpjr)->ada == 0 ? '&#10003;' : '' !!}
                  </span>
              </td>
          </tr>
          <tr>
              <td>b.</td>
              <td>Lahan</td>
              
              <td style="text-align: right; white-space: nowrap;">Ada 
                  {{-- Kotak Ceklis Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->pemeriksaansilanglahan)->ada == 1 ? '&#10003;' : '' !!}
                  </span>
              </td>
              
              <td style="text-align: left; white-space: nowrap;">Tidak ada
                  {{-- Kotak Ceklis Tidak Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->pemeriksaansilanglahan)->ada == 0 ? '&#10003;' : '' !!}
                  </span>
              </td>
          </tr>
      </table>

      <div style="font-weight: bold; margin-bottom: 3px;">3. Data Perekaman (Awal)</div>
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
          <tr>
              <td style="width: 5px;"></td> 
              <td style="width: 250px;">Perekaman Awal</td>
              
              <td style="width: 80px; text-align: right; white-space: nowrap;">Ada 
                  {{-- Kotak Ceklis Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->perekamanawal)->ada == 1 ? '&#10003;' : '' !!}
                  </span>
              </td>
              
              <td style="width: 100px; text-align: left; white-space: nowrap;">Tidak ada
                  {{-- Kotak Ceklis Tidak Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->perekamanawal)->ada == 0 ? '&#10003;' : '' !!}
                  </span>
              </td>
          </tr>
      </table>

      <div style="font-weight: bold; margin-bottom: 3px;">4. Data Opname Resor Jalan Rel (Akhir):</div>
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
          <tr>
              <td style="width: 5px;">a.</td>
              <td style="width: 250px;">IP 2 (Lurusan)</td>
              
              <td style="width: 80px; text-align: right; white-space: nowrap;">Ada 
                  {{-- Kotak Ceklis Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mglurusanakhir)->ada == 1 ? '&#10003;' : '' !!}
                  </span>
              </td>
              
              <td style="width: 100px; text-align: left; white-space: nowrap;">Tidak ada
                  {{-- Kotak Ceklis Tidak Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mglurusanakhir)->ada == 0 ? '&#10003;' : '' !!}
                  </span>
              </td>
          </tr>
          <tr>
              <td>b.</td>
              <td>IG 2 (Lengkung)</td>
              
              <td style="text-align: right; white-space: nowrap;">Ada 
                  {{-- Kotak Ceklis Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mglengkunganakhir)->ada == 1 ? '&#10003;' : '' !!}
                  </span>
              </td>
              
              <td style="text-align: left; white-space: nowrap;">Tidak ada
                  {{-- Kotak Ceklis Tidak Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mglengkunganakhir)->ada == 0 ? '&#10003;' : '' !!}
                  </span>
              </td>
          </tr>
          <tr>
              <td>c.</td>
              <td>IG 3 (Wesel)</td>
              
              <td style="text-align: right; white-space: nowrap;">Ada 
                  {{-- Kotak Ceklis Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mgweselakhir)->ada == 1 ? '&#10003;' : '' !!}
                  </span>
              </td>
              
              <td style="text-align: left; white-space: nowrap;">Tidak ada
                  {{-- Kotak Ceklis Tidak Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->mgweselakhir)->ada == 0 ? '&#10003;' : '' !!}
                  </span>
              </td>
          </tr>
      </table>

      <div style="font-weight: bold; margin-bottom: 3px;">5. Data Perekaman (Akhir)</div>
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
          <tr>
              <td style="width: 5px;"></td>
              <td style="width: 250px;">Perekaman Akhir</td>
              
              <td style="width: 80px; text-align: right; white-space: nowrap;">Ada 
                  {{-- Kotak Ceklis Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->perekamanakhir)->ada == 1 ? '&#10003;' : '' !!}
                  </span>
              </td>
              
              <td style="width: 100px; text-align: left; white-space: nowrap;">Tidak ada
                  {{-- Kotak Ceklis Tidak Ada --}}
                  <span style="border: 1px solid black; padding: 0 0 0 0; margin-left: 5px; font-family: 'DejaVu Sans', sans-serif; display: inline-block; width: 10px; height: 10px; line-height: 1; vertical-align: middle;">
                      {!! optional($report->perekamanakhir)->ada == 0 ? '&#10003;' : '' !!}
                  </span>
              </td>
          </tr>
      </table>
    
      <div style="font-weight: bold; padding: 3px 0; border-top: 1px solid black; border-bottom: 1px solid black; background-color: #f0f0f0; margin-top: 5px; margin-bottom: 5px; background-color: #ccc;">B. DATA OPERASI MESIN</div>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
          <tr>
              <td style="width: 20%; font-weight: bold;">Waktu Start Engine</td>
              <td style="width: 1%; text-align: center;">:</td>
              <td style="width: 33%; border-bottom: 1px solid black;">{{ substr($report->waktu_start_engine ?? ' - : - : - ', 0, 5) }} WIB</td>
              <td style="width: 5px;"></td>
              
              <td style="width: 20%; font-weight: bold;">Waktu Stop Engine</td>
              <td style="width: 1%; text-align: center;">:</td>
              <td style="width: 33%; border-bottom: 1px solid black;">{{ optional($report->workresult)->waktu_stop_engine ? date('H:i', strtotime(optional($report->workresult)->waktu_stop_engine)): '-' }} WIB</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Jam Travelling Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->jam_traveling_awal ?? '-' }}</td>
              <td></td>
              
              <td style="font-weight: bold;">Jam Travelling Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->workresult)->jam_traveling_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Awal Jam Kerja Operator</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ substr($report->jam_kerja_awal ?? ' - : - : - ', 0, 5) }} WIB</td>
              <td></td>
              
              <td style="font-weight: bold;">Akhir Jam Kerja Operator</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->workresult)->jam_kerja_akhir ? date('H:i', strtotime(optional($report->workresult)->jam_kerja_akhir)): '-' }} WIB</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Jam Mesin Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->jam_mesin_awal ?? '-' }}</td>
              <td></td>
              
              <td style="font-weight: bold;">Jam Mesin Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->workresult)->jam_mesin_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Jam Generator Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->jam_generator_awal ?? '-' }}</td>
              <td></td>
              
              <td style="font-weight: bold;">Jam Generator Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->workresult)->jam_generator_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Counter Tamping Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->counter_tamping_awal ?? '-' }}</td>
              <td></td>
              
              <td style="font-weight: bold;">Counter Tamping Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->workresult)->counter_tamping_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Odometer Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->oddometer_awal ?? '-' }}</td>
              <td></td>

              <td style="font-weight: bold;">Odometer Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->workresult)->oddometer_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">HSD Awal Kerja</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->hsd_awal_kerja ?? '-' }} %</td>
              <td></td>
              
              <td style="font-weight: bold;">HSD Akhir Kerja</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->workresult)->hsd_akhir_kerja ?? '-' }} %</td>
          </tr>
          <!-- <tr>
              <td style="font-weight: bold;">Konsumsi H&D</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->workresult)->konsumsi_hsd ?? '-' }} Liter</td>
              <td></td>
              
              <td colspan="3"></td>
          </tr> -->
      </table>

      <div style="font-weight: bold; padding: 3px 0; border-top: 1px solid black; border-bottom: 1px solid black; background-color: #f0f0f0; margin-top: 5px; margin-bottom: 5px; background-color: #ccc;">C. DATA PERSONEL</div>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
        <tr>
            <td style="width: 20%; font-weight: bold;">Operator 1</td>
            <td style="width: 1%; text-align: center;">:</td>
            <td style="width: 33%; border-bottom: 1px solid black;">
                {{ ucwords(strtolower(optional($report->operator1)->name ?? '-')) }}
            </td>
            <td style="width: 5px;"></td>

            <td style="width: 20%; font-weight: bold;">Pengawal 1</td>
            <td style="width: 1%; text-align: center;">:</td>
            <td style="width: 33%; border-bottom: 1px solid black;">
                {{ ucwords(strtolower($report->nama_pengawal) ?? '-') }}
            </td>
        </tr>

        <tr>
            <td style="font-weight: bold;">Operator 2</td>
            <td style="text-align: center;">:</td>
            <td style="border-bottom: 1px solid black;">
                {{ ucwords(strtolower(optional($report->operator2)->name ?? '-')) }}
            </td>
            <td></td>

            <td style="font-weight: bold;">Pengawal 2</td>
            <td style="text-align: center;">:</td>
            <td style="border-bottom: 1px solid black;">
                {{ ucwords(strtolower($report->nama_pengawal1) ?? '-') }}
            </td>
        </tr>

        <tr>
            <td style="font-weight: bold;">Operator 3</td>
            <td style="text-align: center;">:</td>
            <td style="border-bottom: 1px solid black;">
                {{ ucwords(strtolower(optional($report->operator3)->name ?? '-')) }}
            </td>
            <td></td>

            <td colspan="3"></td>
        </tr>
      </table>

      <div class="page-break"></div>
      
        @php
            $checkpointRelations = [
                'mglurusanawal' => '1. Foto IP 2 (Lurusan) - Awal',
                'mglengkunganawal' => '2. Foto IG 2 (Lengkung) - Awal',
                'mgweselawal' => '3. Foto IG 3 (Wesel) - Awal',
                'pemeriksaansilangkpjr' => '4. Foto Pemeriksaan Silang KPJR',
                'pemeriksaansilanglahan' => '5. Foto Pemeriksaan Silang Lahan',
                'perekamanawal' => '6. Foto Perekaman Awal',
                'mglurusanakhir' => '7. Foto IP 2 (Lurusan) - Akhir',
                'mglengkunganakhir' => '8. Foto IG 2 (Lengkung) - Akhir',
                'mgweselakhir' => '9. Foto IG 3 (Wesel) - Akhir',
                'perekamanakhir' => '10. Foto Perekaman Akhir',
            ];
        @endphp

        <div style="text-align: center; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 11pt;">DOKUMENTASI FOTO HASIL PEKERJAAN</h3>
        </div>

        @foreach ($checkpointRelations as $relationName => $title)
            @php
                $checkpoint = optional($report)->{$relationName};
                $attachments = optional($checkpoint)->attachments; 
            @endphp

            <div style="margin-top: 10px;">
                <h4 style="margin: 5px 0; border-bottom: 1px dashed #ccc; padding-bottom: 2px; font-size: 9pt;">{{ $title }}</h4>
                
                @if (optional($checkpoint)->ada == 1 && optional($attachments)->isNotEmpty())
                    {{-- ADA FOTO → tampil 1 foto per baris --}}
                    @foreach ($attachments as $attachment)
                        @php
                            $imageUrl = $getAttachmentUrl($attachment);
                        @endphp
                        <div style="margin: 20px 0; text-align: center;">
                            <img src="{{ $imageUrl }}" 
                                style="width: 60%; max-width: 500px; height: 400px; display: block; margin: 0 auto;">
                            <p style="font-size: 8pt; margin-top: 5px;">
                                {{ $loop->iteration }} {{ $attachment->description ?? '' }}
                            </p>
                        </div>
                    @endforeach

                    @else
                        {{-- TIDAK ADA FOTO → tampil 2 kolom, isi TULISAN di tengah halaman --}}
                        <div style="margin: 20px 0; text-align: center;">
                            <div>
                                <div style="width: 60%; max-width: 500px; height: 400px; display: block; margin: 0 auto;">
                                    <span style="font-size: 10pt; color: #555;">Tidak ada foto lampiran</span>
                                </div>
                            </div>
                        </div>
                @endif

            </div>
        @endforeach

        <div class="page-break"></div>

        <div style="text-align: center; margin-bottom: 15px;">
                <h3 style="margin: 0; font-size: 11pt;">LAMPIRAN DAILY CHECK RESULTS</h3><br>

                <table style="width: 100%; border-collapse: collapse; text-align: center; font-size: 8pt;">
                    <thead>
                        <tr>
                            <th style="width: 3%; border: 1px solid black; padding: 4px; background-color: #ccc;">No</th>
                            <th style="width: 25%; border: 1px solid black; padding: 4px; background-color: #ccc; text-align: left;">Komponen</th>
                            <th style="width: 10%; border: 1px solid black; padding: 4px; background-color: #ccc;">Rujukan</th>
                            <th colspan="3" style="width: 15%; border: 1px solid black; padding: 4px; background-color: #ccc;">Kondisi</th>
                            <th style="width: 10%; border: 1px solid black; padding: 4px; background-color: #ccc;">Nilai Rujukan</th>
                            <th colspan="2" style="width: 15%; border: 1px solid black; padding: 4px; background-color: #ccc;">Hasil Cek</th>
                            <th style="width: 5%; border: 1px solid black; padding: 4px; background-color: #ccc;">Sat.</th>
                            <th style="width: 17%; border: 1px solid black; padding: 4px; background-color: #ccc;">Keterangan (App.)</th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Cek</th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Tambah</th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Ganti</th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Kr/Dpn</th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Kn/Dpn</th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                            <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentGroup = null;
                            $i = 1;
                        @endphp

                        @foreach ($masters as $item)
                            @if ($item->group_name !== $currentGroup)
                                <tr>
                                    <td colspan="11" style="border: 1px solid black; padding: 4px; text-align: left; background-color: #333; color: white; font-weight: bold;">
                                        {{ strtoupper($item->group_name) }}
                                    </td>
                                </tr>
                                @php
                                    $currentGroup = $item->group_name;
                                @endphp
                            @endif
                            
                            {{-- Temukan hasil terkait dari mergedResults --}}
                            @php
                                // UBAH $results menjadi $mergedResults sesuai nama variabel di Controller
                                $result = collect($mergedResults)->firstWhere('check_sheet_master_day_id', $item->id);
                            @endphp
                            
                            @if ($result)
                                <tr>
                                    <td style="border: 1px solid black; padding: 4px;">{{ $item->urutan }}</td>
                                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $item->komponen }}</td>
                                    <td style="border: 1px solid black; padding: 4px;">{{ $item->rujukan }}</td>
                                    
                                    {{-- Kondisi (Cek, Tambah, Ganti) --}}
                                    <td style="border: 1px solid black; padding: 4px;" class="check">{!! $result['cek'] == 1 ? '✓' : '-' !!}</td>
                                    <td style="border: 1px solid black; padding: 4px;" class="check">{!! $result['tambahan'] == 1 ? '✓' : '-' !!}</td>
                                    <td style="border: 1px solid black; padding: 4px;" class="check">{!! $result['ganti'] == 1 ? '✓' : '-' !!}</td>
                                    
                                    <td style="border: 1px solid black; padding: 4px;">{{ $item->nilai_rujukan }}</td>
                                    
                                    {{-- Hasil Cek (Kr/Dpn, Kn/Dpn) --}}
                                    <td style="border: 1px solid black; padding: 4px;">{{ $result['kiri_depan'] ?? '-' }}</td>
                                    <td style="border: 1px solid black; padding: 4px;">{{ $result['kanan_depan'] ?? '-' }}</td>
                                    
                                    <td style="border: 1px solid black; padding: 4px;">{{ $item->satuan }}</td>
                                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $result['keterangan'] ?? '-' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
        </div>


        <table style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: center;">
            <thead>
                <tr>
                    <th style="width: 50%; border: 1px solid black; padding: 5px; background-color: #ccc;">Catatan</th>
                    <th style="width: 25%; border: 1px solid black; padding: 5px; background-color: #ccc;">Petugas</th>
                    <th style="width: 25%; border: 1px solid black; padding: 5px; background-color: #ccc;">Nipp</th>
                    <th style="width: 25%; border: 1px solid black; padding: 5px; background-color: #ccc;">Paraf</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3" style="vertical-align: top; border: 1px solid black; padding: 5px;">{{ optional($report->checksheetworkresult)->catatan ?? '-' }}</td>
                    <td style="height: 10px; border: 1px solid black;">{{ ucwords(strtolower(optional($report->operator1)->name ?? '-')) }}</td>
                    <td style="height: 10px; border: 1px solid black;">{{ optional($report->operator1)->username ?? '-' }}</td>
                    <td style="height: 50px; border: 1px solid black; text-align: center; vertical-align: bottom;">{{ $report->operator_at1 ? \Carbon\Carbon::parse($report->operator_at1)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="height: 10px; border: 1px solid black;">{{ ucwords(strtolower(optional($report->operator2)->name ?? '-')) }}</td>
                    <td style="height: 10px; border: 1px solid black;">{{ optional($report->operator2)->username ?? '-' }}</td>
                    <td style="height: 50px; border: 1px solid black; text-align: center; vertical-align: bottom;">{{ $report->operator_at1 ? \Carbon\Carbon::parse($report->operator_at2)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="height: 10px; border: 1px solid black;">{{ ucwords(strtolower(optional($report->operator3)->name ?? '-')) }}</td>
                    <td style="height: 10px; border: 1px solid black;">{{ optional($report->operator3)->username ?? '-' }}</td>
                    <td style="height: 50px; border: 1px solid black; text-align: center; vertical-align: bottom;">{{ $report->operator_at1 ? \Carbon\Carbon::parse($report->operator_at3)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
            </tbody>
        </table>

    @endif

    @if ($report->mode === 'warmingup') 
      <div style="font-weight: bold; padding: 3px 0; border-top: 1px solid black; border-bottom: 1px solid black; background-color: #f0f0f0; margin-top: 5px; margin-bottom: 5px; background-color: #ccc;">A. DATA OPERASI MESIN</div>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
          <tr>
              <td style="width: 20%; font-weight: bold;">Waktu Start Engine</td>
              <td style="width: 1%; text-align: center;">:</td>
              <td style="width: 33%; border-bottom: 1px solid black;">{{ substr($report->waktu_start_engine ?? ' - : - : - ', 0, 5) }} WIB</td>
              <td style="width: 5px;"></td>
              
              <td style="width: 20%; font-weight: bold;">Waktu Stop Engine</td>
              <td style="width: 1%; text-align: center;">:</td>
              <td style="width: 33%; border-bottom: 1px solid black;">{{ optional($report->warmingup)->waktu_stop_engine ? date('H:i', strtotime(optional($report->warmingup)->waktu_stop_engine)): '-' }} WIB</td>
          </tr>
          <!-- <tr>
              <td style="font-weight: bold;">Jam Travelling Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->jam_traveling_awal ?? '-' }}</td>
              <td></td>
              
              <td style="font-weight: bold;">Jam Travelling Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->warmingup)->jam_traveling_akhir ?? '-' }}</td>
          </tr> -->
          <tr>
              <td style="font-weight: bold;">Awal Jam Kerja Operator</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ substr($report->jam_kerja_awal ?? ' - : - : - ', 0, 5) }} WIB</td>
              <td></td>
              
              <td style="font-weight: bold;">Akhir Jam Kerja Operator</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->warmingup)->jam_kerja_akhir ? date('H:i', strtotime(optional($report->warmingup)->jam_kerja_akhir)): '-' }} WIB</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Jam Mesin Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->jam_mesin_awal ?? '-' }}</td>
              <td></td>
              
              <td style="font-weight: bold;">Jam Mesin Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->warmingup)->jam_mesin_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Jam Generator Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->jam_generator_awal ?? '-' }}</td>
              <td></td>
              
              <td style="font-weight: bold;">Jam Generator Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->warmingup)->jam_generator_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Counter Tamping Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->counter_tamping_awal ?? '-' }}</td>
              <td></td>
              
              <td style="font-weight: bold;">Counter Tamping Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->warmingup)->counter_tamping_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">Odometer Awal</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->oddometer_awal ?? '-' }}</td>
              <td></td>

              <td style="font-weight: bold;">Odometer Akhir</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->warmingup)->oddometer_akhir ?? '-' }}</td>
          </tr>
          <tr>
              <td style="font-weight: bold;">HSD Awal Kerja</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ $report->hsd_awal_kerja ?? '-' }} %</td>
              <td></td>
              
              <td style="font-weight: bold;">HSD Akhir Kerja</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->warmingup)->hsd_akhir_kerja ?? '-' }} %</td>
          </tr>
          <!-- <tr>
              <td style="font-weight: bold;">Konsumsi H&D</td>
              <td style="text-align: center;">:</td>
              <td style="border-bottom: 1px solid black;">{{ optional($report->warmingup)->konsumsi_hsd ?? '-' }} Liter</td>
              <td></td>
              
              <td colspan="3"></td>
          </tr> -->
      </table>

      <div style="font-weight: bold; padding: 3px 0; border-top: 1px solid black; border-bottom: 1px solid black; background-color: #f0f0f0; margin-top: 5px; margin-bottom: 5px; background-color: #ccc;">B. DATA PERSONEL</div>

      <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
        <tr>
            <td style="width: 18%; font-weight: bold;">Operator 1</td>
            <td style="width: 1%; text-align: center;">:</td>
            <td style="width: 33%; border-bottom: 1px solid black;">
                {{ ucwords(strtolower(optional($report->operator1)->name ?? '-')) }}
            </td>
            <td style="width: 5px;"></td>

            <!-- <td style="width: 20%; font-weight: bold;">Pengawal 1</td>
            <td style="width: 1%; text-align: center;">:</td>
            <td style="width: 33%; border-bottom: 1px solid black;">
                {{ ucwords(strtolower($report->nama_pengawal) ?? '-') }}
            </td> -->
        </tr>

        <tr>
            <td style="font-weight: bold;">Operator 2</td>
            <td style="text-align: center;">:</td>
            <td style="border-bottom: 1px solid black;">
                {{ ucwords(strtolower(optional($report->operator2)->name ?? '-')) }}
            </td>
            <td></td>

            <!-- <td style="font-weight: bold;">Pengawal 2</td>
            <td style="text-align: center;">:</td>
            <td style="border-bottom: 1px solid black;">
                {{ ucwords(strtolower($report->nama_pengawal1) ?? '-') }}
            </td> -->
        </tr>

        <tr>
            <td style="font-weight: bold;">Operator 3</td>
            <td style="text-align: center;">:</td>
            <td style="border-bottom: 1px solid black;">
                {{ ucwords(strtolower(optional($report->operator3)->name ?? '-')) }}
            </td>
            <td></td>

            <td colspan="3"></td>
        </tr>
      </table>

      <div class="page-break"></div>

        <div style="text-align: center; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 11pt;">LAMPIRAN DAILY CHECK RESULTS</h3><br>

            <table style="width: 100%; border-collapse: collapse; text-align: center; font-size: 8pt;">
                <thead>
                    <tr>
                        <th style="width: 3%; border: 1px solid black; padding: 4px; background-color: #ccc;">No</th>
                        <th style="width: 25%; border: 1px solid black; padding: 4px; background-color: #ccc; text-align: left;">Komponen</th>
                        <th style="width: 10%; border: 1px solid black; padding: 4px; background-color: #ccc;">Rujukan</th>
                        <th colspan="3" style="width: 15%; border: 1px solid black; padding: 4px; background-color: #ccc;">Kondisi</th>
                        <th style="width: 10%; border: 1px solid black; padding: 4px; background-color: #ccc;">Nilai Rujukan</th>
                        <th colspan="2" style="width: 15%; border: 1px solid black; padding: 4px; background-color: #ccc;">Hasil Cek</th>
                        <th style="width: 5%; border: 1px solid black; padding: 4px; background-color: #ccc;">Sat.</th>
                        <th style="width: 17%; border: 1px solid black; padding: 4px; background-color: #ccc;">Keterangan (App.)</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Cek</th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Tambah</th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Ganti</th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Kr/Dpn</th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;">Kn/Dpn</th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                        <th style="border: 1px solid black; padding: 4px; background-color: #eee;"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentGroup = null;
                        $i = 1;
                    @endphp

                    @foreach ($masters as $item)
                        @if ($item->group_name !== $currentGroup)
                            <tr>
                                <td colspan="11" style="border: 1px solid black; padding: 4px; text-align: left; background-color: #333; color: white; font-weight: bold;">
                                    {{ strtoupper($item->group_name) }}
                                </td>
                            </tr>
                            @php
                                $currentGroup = $item->group_name;
                            @endphp
                        @endif
                        
                        {{-- Temukan hasil terkait dari mergedResults --}}
                        @php
                            // UBAH $results menjadi $mergedResults sesuai nama variabel di Controller
                            $result = collect($mergedResults)->firstWhere('check_sheet_master_day_id', $item->id);
                        @endphp
                        
                        @if ($result)
                            <tr>
                                <td style="border: 1px solid black; padding: 4px;">{{ $item->urutan }}</td>
                                <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $item->komponen }}</td>
                                <td style="border: 1px solid black; padding: 4px;">{{ $item->rujukan }}</td>
                                
                                {{-- Kondisi (Cek, Tambah, Ganti) --}}
                                <td style="border: 1px solid black; padding: 4px;" class="check">{!! $result['cek'] == 1 ? '✓' : '-' !!}</td>
                                <td style="border: 1px solid black; padding: 4px;" class="check">{!! $result['tambahan'] == 1 ? '✓' : '-' !!}</td>
                                <td style="border: 1px solid black; padding: 4px;" class="check">{!! $result['ganti'] == 1 ? '✓' : '-' !!}</td>
                                
                                <td style="border: 1px solid black; padding: 4px;">{{ $item->nilai_rujukan }}</td>
                                
                                {{-- Hasil Cek (Kr/Dpn, Kn/Dpn) --}}
                                <td style="border: 1px solid black; padding: 4px;">{{ $result['kiri_depan'] ?? '-' }}</td>
                                <td style="border: 1px solid black; padding: 4px;">{{ $result['kanan_depan'] ?? '-' }}</td>
                                
                                <td style="border: 1px solid black; padding: 4px;">{{ $item->satuan }}</td>
                                <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $result['keterangan'] ?? '-' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: center;">
            <thead>
                <tr>
                    <th style="width: 50%; border: 1px solid black; padding: 5px;  background-color: #ccc;">Catatan</th>
                    <th style="width: 25%; border: 1px solid black; padding: 5px;  background-color: #ccc;">Petugas</th>
                    <th style="width: 25%; border: 1px solid black; padding: 5px;  background-color: #ccc;">Nipp</th>
                    <th style="width: 25%; border: 1px solid black; padding: 5px;  background-color: #ccc;">Paraf</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3" style="vertical-align: top; border: 1px solid black; padding: 5px;">{{ optional($report->checksheetworkresult)->catatan ?? '-' }}</td>
                    <td style="height: 10px; border: 1px solid black;">{{ ucwords(strtolower(optional($report->operator1)->name ?? '-')) }}</td>
                    <td style="height: 10px; border: 1px solid black;">{{ optional($report->operator1)->username ?? '-' }}</td>
                    <td style="height: 50px; border: 1px solid black; text-align: center; vertical-align: bottom;">{{ $report->operator_at1 ? \Carbon\Carbon::parse($report->operator_at1)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="height: 10px; border: 1px solid black;">{{ ucwords(strtolower(optional($report->operator2)->name ?? '-')) }}</td>
                    <td style="height: 10px; border: 1px solid black;">{{ optional($report->operator2)->username ?? '-' }}</td>
                    <td style="height: 50px; border: 1px solid black; text-align: center; vertical-align: bottom;">{{ $report->operator_at1 ? \Carbon\Carbon::parse($report->operator_at2)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="height: 10px; border: 1px solid black;">{{ ucwords(strtolower(optional($report->operator3)->name ?? '-')) }}</td>
                    <td style="height: 10px; border: 1px solid black;">{{ optional($report->operator3)->username ?? '-' }}</td>
                    <td style="height: 50px; border: 1px solid black; text-align: center; vertical-align: bottom;">{{ $report->operator_at1 ? \Carbon\Carbon::parse($report->operator_at3)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
            </tbody>
        </table>
    @endif

</body>
</html>