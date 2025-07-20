<?php

namespace App\Traits;

use Error;

trait UploadFiles
{

    public function uploadFiles($documentFiles, $folder, $max = 4, $storage = 'public'): array
    {
        $documents = [];

        if (count($documentFiles) > $max) {
            throw new Error("Maximum of $max documents are allowed for upload");
        }

        foreach ($documentFiles as $file) {
            $path = $file->store($folder, $storage);
            $documents[] = $path;
        }

        return $documents;
    }

    public function uploadFile($file, $folder, $storage = 'public')
    {
        return $file->store($folder, $storage);
    }

    public function getFilePath($path): ?string
    {
        if (!$path) {
            return null;
        }
        return asset('storage/'.$path);
    }

    public function getFilePaths($paths): array
    {
        if (!$paths) {
            return [];
        }

        return array_map(function ($path) {
            return asset('storage/'.$path);
        }, $paths);
    }
}
