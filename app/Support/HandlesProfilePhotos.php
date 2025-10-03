<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesProfilePhotos
{
    protected function storeProfilePhoto(?UploadedFile $file, ?string $existingPath = null): ?string
    {
        if (!$file) return $existingPath;

        // delete old if exists
        if ($existingPath && Storage::disk('public')->exists($existingPath)) {
            Storage::disk('public')->delete($existingPath);
        }

        // store new
        return $file->store('avatars', 'public');
    }
}
