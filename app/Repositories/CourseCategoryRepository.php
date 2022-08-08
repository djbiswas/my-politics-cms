<?php

namespace App\Repositories;

use App\Models\CourseCategory;
use Illuminate\Http\Request;

/**
 * Class CourseCategoryRepository.
 */
class CourseCategoryRepository
{
    /**
     * For selecting the data
     *
     */
    public function getPoliticianCategories()
    {
        return CourseCategory::select('id', 'name', 'icon')->get();
    }

    /**
     * For Create / Updating record into CourseCategory table
     */
    public function saveData($condition = [], $fields)
    {
        return  CourseCategory::updateOrCreate($condition, $fields);
    }

    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = "")
    {
        $data = CourseCategory::where($condition)
                ->orderBy('name');
        if($limit){
            $data->take($limit);
        }
        return $data->get();
    }

    /**
     * For Uploading the file into storage
     *
     * @param Request $request
     */
    public function mediaUpload(Request $request)
    {
        $path = public_path('post_comment_image/');
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0777, true, true);
        }

        $fileName = uploadFile('post_comment_image', $request->file);

        return $fileName;
    }
}


?>
