<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Event Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1 class="header">Event Report for {{ $event->name }}</h1>
    <p class="header">Date: {{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}</p>

    <h3>Inclusions</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inclusions as $inclusion)
            <tr>
                <td>{{ $inclusion->name }}</td>
                <td>₱{{ number_format($inclusion->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Staff</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Rate</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staff as $staffMember)
            <tr>
                <td>{{ $staffMember->name }}</td>
                <td>{{ $staffMember->role }}</td>
                <td>₱{{ number_format($staffMember->pay_rate, 2) }}</td>
                <td>{{ ucfirst($staffMember->pay_status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Guests</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $guest)
            <tr>
                <td>{{ $guest->name }}</td>
                <td>{{ $guest->contact_number }}</td>
                <td>{{ $guest->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>