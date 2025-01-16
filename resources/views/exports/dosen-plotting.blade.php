<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="10">
                <h1>PENEMPATAN DOSEN</h1>
            </td>
        </tr>
        <tr>
            <td colspan="10"></td>
        </tr>
    </table>
    <table>
        <tr>
            <th rowspan="3">No.</th>
            <th rowspan="3">Tenaga Pendidik Pengampu/Asisten</th>
            <th rowspan="3">Pangkat/Golongan</th>
            <th rowspan="3">Jafung</th>
            <th rowspan="3">Mata Kuliah Yang Diampu</th>
            <th colspan="4">Tahun Akademik</th>
            <th rowspan="3">Keterangan</th>
        </tr>
        <tr>
            <th colspan="4">Jumlah</th>
        </tr>
       <tr>
            <th>SKS</th>
            <th>KLS</th>
            <th>SKS*KLS</th>
            <th>Jumlah</th>
        </tr>
        @foreach ($plotting as $key => $item)
        <tr>
          <td rowspan="{{ count($item['nama_mk']) + 2 }}">{{ $loop->iteration }}</td>
          <td rowspan="{{ count($item['nama_mk']) + 2 }}">{{ $item['dosen_pengajar'] }}</td>
          <td rowspan="{{ count($item['nama_mk']) + 2 }}">{{ $item['pangkat_golongan'] }}</td>
          <td rowspan="{{ count($item['nama_mk']) + 2 }}">{{ $item['jabatan'] }}</td>
        </tr>
        @foreach ($item['nama_mk'] as $mk)
        <tr>
          <td>{{ $mk['nama_mk']}}</td>
          <td>{{ $mk['sks']}}</td>
          <td>{{ $mk['jumlah_kelas']}}</td>
          <td>{{ $mk['sks_kelas']}}</td>
          <td>{{ $mk['sks_kelas']}}</td>
          <td>{{ $mk['keterangan'] }}</td>
        </tr>
        @endforeach
        <tr>
          <td colspan="4">Total</td>
          <td>{{ $item['total_sks_kelas'] }}</td>
          <td></td>
        </tr>
        @endforeach
    </table>
</body>
</html>
