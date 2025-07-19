<?php

namespace App\Traits;

use Error;
use Illuminate\Support\Str;

trait UploadFiles
{

    public function uploadFiles($documentFiles, $folder, $max = 4): array
    {
        $documents = [];

        if (count($documentFiles) > $max) {
            throw new Error("Maximum of $max documents are allowed for upload");
        }

        foreach ($documentFiles as $file) {
            $uuid = Str::uuid();

            $fileName = time().'_'.$uuid.'_'.$file->getClientOriginalName();
            $file->storeAs('public/'.$folder, $fileName);
            $documents[] = "{$folder}/{$fileName}";
        }

        return $documents;
    }

    public function uploadFile($file, $folder): string
    {
        $uuid = Str::uuid();
        $fileName = time().'_'.$uuid.'_'.$file->getClientOriginalName();

        $file->storeAs('public/'.$folder, $fileName);
        return "{$folder}/{$fileName}";
    }

    public function getFilePath(?string $path): ?string
    {

        if (!$path) {
            return null;
        }

        return asset('storage/'.$path);
    }
}
