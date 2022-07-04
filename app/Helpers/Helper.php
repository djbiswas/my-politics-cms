<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('uploadFile')) {

    /**
     * Upload file to storage
     *
     * @param String $folderName
     * @param String $fileName
     *
     */
    function uploadFile($folderName, $fileName)
    {
        //get filename with extension
        $fileNameWithExtension = str_replace(["-", " ", "(", ")", "%", "#", "$", "*", "&", "[", "]", "+"], "", $fileName->getClientOriginalName());

        $fileNameChanges = preg_replace("//", '', $fileNameWithExtension);
        //get filename without extension
        $file = pathinfo($fileNameChanges, PATHINFO_FILENAME);
        //get file extension
        $extension = $fileName->getClientOriginalExtension();

        //filename to store
        $fileNameToStore = $file . '_' . time() . '.' . $extension;
       
        // Upload Image to s3 storage
        Storage::disk(config('constants.image.driver'))->put($folderName, $fileNameToStore);

        return $fileNameToStore;
    }
}

if (!function_exists('explodeMetaData')) {

    /**
     * Explode Meta Data
     *
     * @param String $folderName
     * @param String $fileName
     *
     */
    function explodeMetaData($keys, $values) {
        $meta_data = [];
        $meta_keys = explode('-~-', $keys);
        $meta_values = explode('-~-', $values);
        if (!empty($meta_keys)) {
            foreach ($meta_keys as $key => $value) {
                $meta_data[$value] = $meta_values[$key] ?? null;
            }
        }
        return $meta_data;
    }
}





