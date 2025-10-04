<form method="POST" action="{{ route('admin.update-user', $user->id) }}">
    @csrf
    @method('PUT')

    <!-- Name, Email, and Password fields as usual -->

    <!-- User Type (only for Admin to change) -->
    <x-input-label for="user_type" :value="__('User Type')" />
    <select id="user_type" name="user_type" required>
        <option value="admin" {{ $user->user_type == 'admin' ? 'selected' : '' }}>Admin</option>
    </select>

    <!-- Submit Button -->
    <x-primary-button>{{ __('Update User') }}</x-primary-button>
</form>