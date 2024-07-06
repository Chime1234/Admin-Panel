<?php
/*
 * File name: SlideController.php
 * Last modified: 2024.05.03 at 15:38:34
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Controllers;

use App\DataTables\SlideDataTable;
use App\Http\Requests\CreateSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\ClinicRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\SlideRepository;
use App\Repositories\UploadRepository;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Prettus\Validator\Exceptions\ValidatorException;

class SlideController extends Controller
{
    /** @var  SlideRepository */
    private SlideRepository $slideRepository;

    /**
     * @var CustomFieldRepository
     */
    private CustomFieldRepository $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private UploadRepository $uploadRepository;
    /**
     * @var DoctorRepository
     */
    private DoctorRepository $doctorRepository;
    /**
     * @var ClinicRepository
     */
    private ClinicRepository $clinicRepository;

    public function __construct(SlideRepository $slideRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo
        , DoctorRepository $doctorRepo
        , ClinicRepository $clinicRepo)
    {
        parent::__construct();
        $this->slideRepository = $slideRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->doctorRepository = $doctorRepo;
        $this->clinicRepository = $clinicRepo;
    }

    /**
     * Display a listing of the Slide.
     *
     * @param SlideDataTable $slideDataTable
     * @return mixed
     */
    public function index(SlideDataTable $slideDataTable):mixed
    {
        return $slideDataTable->render('slides.index');
    }

    /**
     * Show the form for creating a new Slide.
     *
     * @return View
     */
    public function create():View
    {
        $doctor = $this->doctorRepository->pluck('name', 'id');

        $clinic = $this->clinicRepository->pluck('name', 'id');


        $hasCustomField = in_array($this->slideRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->slideRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('slides.create')->with("customFields", isset($html) ? $html : false)->with("doctor", $doctor)->with("clinic", $clinic);
    }

    /**
     * Store a newly created Slide in storage.
     *
     * @param CreateSlideRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CreateSlideRequest $request):RedirectResponse
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->slideRepository->model());
        try {
            $slide = $this->slideRepository->create($input);
            $slide->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($slide, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.slide')]));

        return redirect(route('slides.index'));
    }

    /**
     * Show the form for editing the specified Slide.
     *
     * @param int $id
     *
     * @return RedirectResponse|View
     */
    public function edit(int $id):RedirectResponse|View
    {
        $slide = $this->slideRepository->findWithoutFail($id);
        $doctor = $this->doctorRepository->pluck('name', 'id')->prepend(__('lang.slide_doctor_id_placeholder'), '');
        $clinic = $this->clinicRepository->pluck('name', 'id')->prepend(__('lang.slide_clinic_id_placeholder'), '');
        if (empty($slide)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.slide')]));

            return redirect(route('slides.index'));
        }
        $customFieldsValues = $slide->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->slideRepository->model());
        $hasCustomField = in_array($this->slideRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('slides.edit')->with('slide', $slide)->with("customFields", isset($html) ? $html : false)->with("doctor", $doctor)->with("clinic", $clinic);
    }

    /**
     * Update the specified Slide in storage.
     *
     * @param int $id
     * @param UpdateSlideRequest $request
     *
     * @return RedirectResponse
     */
    public function update(int $id, UpdateSlideRequest $request):RedirectResponse
    {
        $slide = $this->slideRepository->findWithoutFail($id);

        if (empty($slide)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.slide')]));
            return redirect(route('slides.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->slideRepository->model());
        try {
            $slide = $this->slideRepository->update($input, $id);

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($slide, 'image');
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $slide->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.slide')]));
        return redirect(route('slides.index'));
    }

    /**
     * Remove the specified Slide from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function destroy(int $id):RedirectResponse
    {
        $slide = $this->slideRepository->findWithoutFail($id);

        if (empty($slide)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.slide')]));

            return redirect(route('slides.index'));
        }

        $this->slideRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.slide')]));
        return redirect(route('slides.index'));
    }

    /**
     * Remove Media of Slide
     * @param Request $request
     */
    public function removeMedia(Request $request): void
    {
        $input = $request->all();
        $slide = $this->slideRepository->findWithoutFail($input['id']);
        try {
            if ($slide->hasMedia($input['collection'])) {
                $slide->getFirstMedia($input['collection'])->delete();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

}
