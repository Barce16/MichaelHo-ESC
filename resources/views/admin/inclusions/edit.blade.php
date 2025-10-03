<x-admin.layouts.management>
    <form method="POST"
        action="{{ isset($inclusion) ? route('admin.management.inclusions.update',$inclusion) : route('admin.management.inclusions.store') }}"
        enctype="multipart/form-data" class="bg-white rounded shadow p-6 space-y-4">
        @csrf
        @isset($inclusion) @method('PUT') @endisset

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label>Name</x-input-label>
                <x-text-input name="name" class="w-full" value="{{ old('name', $inclusion->name ?? '') }}" />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                <select name="category" id="category" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Category</option>
                    @foreach(\App\Enums\InclusionCategory::cases() as $category)
                    <option value="{{ $category->value }}" {{ old('category', $inclusion->category?->value) ==
                        $category->value ? 'selected' : '' }}>
                        {{ $category->label() }}
                    </option>
                    @endforeach
                </select>
                @error('category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>

                @if($inclusion->image)
                <div class="mb-2" id="currentImageWrapper">
                    <img src="{{ asset('storage/' . $inclusion->image) }}" alt="{{ $inclusion->name }}"
                        class="h-32 w-32 object-cover rounded" id="currentImage">
                    <p class="text-xs text-gray-500 mt-1">Current image</p>
                </div>
                @endif

                <!-- File Input -->
                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 
               file:mr-4 file:py-2 file:px-4 file:rounded-md 
               file:border-0 file:text-sm file:font-semibold 
               file:bg-indigo-50 file:text-indigo-700 
               hover:file:bg-indigo-100">

                <!-- Preview (for new uploads) -->
                <div class="mt-3">
                    <img id="imagePreview" class="hidden h-32 w-32 object-cover rounded border" />
                    <p id="previewLabel" class="hidden text-xs text-gray-500 mt-1">New preview</p>
                </div>

                @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label for="contact_person" value="Contact Person" />
                <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full"
                    value="{{ old('contact_person', $inclusion->contact_person ?? '') }}" />
            </div>

            <div>
                <x-input-label for="contact_email" value="Contact Email" />
                <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full"
                    value="{{ old('contact_email', $inclusion->contact_email ?? '') }}" />
            </div>

            <div>
                <x-input-label for="contact_phone" value="Contact Phone" />
                <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full"
                    value="{{ old('contact_phone', $inclusion->contact_phone ?? '') }}" />
            </div>

            <div>
                <x-input-label>Price</x-input-label>
                <x-text-input type="number" step="0.01" min="0" name="price" class="w-full"
                    value="{{ old('price', isset($inclusion) ? $inclusion->price : 0) }}" />
                <x-input-error :messages="$errors->get('price')" />
            </div>

            <div class="col-span-2" x-data="{
                resize(el){ el.style.height = 'auto'; el.style.overflow = 'hidden'; el.style.height = el.scrollHeight + 'px'; }
            }">
                <x-input-label for="notes" value="Notes" />
                <textarea id="notes" name="notes" rows="1"
                    class="mt-1 w-full border rounded px-3 py-2 resize-none overflow-hidden" x-init="
                    resize($el);
                    $nextTick(() => resize($el));
                    setTimeout(() => resize($el), 0);
                " @input="resize($event.target)">{{ old('notes', $inclusion->notes ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
            </div>



            <div class="flex items-end">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $inclusion->is_active ??
                    true))
                    class="rounded border-gray-300">
                    <span>Active</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.management.inclusions.index') }}" class="px-3 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-gray-800 text-white rounded">
                {{ isset($inclusion) ? 'Save Changes' : 'Create Inclusion' }}
            </button>
        </div>
    </form>
    <script>
        const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewLabel = document.getElementById('previewLabel');
    const currentImageWrapper = document.getElementById('currentImageWrapper');

    imageInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                // Hide current image if it exists
                if (currentImageWrapper) {
                    currentImageWrapper.classList.add('hidden');
                }

                // Show new preview
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                previewLabel.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            // If no file chosen, reset preview
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            previewLabel.classList.add('hidden');

            // Show back the current image if exists
            if (currentImageWrapper) {
                currentImageWrapper.classList.remove('hidden');
            }
        }
    });
    </script>
</x-admin.layouts.management>