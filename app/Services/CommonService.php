<?php
namespace App\Services;

class CommonService
{
    public function limit_text($text, $limit) {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }

    public function storeImage($reqImg, $imgPath='', $dbColName = 'image'){
        if(empty($imgPath)){
            $imgPath= config('constants.image.upload');
        }
        $image      = $reqImg;
        $fileName   = $dbColName.'-'.time() . '.' . $image->getClientOriginalExtension();
        $img = \Image::make($image->getRealPath());
        $img->stream();
        \Storage::disk(config('constants.disk.driver'))->put('public/'.$imgPath.'/'.$fileName, $img);
        return $fileName;
    }
}