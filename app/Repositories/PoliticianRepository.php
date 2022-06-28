<?php

namespace App\Repositories;

use App\Models\Politician;

/**
 * Class PoliticianRepository.
 */
class PoliticianRepository
{
    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = '')
    {
        $data = Politician::where($condition)
                ->orderBy('name');
        if($limit){
            $data->take($limit);
        }
        return $data->get();
    }
}

?>
