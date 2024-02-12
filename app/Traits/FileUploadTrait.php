<?php

namespace App\Traits;

use Illuminate\Http\Request;
use File;

trait FileUploadTrait
{

    function uploadImage(Request $request, $inputName, $oldPath = NULL, $path)
    {

        if ($request->hasFile($inputName)) {

            $image = $request->{$inputName};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'media_' . uniqid() . '.' . $ext; //image.extension
            $image->move(public_path($path), $imageName);
            // delete image if exists
            if ($oldPath && File::exists(public_path($oldPath))) {
                File::delete(public_path($oldPath));
            }
            return $path . '/' . $imageName;
        }
        return null;
    }
    function removeImage(String $path):void{
        if ( File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
