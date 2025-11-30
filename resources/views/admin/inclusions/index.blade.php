<x-admin.layouts.management>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Inclusions</h3>
                <p class="text-gray-500 mt-1">Manage event package inclusions and services</p>
            </div>
            <button type="button" onclick="openInclusionModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Inclusion
            </button>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
            $totalInclusions = $inclusions->total();
            $activeInclusions = \App\Models\Inclusion::where('is_active', true)->count();
            $inactiveInclusions = \App\Models\Inclusion::where('is_active', false)->count();
            $avgPrice = \App\Models\Inclusion::where('is_active', true)->avg('price') ?? 0;
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Total</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalInclusions }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-emerald-50 rounded-xl shadow-sm border border-emerald-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-emerald-700 uppercase tracking-wide">Active</div>
                        <div class="text-2xl font-bold text-emerald-800">{{ $activeInclusions }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-700 uppercase tracking-wide">Inactive</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $inactiveInclusions }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Avg Price</div>
                        <div class="text-2xl font-bold text-gray-900">₱{{ number_format($avgPrice, 0) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" class="flex gap-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="q" value="{{ $q }}"
                        placeholder="Search by name, category, or contact person..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-200 focus:border-slate-400">
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-slate-700 text-white font-medium rounded-lg hover:bg-slate-800 transition">
                    Search
                </button>
                @if($q)
                <a href="{{ route('admin.management.inclusions.index') }}"
                    class="px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Clear
                </a>
                @endif
            </form>
        </div>

        {{-- Inclusions Table --}}
        @if($inclusions->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <p class="text-gray-500 font-medium mb-1">
                @if($q)
                No inclusions found matching "{{ $q }}"
                @else
                No inclusions yet
                @endif
            </p>
            <p class="text-gray-400 text-sm">
                @if($q)
                Try a different search term
                @else
                Create your first inclusion to get started
                @endif
            </p>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="overflow-x-auto rounded-xl">
                <table class="w-full min-w-[900px]">
                    <thead class="bg-slate-50 border-b border-gray-200">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Inclusion
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Category
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Price
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Contact
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($inclusions as $i)
                        @php
                        $imageUrl = $i->image_url;
                        $imageAlt = $i->name;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Name & Image --}}
                            <td class="px-6 py-4 max-w-[250px]">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                                        <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}"
                                            class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-semibold text-gray-900 break-words">{{ $i->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="px-3 py-4">
                                @if($i->category)
                                <span
                                    class="inline-block px-2.5 py-1 bg-sky-100 text-sky-700 rounded-full text-xs font-medium">
                                    {{ $i->category }}
                                </span>
                                @else
                                <span class="text-gray-400 text-xs italic">No category</span>
                                @endif
                            </td>

                            {{-- Price --}}
                            <td class="px-3 py-4">
                                <div class="text-sm font-bold text-gray-900">₱{{ number_format($i->price, 0) }}</div>
                            </td>

                            {{-- Contact --}}
                            <td class="px-3 py-4">
                                <div class="space-y-1">
                                    @if($i->contact_person)
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="truncate max-w-[150px]">{{ $i->contact_person }}</span>
                                    </div>
                                    @endif
                                    @if($i->contact_email)
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate max-w-[150px]">{{ $i->contact_email }}</span>
                                    </div>
                                    @endif
                                    @if($i->contact_phone)
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span>{{ $i->contact_phone }}</span>
                                    </div>
                                    @endif
                                    @if(!$i->contact_person && !$i->contact_email && !$i->contact_phone)
                                    <span class="text-gray-400 text-xs italic">No contact</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-3 py-4">
                                @if($i->is_active)
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                    Inactive
                                </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col items-center justify-end gap-2">
                                    <a href="{{ route('admin.management.inclusions.show', $i) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-slate-700 bg-white border border-gray-200 rounded-lg hover:bg-slate-50 transition whitespace-nowrap"
                                        title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>

                                    <button type="button" onclick="openEditInclusionModal({{ json_encode([
                                            'id' => $i->id,
                                            'name' => $i->name,
                                            'category' => $i->category instanceof \App\Enums\InclusionCategory ? $i->category->value : $i->category,
                                            'package_type' => $i->package_type,
                                            'price' => $i->price,
                                            'contact_person' => $i->contact_person,
                                            'contact_email' => $i->contact_email,
                                            'contact_phone' => $i->contact_phone,
                                            'notes' => $i->notes,
                                            'is_active' => $i->is_active,
                                            'image_url' => $i->image_url,
                                            'update_url' => route('admin.management.inclusions.update', $i)
                                        ]) }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-violet-700 bg-violet-100 rounded-lg hover:bg-violet-200 transition whitespace-nowrap"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($inclusions->hasPages())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-4">
            {{ $inclusions->links() }}
        </div>
        @endif
        @endif
    </div>

    {{-- Include Modals --}}
    @include('admin.inclusions.modals.create')
    @include('admin.inclusions.modals.edit')

    {{-- JavaScript for Modal Handling --}}
    <script>
        // ============================================
        // CREATE MODAL FUNCTIONS
        // ============================================
        let createCurrentStep = 1;
        const createTotalSteps = 3;

        function openInclusionModal() {
            document.getElementById('createInclusionModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            resetCreateModal();
        }

        function closeInclusionModal() {
            document.getElementById('createInclusionModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetCreateModal();
        }

        function resetCreateModal() {
            createCurrentStep = 1;
            updateCreateStepDisplay();
            document.getElementById('createInclusionForm').reset();
            
            // Reset image preview
            document.getElementById('createImagePreview').classList.add('hidden');
            document.getElementById('createImagePreview').src = '';
            document.getElementById('createImagePlaceholder').classList.remove('hidden');
            document.getElementById('createRemoveImageBtn').classList.add('hidden');
            
            // Reset checkbox to checked
            document.getElementById('createIsActive').checked = true;
        }

        function createNextStep() {
            if (createCurrentStep < createTotalSteps) {
                // Validate current step before proceeding
                if (createCurrentStep === 2) {
                    const name = document.getElementById('createName').value.trim();
                    const category = document.getElementById('createCategory').value;
                    
                    if (!name) {
                        alert('Please enter a service name.');
                        document.getElementById('createName').focus();
                        return;
                    }
                    if (!category) {
                        alert('Please select a category.');
                        document.getElementById('createCategory').focus();
                        return;
                    }
                }
                
                createCurrentStep++;
                updateCreateStepDisplay();
            }
        }

        function createPrevStep() {
            if (createCurrentStep > 1) {
                createCurrentStep--;
                updateCreateStepDisplay();
            }
        }

        function updateCreateStepDisplay() {
            // Hide all steps
            for (let i = 1; i <= createTotalSteps; i++) {
                document.getElementById(`createStep${i}`).classList.add('hidden');
                document.getElementById(`createStep${i}Indicator`).classList.remove('bg-white', 'text-sky-600');
                document.getElementById(`createStep${i}Indicator`).classList.add('bg-white/30', 'text-white');
            }
            
            // Show current step
            document.getElementById(`createStep${createCurrentStep}`).classList.remove('hidden');
            
            // Update indicators for completed and current steps
            for (let i = 1; i <= createCurrentStep; i++) {
                document.getElementById(`createStep${i}Indicator`).classList.remove('bg-white/30', 'text-white');
                document.getElementById(`createStep${i}Indicator`).classList.add('bg-white', 'text-sky-600');
            }
            
            // Update buttons
            document.getElementById('createPrevBtn').classList.toggle('hidden', createCurrentStep === 1);
            document.getElementById('createCancelBtn').classList.toggle('hidden', createCurrentStep !== 1);
            document.getElementById('createNextBtn').classList.toggle('hidden', createCurrentStep === createTotalSteps);
            document.getElementById('createSubmitBtn').classList.toggle('hidden', createCurrentStep !== createTotalSteps);
        }

        function previewCreateImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('createImagePreview').src = e.target.result;
                    document.getElementById('createImagePreview').classList.remove('hidden');
                    document.getElementById('createImagePlaceholder').classList.add('hidden');
                    document.getElementById('createRemoveImageBtn').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeCreateImage(event) {
            event.stopPropagation();
            document.getElementById('createInclusionImage').value = '';
            document.getElementById('createImagePreview').src = '';
            document.getElementById('createImagePreview').classList.add('hidden');
            document.getElementById('createImagePlaceholder').classList.remove('hidden');
            document.getElementById('createRemoveImageBtn').classList.add('hidden');
        }

        // ============================================
        // EDIT MODAL FUNCTIONS
        // ============================================
        let editCurrentStep = 1;
        const editTotalSteps = 3;
        let editOriginalImageUrl = '';

        function openEditInclusionModal(data) {
            document.getElementById('editInclusionModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reset to step 1
            editCurrentStep = 1;
            updateEditStepDisplay();
            
            // Set form action URL
            document.getElementById('editInclusionForm').action = data.update_url;
            
            // Populate form fields
            document.getElementById('editName').value = data.name || '';
            document.getElementById('editCategory').value = data.category || '';
            document.getElementById('editPackageType').value = data.package_type || '';
            document.getElementById('editPrice').value = data.price || 0;
            document.getElementById('editContactPerson').value = data.contact_person || '';
            document.getElementById('editContactEmail').value = data.contact_email || '';
            document.getElementById('editContactPhone').value = data.contact_phone || '';
            document.getElementById('editNotes').value = data.notes || '';
            document.getElementById('editIsActive').checked = data.is_active == 1;
            
            // Handle image preview
            editOriginalImageUrl = data.image_url || '';
            if (editOriginalImageUrl && !editOriginalImageUrl.includes('ui-avatars.com')) {
                document.getElementById('editImagePreview').src = editOriginalImageUrl;
                document.getElementById('editImagePreview').classList.remove('hidden');
                document.getElementById('editImagePlaceholder').classList.add('hidden');
                document.getElementById('editRemoveImageBtn').classList.remove('hidden');
            } else {
                document.getElementById('editImagePreview').classList.add('hidden');
                document.getElementById('editImagePlaceholder').classList.remove('hidden');
                document.getElementById('editRemoveImageBtn').classList.add('hidden');
            }
            
            // Clear file input
            document.getElementById('editInclusionImage').value = '';
        }

        function closeEditInclusionModal() {
            document.getElementById('editInclusionModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetEditModal();
        }

        function resetEditModal() {
            editCurrentStep = 1;
            updateEditStepDisplay();
            document.getElementById('editInclusionForm').reset();
            
            // Reset image preview
            document.getElementById('editImagePreview').classList.add('hidden');
            document.getElementById('editImagePreview').src = '';
            document.getElementById('editImagePlaceholder').classList.remove('hidden');
            document.getElementById('editRemoveImageBtn').classList.add('hidden');
            editOriginalImageUrl = '';
        }

        function editNextStep() {
            if (editCurrentStep < editTotalSteps) {
                // Validate current step before proceeding
                if (editCurrentStep === 2) {
                    const name = document.getElementById('editName').value.trim();
                    const category = document.getElementById('editCategory').value;
                    
                    if (!name) {
                        alert('Please enter a service name.');
                        document.getElementById('editName').focus();
                        return;
                    }
                    if (!category) {
                        alert('Please select a category.');
                        document.getElementById('editCategory').focus();
                        return;
                    }
                }
                
                editCurrentStep++;
                updateEditStepDisplay();
            }
        }

        function editPrevStep() {
            if (editCurrentStep > 1) {
                editCurrentStep--;
                updateEditStepDisplay();
            }
        }

        function updateEditStepDisplay() {
            // Hide all steps
            for (let i = 1; i <= editTotalSteps; i++) {
                document.getElementById(`editStep${i}`).classList.add('hidden');
                document.getElementById(`editStep${i}Indicator`).classList.remove('bg-white', 'text-blue-600');
                document.getElementById(`editStep${i}Indicator`).classList.add('bg-white/30', 'text-white');
            }
            
            // Show current step
            document.getElementById(`editStep${editCurrentStep}`).classList.remove('hidden');
            
            // Update indicators for completed and current steps
            for (let i = 1; i <= editCurrentStep; i++) {
                document.getElementById(`editStep${i}Indicator`).classList.remove('bg-white/30', 'text-white');
                document.getElementById(`editStep${i}Indicator`).classList.add('bg-white', 'text-blue-600');
            }
            
            // Update buttons
            document.getElementById('editPrevBtn').classList.toggle('hidden', editCurrentStep === 1);
            document.getElementById('editCancelBtn').classList.toggle('hidden', editCurrentStep !== 1);
            document.getElementById('editNextBtn').classList.toggle('hidden', editCurrentStep === editTotalSteps);
            document.getElementById('editSubmitBtn').classList.toggle('hidden', editCurrentStep !== editTotalSteps);
        }

        function previewEditImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editImagePreview').src = e.target.result;
                    document.getElementById('editImagePreview').classList.remove('hidden');
                    document.getElementById('editImagePlaceholder').classList.add('hidden');
                    document.getElementById('editRemoveImageBtn').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeEditImage(event) {
            event.stopPropagation();
            document.getElementById('editInclusionImage').value = '';
            
            // If there was an original image, show it back
            if (editOriginalImageUrl && !editOriginalImageUrl.includes('ui-avatars.com')) {
                document.getElementById('editImagePreview').src = editOriginalImageUrl;
            } else {
                document.getElementById('editImagePreview').src = '';
                document.getElementById('editImagePreview').classList.add('hidden');
                document.getElementById('editImagePlaceholder').classList.remove('hidden');
                document.getElementById('editRemoveImageBtn').classList.add('hidden');
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('createInclusionModal').classList.contains('hidden')) {
                    closeInclusionModal();
                }
                if (!document.getElementById('editInclusionModal').classList.contains('hidden')) {
                    closeEditInclusionModal();
                }
            }
        });
    </script>
</x-admin.layouts.management>