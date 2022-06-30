<?php

namespace App\Http\CommonTraits;

use Illuminate\Support\Facades\Storage;

trait UploadMedia
{
    public function imageUpload($request){
        $return = false;
        $image_parts = explode(";base64,", $request->profilePhoto);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $allowed_extensions = array("png", "jpg", "jpeg", "gif", "svg", "svg+xml");
        $folder_path = public_path();
        if (in_array($image_type, $allowed_extensions)) {
            $image_base64 = base64_decode($image_parts[1]);
            $file_name = uniqid() . '.' . $image_type;
            $file = $folder_path . $file_name;
            if (file_put_contents($file, $image_base64)) {
                $return = $file_name;
            } else {
                $return = false;
            }
        }
        return $return;
    }

}
