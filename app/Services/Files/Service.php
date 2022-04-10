<?php

namespace App\Services\Files;

use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class Service {

    public function getFilesByPath(string $path): array {
        if (File::isFile($path)) {
            return [File::basename($path)];
        }

        return collect(File::files($path))->map(fn(SplFileInfo $file) => $file->getFilename())->toArray();
    }

    public function deleteRecursively(string $path): void {
        if (File::isFile($path)) {
            File::delete($path);
        }

        File::deleteDirectory($path);
    }
}
