<?php

namespace App\Http\Controllers\API;


use App\Models\Faq;
use App\Repositories\FaqRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;

/**
 * Class FaqController
 * @package App\Http\Controllers\API
 */

class FaqAPIController extends Controller
{
    /** @var  FaqRepository */
    private FaqRepository $faqRepository;

    public function __construct(FaqRepository $faqRepo)
    {
        $this->faqRepository = $faqRepo;
        parent::__construct();
    }

    /**
     * Display a listing of the Faq.
     * GET|HEAD /faqs
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try{
            $this->faqRepository->pushCriteria(new RequestCriteria($request));
            $this->faqRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $faqs = $this->faqRepository->all();

        return $this->sendResponse($faqs->toArray(), 'Faqs retrieved successfully');
    }

    /**
     * Display the specified Faq.
     * GET|HEAD /faqs/{id}
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {

        if (!empty($this->faqRepository)) {
            $faq = $this->faqRepository->findWithoutFail($id);
        }

        if (empty($faq)) {
            return $this->sendError('Faq not found');
        }

        return $this->sendResponse($faq->toArray(), 'Faq retrieved successfully');
    }
}
