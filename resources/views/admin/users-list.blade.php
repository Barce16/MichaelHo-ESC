<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->user_type) }}</td>
            <td>
                <!-- Optionally, add Edit and Delete functionality -->
                <a href="{{ route('admin.edit-user', $user->id) }}">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>