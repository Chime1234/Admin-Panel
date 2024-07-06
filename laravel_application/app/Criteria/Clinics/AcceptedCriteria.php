<?php
/*
 * File name: AcceptedCriteria.php
 * Last modified: 2024.05.03 at 21:22:03
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Criteria\Clinics;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AcceptedCriteria.
 *
 * @package namespace App\Criteria\Clinics;
 */
class AcceptedCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository): mixed
    {
        return $model->where('clinics.accepted', '1');
    }
}
