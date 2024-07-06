<?php
/*
 * File name: ClinicPayoutAPIController.php
 * Last modified: 2024.05.03 at 16:06:30
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\ClinicPayout;
use App\Repositories\ClinicPayoutRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class ClinicPayoutController
 * @package App\Http\Controllers\API
 */
class ClinicPayoutAPIController extends Controller
{
    /** @var  ClinicPayoutRepository */
    private ClinicPayoutRepository $clinicPayoutRepository;

    public function __construct(ClinicPayoutRepository $clinicPayoutRepo)
    {
        $this->clinicPayoutRepository = $clinicPayoutRepo;
        parent::__construct();
    }

    /**
     * Display a listing of the ClinicPayout.
     * GET|HEAD /clinicPayouts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->clinicPayoutRepository->pushCriteria(new RequestCriteria($request));
            $this->clinicPayoutRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $clinicPayouts = $this->clinicPayoutRepository->all();

        return $this->sendResponse($clinicPayouts->toArray(), 'Clinic Payouts retrieved successfully');
    }

    /**
     * Display the specified ClinicPayout.
     * GET|HEAD /clinicPayouts/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {

        if (!empty($this->clinicPayoutRepository)) {
            $clinicPayout = $this->clinicPayoutRepository->findWithoutFail($id);
        }

        if (empty($clinicPayout)) {
            return $this->sendError('Clinic Payout not found');
        }

        return $this->sendResponse($clinicPayout->toArray(), 'Clinic Payout retrieved successfully');
    }
}
