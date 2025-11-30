{{-- Create Inclusion Modal --}}
<div id="createInclusionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeInclusionModal()"></div>

    {{-- Modal Content --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl transform transition-all">
            {{-- Header with gradient --}}
            <div class="bg-gradient-to-r from-sky-600 to-cyan-600 rounded-t-2xl px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">New Inclusion</h3>
                        <p class="text-sky-100 text-sm mt-0.5">Add a new service or item</p>
                    </div>
                    <button type="button" onclick="closeInclusionModal()"
                        class="text-white/80 hover:text-white transition p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Step Indicators --}}
                <div class="flex items-center justify-center gap-2 mt-4">
                    <div class="flex items-center gap-2">
                        <div id="createStep1Indicator"
                            class="w-8 h-8 rounded-full bg-white text-sky-600 flex items-center justify-center text-sm font-bold">
                            1</div>
                        <span class="text-white text-xs font-medium hidden sm:inline">Image</span>
                    </div>
                    <div class="w-8 h-0.5 bg-white/30"></div>
                    <div class="flex items-center gap-2">
                        <div id="createStep2Indicator"
                            class="w-8 h-8 rounded-full bg-white/30 text-white flex items-center justify-center text-sm font-bold">
                            2</div>
                        <span class="text-white/70 text-xs font-medium hidden sm:inline">Basic Info</span>
                    </div>
                    <div class="w-8 h-0.5 bg-white/30"></div>
                    <div class="flex items-center gap-2">
                        <div id="createStep3Indicator"
                            class="w-8 h-8 rounded-full bg-white/30 text-white flex items-center justify-center text-sm font-bold">
                            3</div>
                        <span class="text-white/70 text-xs font-medium hidden sm:inline">Contact & Details</span>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form id="createInclusionForm" method="POST" action="{{ route('admin.management.inclusions.store') }}"
                enctype="multipart/form-data">
                @csrf

                {{-- Step 1: Image Upload --}}
                <div id="createStep1" class="p-6">
                    <div class="text-center mb-6">
                        <h4 class="text-lg font-semibold text-gray-800">Service Image</h4>
                        <p class="text-sm text-gray-500 mt-1">Upload an image for this inclusion (optional)</p>
                    </div>

                    <div class="flex justify-center">
                        <div class="relative">
                            {{-- Image Preview Container --}}
                            <div id="createImagePreviewWrapper"
                                class="w-48 h-48 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden cursor-pointer hover:border-sky-400 hover:bg-sky-50 transition-all"
                                onclick="document.getElementById('createInclusionImage').click()">
                                <div id="createImagePlaceholder" class="text-center p-4">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-400">Click to upload</p>
                                    <p class="text-xs text-gray-400">JPG, PNG, GIF (max 2MB)</p>
                                </div>
                                <img id="createImagePreview" class="hidden w-full h-full object-cover" />
                            </div>

                            {{-- Remove Image Button --}}
                            <button type="button" id="createRemoveImageBtn"
                                class="hidden absolute -top-2 -right-2 w-7 h-7 bg-rose-500 text-white rounded-full shadow-lg hover:bg-rose-600 transition flex items-center justify-center"
                                onclick="removeCreateImage(event)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <input type="file" id="createInclusionImage" name="image" accept=".jpg,.jpeg,.png,.webp,.gif"
                        class="hidden" onchange="previewCreateImage(this)">

                    <p class="text-center text-xs text-gray-400 mt-4">You can skip this step if no image is needed</p>
                </div>

                {{-- Step 2: Basic Information --}}
                <div id="createStep2" class="hidden p-6">
                    <div class="text-center mb-6">
                        <h4 class="text-lg font-semibold text-gray-800">Basic Information</h4>
                        <p class="text-sm text-gray-500 mt-1">Enter the inclusion details</p>
                    </div>

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Service Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" id="createName" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 transition"
                                placeholder="e.g., Professional Photography">
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Category <span class="text-rose-500">*</span>
                            </label>
                            <select name="category" id="createCategory" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 transition">
                                <option value="">Select category</option>
                                @foreach(\App\Enums\InclusionCategory::cases() as $category)
                                <option value="{{ $category->value }}">{{ $category->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Package Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Package Type
                                    <span class="text-gray-400 font-normal text-xs">(Optional)</span>
                                </label>
                                <select name="package_type" id="createPackageType"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 transition">
                                    <option value="">All Package Types</option>
                                    @foreach(\App\Models\Package::getDistinctTypes() as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Price --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Price <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</span>
                                    <input type="number" step="0.01" min="0" name="price" id="createPrice" value="0"
                                        required
                                        class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 transition"
                                        placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Contact & Details --}}
                <div id="createStep3" class="hidden p-6">
                    <div class="text-center mb-6">
                        <h4 class="text-lg font-semibold text-gray-800">Contact & Details</h4>
                        <p class="text-sm text-gray-500 mt-1">Add contact information and notes</p>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Contact Person --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                                <input type="text" name="contact_person" id="createContactPerson"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 transition"
                                    placeholder="John Doe">
                            </div>

                            {{-- Contact Phone --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="contact_phone" id="createContactPhone"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 transition"
                                    placeholder="+63 912 345 6789">
                            </div>
                        </div>

                        {{-- Contact Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="contact_email" id="createContactEmail"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 transition"
                                placeholder="contact@example.com">
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes & Description</label>
                            <textarea name="notes" id="createNotes" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 transition resize-none"
                                placeholder="Add any additional notes or description..."></textarea>
                        </div>

                        {{-- Active Status --}}
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <input id="createIsActive" name="is_active" type="checkbox" value="1" checked
                                class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-2 focus:ring-emerald-200">
                            <div class="flex-1">
                                <label for="createIsActive"
                                    class="text-sm font-medium text-gray-900 cursor-pointer">Active Service</label>
                                <p class="text-xs text-gray-500 mt-0.5">Make this service available immediately</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer with Navigation --}}
                <div
                    class="bg-gray-50 rounded-b-2xl px-6 py-4 flex items-center justify-between border-t border-gray-200">
                    <button type="button" id="createPrevBtn" onclick="createPrevStep()"
                        class="hidden px-4 py-2 text-gray-600 font-medium hover:text-gray-800 transition">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </span>
                    </button>
                    <button type="button" onclick="closeInclusionModal()"
                        class="px-4 py-2 text-gray-600 font-medium hover:text-gray-800 transition" id="createCancelBtn">
                        Cancel
                    </button>

                    <div class="flex items-center gap-3">
                        <button type="button" id="createNextBtn" onclick="createNextStep()"
                            class="px-5 py-2 bg-sky-600 text-white font-medium rounded-lg hover:bg-sky-700 transition">
                            Next
                            <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <button type="submit" id="createSubmitBtn"
                            class="hidden px-5 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Create Inclusion
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>