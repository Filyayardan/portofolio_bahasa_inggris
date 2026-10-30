<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HandlesUploads
{
    protected function storeUpload(?UploadedFile $file, string $folder, ?string $oldPath = null): ?string
    {
        if (! $file) {
            return $oldPath;
        }

        $this->deleteUpload($oldPath);

        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $name = $base.'-'.Str::lower(Str::random(6)).'.'.$file->getClientOriginalExtension();

        $directory = public_path('uploads/'.$folder);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file->move($directory, $name);

        return 'uploads/'.$folder.'/'.$name;
    }

    protected function deleteUpload(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
