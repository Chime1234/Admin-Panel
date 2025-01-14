<?php
/*
 * File name: NearCriteria.php
 * Last modified: 2021.04.18 at 11:59:11
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Criteria\Specialities;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class NearCriteria.
 *
 * @package namespace App\Criteria\Specialities;
 */
class NearCriteria implements CriteriaInterface
{
    /**
     * @var array|Request
     */
    private Request|array $request;

    /**
     * NearCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

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
        if ($this->request->has(['myLon', 'myLat'])) {
            $myLat = $this->request->get('myLat');
            $myLon = $this->request->get('myLon');
            return $model->with(['featuredDoctors' => function ($q){
                $coordination = $this->request->only('myLat', 'myLon');
                $coordination = array_values($coordination);
                array_push($coordination, ...$coordination);

                return $q->near(...$coordination);
                //return $q->near($myLat, $myLon);
            }]);
        } else {
            return $model;
        }
    }
}
