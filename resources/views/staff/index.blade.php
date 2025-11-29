<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Staff Management') }}
            </h2>
            <button onclick="openStaffModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-700 hover:to-purple-700 transition shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Staff
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters & Search -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.staff.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by name, email, or role..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                                <option value="">All Roles</option>
                                <option value="photographer" {{ request('role')=='photographer' ? 'selected' : '' }}>
                                    Photographer</option>
                                <option value="videographer" {{ request('role')=='videographer' ? 'selected' : '' }}>
                                    Videographer</option>
                                <option value="coordinator" {{ request('role')=='coordinator' ? 'selected' : '' }}>
                                    Coordinator</option>
                                <option value="makeup_artist" {{ request('role')=='makeup_artist' ? 'selected' : '' }}>
                                    Makeup Artist</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.staff.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Clear Filters
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Staff Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Staff Member
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rate
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($staffs as $staff)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($staff->user && $staff->user->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ asset('storage/' . $staff->user->profile_photo_path) }}"
                                                alt="{{ $staff->user->name }}">
                                            @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-violet-400 to-purple-400 flex items-center justify-center text-white font-semibold">
                                                {{ substr($staff->user->name ?? 'S', 0, 1) }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $staff->user->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $staff->user->email ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $staff->role_type ?? 'Staff')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $staff->contact_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₱{{ number_format($staff->rate ?? 0, 2) }}
                                    <span class="text-xs text-gray-500">/ {{ $staff->rate_type ?? 'event' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($staff->is_active)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                    @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('admin.staff.show', $staff) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-violet-100 text-violet-700 rounded-lg hover:bg-violet-200 transition font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>

                                        <!-- Edit Button -->
                                        <button onclick="openEditStaffModal({{ $staff->id }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>

                                        <!-- Delete Button -->
                                        <button
                                            onclick="openDeleteModal({{ $staff->id }}, '{{ $staff->user->name ?? 'N/A' }}')"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">No staff members found</h3>
                                        <p class="text-gray-500 mb-4">Get started by adding your first staff member.
                                        </p>
                                        <button onclick="openStaffModal()"
                                            class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition">
                                            Add Staff Member
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($staffs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $staffs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Create Staff Modal -->
    @include('staff.modals.create')

    <!-- Include Edit Staff Modal -->
    @include('staff.modals.edit')

    <!-- Delete Confirmation Modal -->
    <div id="deleteStaffModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all"
            style="animation: scaleIn 0.3s ease-out;">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-red-500 to-rose-600 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Confirm Deletion</h3>
                        <p class="text-white/80 text-sm mt-1">This action cannot be undone</p>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-gray-700 mb-3">Are you sure you want to delete this staff member?</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600 mb-2">Staff Member:</p>
                    <p class="font-semibold text-gray-900" id="deleteStaffName">Loading...</p>
                </div>

                <form id="deleteStaffForm" method="POST" action="">
                    @csrf
                    @method('DELETE')

                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-rose-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-rose-700 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                            Delete Staff
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <!-- Modal Scripts -->
    <script>
        // CREATE MODAL SCRIPTS (existing)
        let currentStep = 1;
        const totalSteps = 4;

        function openStaffModal() {
            document.getElementById('staffModal').classList.remove('hidden');
            currentStep = 1;
            showStep(currentStep);
        }

        function closeStaffModal() {
            document.getElementById('staffModal').classList.add('hidden');
            document.getElementById('addStaffForm').reset();
            document.getElementById('avatarPreview').innerHTML = `
                <svg class="w-16 h-16 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            `;
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function showStep(step) {
            document.querySelectorAll('.form-step').forEach((el, index) => {
                el.classList.toggle('hidden', index + 1 !== step);
            });

            document.getElementById('prevBtn').classList.toggle('hidden', step === 1);
            document.getElementById('nextBtn').classList.toggle('hidden', step === totalSteps);
            document.getElementById('submitBtn').classList.toggle('hidden', step !== totalSteps);

            updateProgressBar(step);
        }

        function updateProgressBar(step) {
            const indicators = document.querySelectorAll('.step-indicator');
            const lines = document.querySelectorAll('.progress-line');
            const labels = [
                document.getElementById('label1'),
                document.getElementById('label2'),
                document.getElementById('label3'),
                document.getElementById('label4')
            ];

            indicators.forEach((indicator, index) => {
                if (index < step) {
                    indicator.classList.add('bg-white', 'text-violet-600', 'border-white');
                    indicator.classList.remove('border-white/50', 'text-white');
                } else {
                    indicator.classList.remove('bg-white', 'text-violet-600', 'border-white');
                    indicator.classList.add('border-white/50', 'text-white');
                }
            });

            lines.forEach((line, index) => {
                if (index < step - 1) {
                    line.classList.add('bg-white');
                    line.classList.remove('bg-white/30');
                } else {
                    line.classList.remove('bg-white');
                    line.classList.add('bg-white/30');
                }
            });

            labels.forEach((label, index) => {
                if (index < step) {
                    label.classList.add('font-semibold', 'opacity-100');
                    label.classList.remove('opacity-70');
                } else {
                    label.classList.remove('font-semibold', 'opacity-100');
                    label.classList.add('opacity-70');
                }
            });
        }

        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').innerHTML = 
                        `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Avatar Preview">`;
                };
                reader.readAsDataURL(file);
            }
        }

        // EDIT MODAL SCRIPTS (new)
        let editCurrentStep = 1;

        function openEditStaffModal(staffId) {
            // Fetch staff data
            fetch(`/admin/staff/${staffId}/edit-data`)
                .then(response => response.json())
                .then(data => {
                    // Set form action
                    document.getElementById('editStaffForm').action = `/admin/staff/${staffId}`;
                    
                    // Populate form fields
                    document.getElementById('edit_name').value = data.user.name || '';
                    document.getElementById('edit_email').value = data.user.email || '';
                    document.getElementById('edit_username').value = data.user.username || '';
                    document.getElementById('edit_contact_number').value = data.contact_number || '';
                    document.getElementById('edit_gender').value = data.gender || '';
                    document.getElementById('edit_address').value = data.address || '';
                    document.getElementById('edit_role_type').value = data.role_type || '';
                    document.getElementById('edit_rate').value = data.rate || '';
                    document.getElementById('edit_remarks').value = data.remarks || '';
                    document.getElementById('edit_is_active').checked = data.is_active == 1;
                    
                    // Set avatar preview
                    if (data.user.profile_photo_path) {
                        document.getElementById('editAvatarPreview').innerHTML = 
                            `<img src="/storage/${data.user.profile_photo_path}" class="w-full h-full object-cover" alt="Current Avatar">`;
                    } else {
                        document.getElementById('editAvatarPreview').innerHTML = `
                            <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        `;
                    }
                    
                    // Show modal
                    document.getElementById('editStaffModal').classList.remove('hidden');
                    editCurrentStep = 1;
                    showEditStep(editCurrentStep);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load staff data');
                });
        }

        function closeEditStaffModal() {
            document.getElementById('editStaffModal').classList.add('hidden');
            document.getElementById('editStaffForm').reset();
        }

        function nextEditStep() {
            if (editCurrentStep < totalSteps) {
                editCurrentStep++;
                showEditStep(editCurrentStep);
            }
        }

        function previousEditStep() {
            if (editCurrentStep > 1) {
                editCurrentStep--;
                showEditStep(editCurrentStep);
            }
        }

        function showEditStep(step) {
            document.querySelectorAll('.edit-form-step').forEach((el, index) => {
                el.classList.toggle('hidden', index + 1 !== step);
            });

            document.getElementById('editPrevBtn').classList.toggle('hidden', step === 1);
            document.getElementById('editNextBtn').classList.toggle('hidden', step === totalSteps);
            document.getElementById('editSubmitBtn').classList.toggle('hidden', step !== totalSteps);

            updateEditProgressBar(step);
        }

        function updateEditProgressBar(step) {
            const indicators = document.querySelectorAll('.edit-step-indicator');
            const lines = document.querySelectorAll('.edit-progress-line');
            const labels = [
                document.getElementById('editLabel1'),
                document.getElementById('editLabel2'),
                document.getElementById('editLabel3'),
                document.getElementById('editLabel4')
            ];

            indicators.forEach((indicator, index) => {
                if (index < step) {
                    indicator.classList.add('bg-white', 'text-blue-600', 'border-white');
                    indicator.classList.remove('border-white/50', 'text-white');
                } else {
                    indicator.classList.remove('bg-white', 'text-blue-600', 'border-white');
                    indicator.classList.add('border-white/50', 'text-white');
                }
            });

            lines.forEach((line, index) => {
                if (index < step - 1) {
                    line.classList.add('bg-white');
                    line.classList.remove('bg-white/30');
                } else {
                    line.classList.remove('bg-white');
                    line.classList.add('bg-white/30');
                }
            });

            labels.forEach((label, index) => {
                if (index < step) {
                    label.classList.add('font-semibold', 'opacity-100');
                    label.classList.remove('opacity-70');
                } else {
                    label.classList.remove('font-semibold', 'opacity-100');
                    label.classList.add('opacity-70');
                }
            });
        }
        // DELETE MODAL SCRIPTS
function openDeleteModal(staffId, staffName) {
    document.getElementById('deleteStaffName').textContent = staffName;
    document.getElementById('deleteStaffForm').action = `/admin/staff/${staffId}`;
    document.getElementById('deleteStaffModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteStaffModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const deleteModal = document.getElementById('deleteStaffModal');
    if (e.target === deleteModal) {
        closeDeleteModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
    </script>

    <!-- Custom Styles -->
    <style>
        .step-indicator {
            transition: all 0.3s ease;
        }

        .progress-line {
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .modal-content {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>