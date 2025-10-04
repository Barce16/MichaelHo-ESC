<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            font-weight: bold;
            font-size: 14pt;
            /* Larger font size for headers */
        }

        td {
            font-size: 12pt;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 16pt;
        }
    </style>
</head>

<body>

    <h1>Staff Report</h1>

    <table>
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Contact Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staffs as $staff)
            <tr>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->role_type }}</td>
                <td>{{ $staff->user->email }}</td>
                <td>{{ $staff->contact_number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>