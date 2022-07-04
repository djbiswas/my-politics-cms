<?php

namespace App\Repositories;

use App\Models\Page;

/**
 * Class PageRepository.
 */
class PageRepository
{
    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = 8)
    {
        return Page::where($condition)
                ->orderBy('id')
                ->take($limit)
                ->get();
    }

    /**
     * For Create / Updating record into pages table
     */
    public function saveData($condition = [], $fields)
    {
        return (!empty($condition)) ? Page::updateOrCreate($condition, $fields) : Page::updateOrCreate($fields);
    }
}

?>
