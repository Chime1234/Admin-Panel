<?php
/*
 * File name: ClinicLevelController.php
 * Last modified: 2024.02.03 at 10:46:03
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Controllers;

use App\DataTables\ClinicLevelDataTable;
use App\Http\Requests\CreateClinicLevelRequest;
use App\Http\Requests\UpdateClinicLevelRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\ClinicLevelRepository;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class ClinicLevelController extends Controller
{
    /** @var  ClinicLevelRepository */
    private ClinicLevelRepository $clinicLevelRepository;

    /**
     * @var CustomFieldRepository
     */
    private CustomFieldRepository $customFieldRepository;


    public function __construct(ClinicLevelRepository $clinicLevelRepo, CustomFieldRepository $customFieldRepo)
    {
        parent::__construct();
        $this->clinicLevelRepository = $clinicLevelRepo;
        $this->customFieldRepository = $customFieldRepo;

    }

    /**
     * Display a listing of the ClinicLevel.
     *
     * @param ClinicLevelDataTable $clinicLevelDataTable
     * @return mixed
     */
    public function index(ClinicLevelDataTable $clinicLevelDataTable): mixed
    {
        return $clinicLevelDataTable->render('clinic_levels.index');
    }

    /**
     * Show the form for creating a new ClinicLevel.
     *
     * @return View
     */
    public function create(): View
    {


        $hasCustomField = in_array($this->clinicLevelRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->clinicLevelRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('clinic_levels.create')->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Store a newly created ClinicLevel in storage.
     *
     * @param CreateClinicLevelRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CreateClinicLevelRequest $request):RedirectResponse
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->clinicLevelRepository->model());
        try {
            $clinicLevel = $this->clinicLevelRepository->create($input);
            $clinicLevel->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.clinic_level')]));

        return redirect(route('clinicLevels.index'));
    }

    /**
     * Display the specified ClinicLevel.
     *
     * @param int $id
     *
     * @return RedirectResponse|View
     */
    public function show(int $id): RedirectResponse|View
    {
        $clinicLevel = $this->clinicLevelRepository->findWithoutFail($id);

        if (empty($clinicLevel)) {
            Flash::error('Clinic Level not found');

            return redirect(route('clinicLevels.index'));
        }

        return view('clinic_levels.show')->with('clinicLevel', $clinicLevel);
    }

    /**
     * Show the form for editing the specified ClinicLevel.
     *
     * @param int $id
     *
     * @return RedirectResponse|View
     */
    public function edit(int $id):RedirectResponse|View
    {
        $clinicLevel = $this->clinicLevelRepository->findWithoutFail($id);


        if (empty($clinicLevel)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.clinic_level')]));

            return redirect(route('clinicLevels.index'));
        }
        $customFieldsValues = $clinicLevel->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->clinicLevelRepository->model());
        $hasCustomField = in_array($this->clinicLevelRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('clinic_levels.edit')->with('clinicLevel', $clinicLevel)->with("customFields", isset($html) ? $html : false);
    }

    /**
     * Update the specified ClinicLevel in storage.
     *
     * @param int $id
     * @param UpdateClinicLevelRequest $request
     *
     * @return RedirectResponse
     */
    public function update(int $id, UpdateClinicLevelRequest $request): RedirectResponse
    {
        $clinicLevel = $this->clinicLevelRepository->findWithoutFail($id);

        if (empty($clinicLevel)) {
            Flash::error('Clinic Level not found');
            return redirect(route('clinicLevels.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->clinicLevelRepository->model());
        try {
            $clinicLevel = $this->clinicLevelRepository->update($input, $id);


            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $clinicLevel->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.clinic_level')]));

        return redirect(route('clinicLevels.index'));
    }

    /**
     * Remove the specified ClinicLevel from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function destroy(int $id):RedirectResponse
    {
        $clinicLevel = $this->clinicLevelRepository->findWithoutFail($id);

        if (empty($clinicLevel)) {
            Flash::error('Clinic Level not found');

            return redirect(route('clinicLevels.index'));
        }

        $this->clinicLevelRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.clinic_level')]));

        return redirect(route('clinicLevels.index'));
    }

    /**
     * Remove Media of ClinicLevel
     * @param Request $request
     */
    public function removeMedia(Request $request): void
    {
        $input = $request->all();
        $clinicLevel = $this->clinicLevelRepository->findWithoutFail($input['id']);
        try {
            if ($clinicLevel->hasMedia($input['collection'])) {
                $clinicLevel->getFirstMedia($input['collection'])->delete();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
