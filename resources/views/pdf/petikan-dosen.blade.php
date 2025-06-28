<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Petikan Dosen Plotting</title>
    <style>
        body {
            font-size: 14px;
        }
        .page-break {
            page-break-after: always;
        }
        .dosen-section {
            margin-bottom: 30px;
        }
        .dosen-info {
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total-row {
            background-color: #e8f4fd;
            font-weight: bold;
        }
        
        .text-center {
            text-align: center;
            margin-bottom: 20px;
        }
        .text-center p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .info {
            margin-bottom: 20px;
        }

        .info table {
            margin: 0;
            padding: 0;
            border: none;
        }
        .info table th,
        .info table td {
            border: none;
            margin: 0;
            padding: 0;
        }

        .footer table {
            margin: 0;
            padding: 0;
            border: none;
        }

        .footer table th,
        .footer table td {
            border: none;
            margin: 0;
            padding: 0;
        }
        
        .logo table {
            width: 40%;
            margin-bottom: 20px;
        }

        .logo table td {
            border: none;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    @foreach ($plotting as $index => $item)
        <div class="dosen-section {{ $loop->last ? '' : 'page-break' }}">
            <div class="logo">
                <table>
                    <tr>
                        <td style="padding-right: 10px;">
                            <img src="{{ public_path('images/logo-unpas.png') }}" alt="Logo Unpas" style="width: 60px; height: auto;" >
                        </td>
                        <td><h4>UNIVERSITAS PASUNDAN BANDUNG</h4></td>
                    </tr>
                    <tr>
                        <td colspan="2">Jl. Tamansari No. 6-8 Bandung</td>
                    </tr>
                </table>
            </div>
            <div class="text-center">
                <p>TENTANG</p>
                <P>PENGANGKATAN DOSEN/ASISTEN YANG MENGAJAR PADA</P>
                <P>{{ strtoupper($tahun_akademik->nama_periode) }}</P>
            </div>
            <div class="info">
                <table>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Petikan</td>
                    </tr>
                    <tr>
                        <td>Lampiran II</td>
                        <td>:</td>
                        <td>Keputusan Rektor Universitas Pasundan</td>
                    </tr>
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($tanggal ?? now())->translatedFormat('d F Y') }}</td>
                    </tr>
                </table>
            </div>
            <table>
                <thead >
                    <tr>
                        <th style="text-align: center" >No</th>
                        <th style="text-align: center" >Nama</th>
                        <th style="text-align: center" >Pangkat Gol</th>
                        <th style="text-align: center" >Jabatan Akademik</th>
                        <th style="text-align: center" >Mata Kuliah</th>
                        <th style="text-align: center" >SKS</th>
                        <th style="text-align: center" >JML KLS</th>
                        <th style="text-align: center" >Total (SKS x KLS)</th>
                        <th style="text-align: center" >Ket</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalMataKuliah = count($item['nama_mk']);
                    @endphp
                    
                    @foreach ($item['nama_mk'] as $key => $matkul)
                    <tr>
                        @if ($key == 0)
                            <!-- Tampilkan data dosen hanya di baris pertama dengan rowspan -->
                            <td style="text-align: center" rowspan="{{ $totalMataKuliah }}">{{ $loop->parent->iteration }}</td>
                            <td rowspan="{{ $totalMataKuliah }}">{{ $item['dosen_pengajar'] }}</td>
                            <td rowspan="{{ $totalMataKuliah }}">{{ $item['pangkat_golongan'] }}</td>
                            <td rowspan="{{ $totalMataKuliah }}">{{ $item['jabatan'] }}</td>
                        @endif
                        <td>{{ $matkul['nama_mk'] }}</td>
                        <td>{{ $matkul['sks'] }}</td>
                        <td>{{ $matkul['jumlah_kelas'] }}</td>
                        <td>{{ $matkul['sks_kelas'] }}</td>
                        <td>{{ $matkul['keterangan'] ?? '-' }}</td>
                    </tr>
                    @endforeach
                    
                    <!-- Baris Total - sebagai baris terpisah tanpa rowspan conflict -->
                    <tr class="total-row">
                        <td colspan="5"><strong>Total</strong></td>
                        <td><strong>{{ array_sum(array_column($item['nama_mk'], 'sks')) }}</strong></td>
                        <td><strong>{{ array_sum(array_column($item['nama_mk'], 'jumlah_kelas')) }}</strong></td>
                        <td><strong>{{ $item['total_sks_kelas'] }}</strong></td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
            <p>Untuk petikan yang sah sesuai dengan aslinya</p>
            <div class="footer">
                <table>
                    <tr>
                        <td></td>
                        <td>Bandung, {{ \Carbon\Carbon::parse($tanggal ?? now())->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 150px">Kepala Biro BELMAWABUD,</td>
                        <td>Rektor,</td>
                    </tr>
                    <tr style="height: 60px;">
                        <td style="padding-top: 30px; padding-bottom: 30px;"></td>
                        <td style="padding-top: 30px; padding-bottom: 30px;"></td>
                    </tr>
                    <tr>
                        <td>Hj.R. Iin Martina, S.H. </td>
                        <td>
                            <u>Prof. Dr. H. Azhar Affandi, S.E.,M.Sc.</u><br>
                            NIPY : 151.100.19
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</body>
</html>