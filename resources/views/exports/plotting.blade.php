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
        <td colspan="15">
            <h1>DRAFT PENYEBARAN MATA KULIAH DAN DOSEN SEMESTER GENAP TAHUN AKADEMIK 2024/2025</h1>
        </td>
    </table>
  
    <table>
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Mata Kuliah</th>
            <th rowspan="2">SMT</th>
            <th rowspan="2">SKS</th>
            <th rowspan="2">PST</th>
            <th colspan="4">Tahun Akademik 2023/2024</th>
            <th colspan="4">Draft Tahun Akademik 2024/2025</th>
            <th rowspan="2">Koordinator Mata Kuliah</th>
            <th rowspan="2">Dosen Pembina</th>
        </tr>
        <tr>
            <th colspan="3">Pembagian Kelas</th>
            <th>Dosen</th>
            <th colspan="3">Pembagian Kelas</th>
            <th>Dosen</th>
        </tr>

        @foreach ($plotting as $key => $item)
        <tr>
            <td rowspan="{{ count($item['dosen']) + 1 }}">1</td>
            <td rowspan="{{ count($item['dosen']) + 1 }}">{{ $item['nama_mk']}}</td>
            <td rowspan="{{ count($item['dosen']) + 1 }}">{{ $item['smt']}}</td>
            <td rowspan="{{ count($item['dosen']) + 1 }}">{{ $item['sks']}}</td>
            <td rowspan="{{ count($item['dosen']) + 1 }}">{{ $item['peserta']}}</td>
            <td rowspan="{{ count($item['dosen']) + 1 }}">{{ $item['total_kelas'] }}</td>
        </tr>
        @foreach ($item['dosen'] as $dosen)
        <tr>
            <td>{{ $dosen['kelas']}}</td>
            <td>{{ $dosen['jumlah_kelas']}}</td>
            <td>{{ $dosen['nama_dosen']}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $item['koordinator'] }}</td>
            <td>{{ $item['pembina'] }}</td>
        </tr>
        @endforeach
        @endforeach
    </table>
</body>
</html>
