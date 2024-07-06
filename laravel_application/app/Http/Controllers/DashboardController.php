<?php
/*
 * File name: DashboardController.php
 * Last modified: 2021.12.04 at 12:22:28
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Controllers;

use App\Repositories\AppointmentRepository;
use App\Repositories\EarningRepository;
use App\Repositories\ClinicRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use Illuminate\View\View;
use Modules\Pharmacies\Repositories\OrderRepository;
use Modules\Pharmacies\Repositories\PharmaciesEarningRepository;
use Modules\Pharmacies\Repositories\PharmacyRepository;

class DashboardController extends Controller
{

    /** @var  AppointmentRepository */
    private AppointmentRepository $appointmentRepository;


    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /** @var  ClinicRepository */
    private ClinicRepository $clinicRepository;
    /** @var  EarningRepository */
    private EarningRepository $earningRepository;


    /** @var  PatientRepository */
    private PatientRepository $patientRepository;


    /** @var  PharmacyRepository */
    private PharmacyRepository $pharmacyRepository;

    /** @var  PharmaciesEarningRepository */

    private PharmaciesEarningRepository $pharmaciesEarningRepository;

    /** @var OrderRepository */

    private OrderRepository $orderRepository;
    public function __construct(AppointmentRepository $appointmentRepo,
                                UserRepository $userRepo,
                                EarningRepository $earningRepository,
                                ClinicRepository $clinicRepo,
                                PatientRepository $patientRepo,
                                PharmacyRepository $pharmacyRepo,
                                PharmaciesEarningRepository $pharmaciesEarningRepo,
                                OrderRepository $orderRepo
    )
    {
        parent::__construct();
        $this->appointmentRepository = $appointmentRepo;
        $this->userRepository = $userRepo;
        $this->clinicRepository = $clinicRepo;
        $this->patientRepository = $patientRepo;
        $this->earningRepository = $earningRepository;
        $this->pharmacyRepository = $pharmacyRepo;
        $this->pharmaciesEarningRepository = $pharmaciesEarningRepo;
        $this->orderRepository = $orderRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $ordersCount = 0;
        $pharmaciesEarning = 0;
        $pharmaciesCount = 0;
        try {
            $ordersCount = $this->orderRepository->count();
            $pharmaciesEarning = $this->pharmaciesEarningRepository->all()->sum('total_earning');
            $pharmaciesCount = $this->pharmacyRepository->count();
        }catch (\Exception $e){}
        $appointmentsCount = $this->appointmentRepository->count();
        $membersCount = $this->userRepository->count();
        $clinicCount = $this->clinicRepository->count();
        $clinics = $this->clinicRepository->orderBy('id', 'desc')->limit(4);
        $patients = $this->patientRepository->orderBy('id', 'desc')->limit(5);
        $earning = $this->earningRepository->all()->sum('total_earning');


        $ajaxEarningUrl = route('payments.byMonth', ['api_token' => auth()->user()->api_token]);
        return view('dashboard.index')
            ->with("ajaxEarningUrl", $ajaxEarningUrl)
            ->with("appointmentsCount", $appointmentsCount)
            ->with("clinicsCount", $clinicCount)
            ->with("clinics", $clinics)
            ->with("membersCount", $membersCount)
            ->with("earning", $earning)
            ->with("patients", $patients)
            ->with("ordersCount", $ordersCount)
            ->with("pharmaciesEarning", $pharmaciesEarning)
            ->with("pharmaciesCount", $pharmaciesCount);
    }
}
