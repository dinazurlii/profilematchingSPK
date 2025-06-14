<!DOCTYPE html>
<html>
<head>
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Daftar Karyawan</h1>

    @if ($employees->isEmpty())
        <p style="color: orange;">⚠️ Tidak ada data karyawan</p>
    @else
        <p style="color: green;">✅ Menampilkan {{ count($employees) }} data karyawan</p>

        <table>
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Group</th>
                    <th>Role</th>
                    <th>Divisi</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->nip }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->group_name }}</td>
                        <td>{{ $employee->role_name }}</td>
                        <td>{{ $employee->division_name }}</td>
                        <td>{{ $employee->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
