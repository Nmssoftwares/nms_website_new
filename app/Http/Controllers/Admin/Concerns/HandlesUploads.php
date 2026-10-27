<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Shared image upload helpers for the admin controllers.
 */
trait HandlesUploads
{
    /**
     * If the request has an uploaded file in $field: save it in the public disk folder $dir,
     * delete the old file ($oldPath), and return the new path.
     * Returns null when no file was uploaded.
     */
    protected function replaceUpload(Request $request, string $field, string $dir, ?string $oldPath = null): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $this->deleteUpload($oldPath);

        return $request->file($field)->store($dir, 'public');
    }

    /** Delete a stored file from the public disk (does nothing if the path is empty). */
    protected function deleteUpload(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}