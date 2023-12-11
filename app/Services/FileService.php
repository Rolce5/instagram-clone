<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class FileService
{
    // public function updateFile($model, $request, $type){
    //     if (!empty($model->file)){
    //         $currentFile = public_path() . $model->file;

    //         if(file_exists($currentFile) && $currentFile != public_path() . '/user-placeholder.png'){
    //             unlink($currentFile);
    //         }
    //     }

    //     $file = null;
    //     if($type === "user") {
    //         $file = Image::make($request->file('file'))->resize(400, 400);
    //     } else {
    //         $file = Image::make($request->file('file'));
    //     }
    //     $ext = $request->file('file');
    //     $extension = $ext->getClientOriginalExtension();
    //     $name = time() . '.' .$extension;
    //     $file->save(public_path() . '/file/' . $name);
    //     $model->file = '/file/' .$name;

    //     return $model;
    // }

    public function updateFile($model, $request, $type)
{
    if (!empty($model->file)) {
        $currentFile = public_path() . $model->file;

        if (file_exists($currentFile) && $currentFile != public_path() . '/user-placeholder.png') {
            unlink($currentFile);
        }
    }

    $file = null;
    $uploadedFile = $request->file('file');
    $mimeType = $uploadedFile->getMimeType();

    // Handle image files
    if (strpos($mimeType, 'image') === 0) {
        if ($type === "user") {
            $file = Image::make($uploadedFile)->resize(400, 400);
        } else {
            $file = Image::make($uploadedFile);
        }

        $extension = $uploadedFile->getClientOriginalExtension();
        $name = time() . '.' . $extension;
        $path = '/file/' . $name;
        $file->save(public_path() . $path);
    }
    // Handle video files
    else if (strpos($mimeType, 'video') === 0) {
        $extension = $uploadedFile->getClientOriginalExtension();
        $name = time() . '.' . $extension;
        $path = '/file/' . $name;
        $uploadedFile->move(public_path() . '/file/', $name);
    }
    // Handle other file types
    else {
        // Handle the case when the file type is not supported
        return null;
    }

    $model->file = $path;
    return $model;
}
}