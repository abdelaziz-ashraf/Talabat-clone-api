<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalFileUploader
{
    public function upload($file, string $path, string $replaceWith = null)
    {
        if ($replaceWith) {
            $this->deleteFile($replaceWith);
        }

        [$name, $extension] = explode('.', $file->getClientOriginalName());
        $fileName = date('YmdHi').'-'.Str::of($name)->slug('-').'.'.$extension;
        $file->move(public_path('uploads/'.$path), $fileName);

        return $fileName;
    }

    public function deleteFile(string $path)
    {
        $path = str_replace(asset('uploads'), '', $path);
        Storage::disk('public_uploads')->delete($path);
    }

}
