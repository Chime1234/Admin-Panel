<?php
/*
 * File name: SpecialitiesOfClinicCriteria.php
 * Last modified: 2021.02.21 at 14:50:32
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Criteria\Specialities;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SpecialitiesOfClinicCriteria.
 *
 * @package namespace App\Criteria\Specialities;
 */
class SpecialitiesOfClinicCriteria implements CriteriaInterface
{
    /**
     * @var array|Request
     */
    private Request|array $request;

    /**
     * SpecialitiesOfClinicCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository): mixed
    {
        if (!$this->request->has('clinic_id')) {
            return $model;
        } else {
            $id = $this->request->get('clinic_id');
            return $model->join('doctors', 'doctors.speciality_id', '=', 'specialities.id')
                ->where('doctors.clinic_id', $id)
                ->select('specialities.*')
                ->groupBy('specialities.id');
        }
    }
}
