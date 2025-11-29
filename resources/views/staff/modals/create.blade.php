<!-- Staff Creation Modal Component -->
<div id="staffModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col modal-content">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-violet-500 to-purple-600 p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold">Add New Staff Member</h3>
                <button onclick="closeStaffModal()" class="text-white/80 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Progress Indicator --}}
            <div class="flex items-center justify-between" id="progressBar">
                <div class="flex items-center flex-1">
                    <div class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2">
                        <span class="text-sm font-bold">1</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 rounded progress-line"></div>
                </div>
                <div class="flex items-center flex-1">
                    <div class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2">
                        <span class="text-sm font-bold">2</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 rounded progress-line"></div>
                </div>
                <div class="flex items-center flex-1">
                    <div class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2">
                        <span class="text-sm font-bold">3</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 rounded progress-line"></div>
                </div>
                <div class="flex items-center">
                    <div class="step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2">
                        <span class="text-sm font-bold">4</span>
                    </div>
                </div>
            </div>

            {{-- Step Labels --}}
            <div class="flex justify-between mt-2 text-sm">
                <span id="label1" class="font-semibold">Photo</span>
                <span id="label2" class="opacity-70">Account</span>
                <span id="label3" class="opacity-70">Personal</span>
                <span id="label4" class="opacity-70">Employment</span>
            </div>
        </div>

        {{-- Form --}}
        <form id="addStaffForm" method="POST" action="{{ route('admin.staff.store') }}" enctype="multipart/form-data"
            class="flex-1 overflow-y-auto">
            @csrf

            {{-- Step 1: Profile Photo --}}
            <div class="form-step p-8" data-step="1">
                <h4 class="text-xl font-bold text-gray-900 mb-6">Profile Photo</h4>
                <div class="flex flex-col items-center gap-6">
                    <div id="avatarPreview"
                        class="w-32 h-32 rounded-full bg-gradient-to-br from-violet-100 to-purple-100 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                        <svg class="w-16 h-16 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <label for="avatar" class="cursor-pointer w-full">
                        <div
                            class="flex items-center justify-center px-6 py-8 border-2 border-dashed border-gray-300 rounded-lg hover:border-violet-400 transition bg-gray-50 hover:bg-violet-50">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-3 text-sm text-gray-600">
                                    <span class="font-semibold text-violet-600">Click to upload</span> or drag and
                                    drop
                                </p>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG up to 10MB</p>
                            </div>
                        </div>
                    </label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden"
                        onchange="previewAvatar(event)" />
                </div>
            </div>

            {{-- Step 2: Account Information --}}
            <div class="form-step hidden p-8" data-step="2">
                <h4 class="text-xl font-bold text-gray-900 mb-6">Account Information</h4>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="name"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                placeholder="Enter full name" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span
                                    class="text-rose-500">*</span></label>
                            <input type="email" name="email"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                placeholder="email@example.com" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="username"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                placeholder="username" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password <span
                                    class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" id="staffPassword" name="password"
                                    class="block w-full px-4 py-3 pr-12 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="••••••••" />

                                <!-- Toggle Password Visibility -->
                                <button type="button" onclick="toggleStaffPassword()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-violet-600 transition">
                                    <svg id="staffEyeIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="staffEyeSlashIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3: Personal Information --}}
            <div class="form-step hidden p-8" data-step="3">
                <h4 class="text-xl font-bold text-gray-900 mb-6">Personal Information</h4>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                            <input type="text" name="contact_number"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                placeholder="+63 912 345 6789" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select name="gender"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" name="address"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                placeholder="Street, City, Province" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 4: Employment Details --}}
            <div class="form-step hidden p-8" data-step="4">
                <h4 class="text-xl font-bold text-gray-900 mb-6">Employment Details</h4>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role / Position</label>
                            <input type="text" name="role_type"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                placeholder="e.g., Event Coordinator, Photographer" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Default Rate (per
                                event)</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                <input type="number" name="rate" step="0.01" min="0"
                                    class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition"
                                    placeholder="0.00" />
                            </div>
                            <input type="hidden" name="rate_type" value="per_event">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Remarks / Notes</label>
                            <textarea name="remarks" rows="3"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition resize-none"
                                placeholder="Any additional notes or remarks..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="inline-flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked
                                    class="w-5 h-5 rounded border-gray-300 text-violet-600 focus:ring-2 focus:ring-violet-200">
                                <span class="text-sm font-medium text-gray-700">
                                    Active Staff Member
                                    <span class="block text-xs text-gray-500 mt-0.5">This staff member can be
                                        assigned to events</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Footer Navigation --}}
        <div class="border-t border-gray-200 p-6 bg-gray-50">
            <div class="flex justify-between items-center">
                <button type="button" id="prevBtn" onclick="previousStep()"
                    class="hidden px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition">
                    Previous
                </button>
                <div></div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeStaffModal()"
                        class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition">
                        Cancel
                    </button>
                    <button type="button" id="nextBtn" onclick="nextStep()"
                        class="px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-600 hover:to-purple-700 transition">
                        Next
                    </button>
                    <button type="submit" form="addStaffForm" id="submitBtn"
                        class="hidden px-6 py-3 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-600 hover:to-purple-700 transition">
                        Create Staff Member
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility for staff modal
    function toggleStaffPassword() {
        const passwordInput = document.getElementById('staffPassword');
        const eyeIcon = document.getElementById('staffEyeIcon');
        const eyeSlashIcon = document.getElementById('staffEyeSlashIcon');
        
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        eyeIcon.classList.toggle('hidden');
        eyeSlashIcon.classList.toggle('hidden');
    }
</script>