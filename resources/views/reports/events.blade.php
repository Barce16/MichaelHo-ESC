<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Report</title>
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
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        h2 {
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <h1>Event List Report</h1>

    @foreach($events as $event)
    <h2>Event Report: {{ $event->name }}</h2>
    <p>Date: {{ $event->event_date }}</p>
    <p>Package: {{ $event->package->name }}</p>
    <h3>Staff: {{ $event->staffs->count() }}</h3>
    <h3>Guests: {{ $event->guests->count() }}</h3>
    <table>
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->guests as $guest)
            <tr>
                <td>{{ $guest->name }}</td>
                <td>{{ $guest->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @endforeach

</body>

</html>