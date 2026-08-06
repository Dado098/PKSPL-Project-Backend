<?php

namespace App\Services\Shapefile;

use App\Models\Proyek;
use Illuminate\Http\UploadedFile;

/** Stores shapefile components now, leaving parsing/import orchestration for a later implementation. */
class ShapefileUploadService
{
    /** @param array<string, UploadedFile|null> $files */
    public function store(Proyek $proyek, array $files): array
    {
        $stored = $proyek->shapefile_files ?? [];

        foreach ($files as $component => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $stored[$component] = $file->store(
                "proyek/{$proyek->id_proyek}/shapefile",
                config('filesystems.default'),
            );
        }

        return $stored;
    }
}
