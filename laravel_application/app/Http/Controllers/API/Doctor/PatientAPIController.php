<?php
/*
 * File name: PatientAPIController.php
 * Last modified: 2024.04.13 at 08:14:30
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Controllers\API\Doctor;


use App\Criteria\Doctors\DoctorsOfUserCriteria;
use App\Criteria\Patients\PatientOfDoctorCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\DoctorPatientsRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\PatientRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class PatientController
 * @package App\Http\Controllers\API
 */
class PatientAPIController extends Controller
{
    /** @var  patientRepository */
    private PatientRepository $patientRepository;

    /** @var  doctorPatientsRepository */
    private DoctorPatientsRepository $doctorPatientsRepository;

    /** @var  doctorRepository */
    private DoctorRepository $doctorRepository;

    public function __construct(PatientRepository $patientRepo,DoctorRepository $doctorRepo,DoctorPatientsRepository $doctorPatientsRepo)
    {
        parent::__construct();
        $this->patientRepository = $patientRepo;
        $this->doctorRepository = $doctorRepo;
        $this->doctorPatientsRepository = $doctorPatientsRepo;
    }

    /**
     * Display a listing of the Patient.
     * GET|HEAD /patients
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->patientRepository->pushCriteria(new RequestCriteria($request));
            $this->patientRepository->pushCriteria(new LimitOffsetCriteria($request));

            $patients = $this->patientRepository->all();

            $patients = array_values($patients->toArray());

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($patients, 'Patients retrieved successfully');
    }


}
