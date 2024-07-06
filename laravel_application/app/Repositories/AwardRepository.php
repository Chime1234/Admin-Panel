<?php
/*
 * File name: AwardRepository.php
 * Last modified: 2024.05.03 at 21:45:45
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Repositories;

use App\Models\Award;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AwardRepository
 * @package App\Repositories
 * @version January 12, 2021, 10:59 am UTC
 *
 * @method Award findWithoutFail($id, $columns = ['*'])
 * @method Award find($id, $columns = ['*'])
 * @method Award first($columns = ['*'])
 */
class AwardRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'description',
        'clinic_id'
    ];

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return Award::class;
    }
}
