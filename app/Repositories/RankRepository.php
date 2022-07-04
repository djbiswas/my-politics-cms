<?php

namespace App\Repositories;

use App\Models\Rank;

/**
 * Class RankRepository.
 */
class RankRepository
{
    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = "")
    {
        $data = Rank::where($condition)
                ->orderBy('title');
        if($limit){
            $data->take($limit);
        }
        return $data->get();
    }

    /**
     * For Create / Updating record into ranks table
     */
    public function saveData($condition = [], $fields)
    {
        return (!empty($condition)) ? Rank::updateOrCreate($condition, $fields) : Rank::updateOrCreate($fields);
    }
}

?>
