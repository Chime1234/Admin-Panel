<?php

namespace App\Http\Controllers;

use App\DataTables\PatientDataTable;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Repositories\PatientRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UserRepository;
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

class PatientController extends Controller
{
    /** @var  PatientRepository */
    private PatientRepository $patientRepository;

    /**
     * @var CustomFieldRepository
     */
    private CustomFieldRepository $customFieldRepository;

    /**
      * @var UserRepository
      */
    private UserRepository $userRepository;
    /**
     * @var UploadRepository
     */
    private UploadRepository $uploadRepository;

    public function __construct(PatientRepository $patientRepo, CustomFieldRepository $customFieldRepo , UserRepository $userRepo
                , UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->patientRepository = $patientRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->userRepository = $userRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Patient.
     *
     * @param PatientDataTable $patientDataTable
     * @return mixed
     */
    public function index(PatientDataTable $patientDataTable):mixed
    {
        return $patientDataTable->render('patients.index');
    }

    /**
     * Show the form for creating a new Patient.
     *
     * @return View
     */
    public function create():View
    {
        $user = $this->userRepository->pluck('name','id');

        
        $hasCustomField = in_array($this->patientRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->patientRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('patients.create')->with("customFields", isset($html) ? $html : false)->with("user",$user);
    }

    /**
     * Store a newly created Patient in storage.
     *
     * @param CreatePatientRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CreatePatientRequest $request):RedirectResponse
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->patientRepository->model());
        $patient = $this->patientRepository->create($input);
        $patient->customFieldsValues()->createMany(getCustomFieldsValues($customFields,$request));
        try {
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($patient, 'image');
                }
            }
            if (isset($input['card_id']) && $input['card_id'] && is_array($input['card_id'])) {
                foreach ($input['card_id'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('card_id')->first();
                    $mediaItem->copy($patient, 'card_id');
                }
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.patient')]));

        return redirect(route('patients.index'));
    }

    /**
     * Display the specified Patient.
     *
     * @param  int $id
     *
     * @return RedirectResponse|View
     */
    public function show(int $id):RedirectResponse|View
    {
        $patient = $this->patientRepository->findWithoutFail($id);

        if (empty($patient)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.patient')]));
            return redirect(route('patients.index'));
        }
        return view('patients.show')->with('patient', $patient);
    }

    /**
     * Show the form for editing the specified Patient.
     *
     * @param  int $id
     *
     * @return RedirectResponse|View
     */
    public function edit(int $id):RedirectResponse|View
    {
        $patient = $this->patientRepository->findWithoutFail($id);
        if (empty($patient)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.patient')]));

            return redirect(route('patients.index'));
        }
        $user = $this->userRepository->pluck('name','id');
        $customFieldsValues = $patient->customFieldsValues()->with('customField')->get();
        $customFields =  $this->customFieldRepository->findByField('custom_field_model', $this->patientRepository->model());
        $hasCustomField = in_array($this->patientRepository->model(),setting('custom_field_models',[]));
        if($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('patients.edit')->with('patient', $patient)->with("customFields", isset($html) ? $html : false)->with("user",$user);
    }

    /**
     * Update the specified Patient in storage.
     *
     * @param  int              $id
     * @param UpdatePatientRequest $request
     *
     * @return RedirectResponse
     */
    public function update(int $id, UpdatePatientRequest $request):RedirectResponse
    {
        $patient = $this->patientRepository->findWithoutFail($id);

        if (empty($patient)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.patient')]));
            return redirect(route('patients.index'));
        }
        $input = $request->all();

        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->patientRepository->model());
        try {
            //dd($input);
            $patient = $this->patientRepository->update($input, $id);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($patient, 'image');
                }
            }
            if (isset($input['card_id']) && $input['card_id'] && is_array($input['card_id'])) {
                foreach ($input['card_id'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('card_id')->first();
                    $mediaItem->copy($patient, 'card_id');
                }
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value){
                $patient->customFieldsValues()
                    ->updateOrCreate(['custom_field_id'=>$value['custom_field_id']],$value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('lang.updated_successfully',['operator' => __('lang.patient')]));
        return redirect(route('patients.index'));
    }

    /**
     * Remove the specified Patient from storage.
     *
     * @param  int $id
     *
     * @return RedirectResponse
     */
    public function destroy(int $id):RedirectResponse
    {
        $patient = $this->patientRepository->findWithoutFail($id);

        if (empty($patient)) {
            Flash::error(__('lang.not_found',['operator' => __('lang.patient')]));

            return redirect(route('patients.index'));
        }

        $this->patientRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.patient')]));
        return redirect(route('patients.index'));
    }

        /**
     * Remove Media of Patient
     * @param Request $request
     */
    public function removeMedia(Request $request): void
    {
        $input = $request->all();
        $patient = $this->patientRepository->findWithoutFail($input['id']);
        try {
            if ($patient->hasMedia($input['collection'])) {
                $patient->getFirstMedia($input['collection'])->delete();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

}
