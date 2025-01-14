<?php
/*
 * File name: EarningRepository.php
 * Last modified: 2024.05.03 at 15:09:09
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Repositories;

use App\Models\Earning;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class EarningRepository
 * @package App\Repositories
 * @version January 30, 2021, 1:53 pm UTC
 *
 * @method Earning findWithoutFail($id, $columns = ['*'])
 * @method Earning find($id, $columns = ['*'])
 * @method Earning first($columns = ['*'])
 */
class EarningRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'clinic_id',
        'doctor_id',
        'total_appointments',
        'total_earning',
        'admin_earning',
        'clinic_earning',
        'doctor_earning',
        'taxes'
    ];

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return Earning::class;
    }
}
