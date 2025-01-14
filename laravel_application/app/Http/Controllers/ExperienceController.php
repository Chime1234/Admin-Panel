<?php
/*
 * File name: ExperienceController.php
 * Last modified: 2021.03.20 at 23:14:37
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Controllers;

use App\Criteria\Doctors\DoctorsOfUserCriteria;
use App\Criteria\Experiences\ExperiencesOfUserCriteria;
use App\DataTables\ExperienceDataTable;
use App\Http\Requests\CreateExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\ExperienceRepository;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

class ExperienceController extends Controller
{
    /** @var  ExperienceRepository */
    private ExperienceRepository $experienceRepository;

    /**
     * @var CustomFieldRepository
     */
    private CustomFieldRepository $customFieldRepository;

    /**
     * @var DoctorRepository
     */
    private DoctorRepository $doctorRepository;

    public function __construct(ExperienceRepository $experienceRepo, CustomFieldRepository $customFieldRepo, DoctorRepository $doctorRepo)
    {
        parent::__construct();
        $this->experienceRepository = $experienceRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->doctorRepository = $doctorRepo;
    }

    /**
     * Display a listing of the Experience.
     *
     * @param ExperienceDataTable $experienceDataTable
     * @return mixed
     */
    public function index(ExperienceDataTable $experienceDataTable): mixed
    {
        return $experienceDataTable->render('experiences.index');
    }

    /**
     * Show the form for creating a new Experience.
     *
     * @return View
     */
    public function create():View
    {
        $doctor = $this->doctorRepository->getByCriteria(new DoctorsOfUserCriteria(auth()->id()))->pluck('name', 'id');

        $hasCustomField = in_array($this->experienceRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->experienceRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('experiences.create')->with("customFields", isset($html) ? $html : false)->with("doctor", $doctor);
    }

    /**
     * Store a newly created Experience in storage.
     *
     * @param CreateExperienceRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CreateExperienceRequest $request):RedirectResponse
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->experienceRepository->model());
        try {
            $experience = $this->experienceRepository->create($input);
            $experience->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.experience')]));

        return redirect(route('experiences.index'));
    }

    /**
     * Display the specified Experience.
     *
     * @param int $id
     *
     * @return RedirectResponse|View
     * @throws RepositoryException
     */
    public function show(int $id): RedirectResponse|View
    {
        $this->experienceRepository->pushCriteria(new ExperiencesOfUserCriteria(auth()->id()));
        $experience = $this->experienceRepository->findWithoutFail($id);

        if (empty($experience)) {
            Flash::error('Experience not found');

            return redirect(route('experiences.index'));
        }

        return view('experiences.show')->with('experience', $experience);
    }

    /**
     * Show the form for editing the specified Experience.
     *
     * @param int $id
     *
     * @return RedirectResponse|View
     * @throws RepositoryException
     */
    public function edit(int $id): RedirectResponse|View
    {
        $this->experienceRepository->pushCriteria(new ExperiencesOfUserCriteria(auth()->id()));
        $experience = $this->experienceRepository->findWithoutFail($id);
        $doctor = $this->doctorRepository->getByCriteria(new DoctorsOfUserCriteria(auth()->id()))->pluck('name', 'id');

        if (empty($experience)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.experience')]));

            return redirect(route('experiences.index'));
        }
        $customFieldsValues = $experience->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->experienceRepository->model());
        $hasCustomField = in_array($this->experienceRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('experiences.edit')->with('experience', $experience)->with("customFields", isset($html) ? $html : false)->with("doctor", $doctor);
    }

    /**
     * Update the specified Experience in storage.
     *
     * @param int $id
     * @param UpdateExperienceRequest $request
     *
     * @return RedirectResponse
     * @throws RepositoryException
     */
    public function update(int $id, UpdateExperienceRequest $request):RedirectResponse
    {
        $this->experienceRepository->pushCriteria(new ExperiencesOfUserCriteria(auth()->id()));
        $experience = $this->experienceRepository->findWithoutFail($id);

        if (empty($experience)) {
            Flash::error('Experience not found');
            return redirect(route('experiences.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->experienceRepository->model());
        try {
            $experience = $this->experienceRepository->update($input, $id);


            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $experience->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.experience')]));

        return redirect(route('experiences.index'));
    }

    /**
     * Remove the specified Experience from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     * @throws RepositoryException
     */
    public function destroy(int $id):RedirectResponse
    {
        $this->experienceRepository->pushCriteria(new ExperiencesOfUserCriteria(auth()->id()));
        $experience = $this->experienceRepository->findWithoutFail($id);

        if (empty($experience)) {
            Flash::error('Experience not found');

            return redirect(route('experiences.index'));
        }

        $this->experienceRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.experience')]));

        return redirect(route('experiences.index'));
    }
}
