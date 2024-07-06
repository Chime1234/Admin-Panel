<?php
/*
 * File name: PayMongoController.php
 * Last modified: 2021.11.02 at 13:04:47
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Controllers;

use Exception;
use Flash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayMongoController extends ParentAppointmentController
{

    /**
     * @return void
     */
    public function __init()
    {

    }

    /**
     * @return View
     */
    public function index():View
    {
        return view('home');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function checkout(Request $request):RedirectResponse|View
    {
        $this->appointment = $this->appointmentRepository->findWithoutFail($request->get('appointment_id'));
        if (empty($this->appointment)) {
            Flash::error("Error processing PayMongo payment for your appointment");
            return redirect(route('payments.failed'));
        }
        return view('payment_methods.paymongo_charge', ['appointment' => $this->appointment]);
    }

    /**
     * @param Request $request
     * @param int $appointmentId
     * @param string $paymentMethodId
     * @return RedirectResponse
     */
    public function processing(Request $request, int $appointmentId, string $paymentMethodId): RedirectResponse
    {
        $this->appointment = $this->appointmentRepository->findWithoutFail($appointmentId);
        if (empty($this->appointment)) {
            Flash::error("Error processing PayMongo payment for your appointment");
            return redirect(route('payments.failed'));
        } else {
            try {
                $intent = $this->createPaymentIntent();
                if ($intent != null && isset($intent['data'])) {
                    $intent = $this->attachPaymentIntent($intent['data']['id'], $paymentMethodId, $appointmentId);
                    if ($intent != null && isset($intent['data']['attributes']['status'])) {
                        if ($intent['data']['attributes']['status'] == "succeeded") {
                            $this->paymentMethodId = 12; // PayMongo method
                            $this->createAppointment();
                            return redirect(url('payments/paymongo'));
                        } else if ($intent['data']['attributes']['status'] == "awaiting_next_action") {
                            return redirect($intent['data']['attributes']['next_action']['redirect']['url']);
                        }
                    }
                }
                if ($intent != null && isset($intent['errors'])) {
                    $details = array_map(function ($value) {
                        return $value['detail'];
                    }, $intent['errors']);
                    Flash::error(implode('<br><br>', $details));
                    return redirect(route('payments.failed'));
                }
                Flash::error("The payment failed to be processed due to unknown reasons.");
                return redirect(route('payments.failed'));
            } catch (Exception $e) {
                Flash::error("Error processing PayMongo payment for your appointment :" . $e->getMessage());
                return redirect(route('payments.failed'));
            }
        }
    }

    /**
     * @return mixed|null
     */
    private function createPaymentIntent(): mixed
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paymongo.com/v1/payment_intents",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->getAppointmentData(),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . base64_encode(setting('paymongo_secret')),
                "Content-Type: application/json",
                "Accept: application/json",
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Flash::error($err);
            return null;
        } else {
            return json_decode($response, true);
        }
    }

    /**
     * Set cart data for processing payment on PayMongo.
     */
    private function getAppointmentData(): string
    {
        $amount = $this->appointment->getTotal();
        $amount = (int)($amount * 100);
        $currency = setting('default_currency_code');
        $description = "#" . $this->appointment->id . " - " . $this->appointment->doctor->name . " - " . $this->appointment->clinic->name;
        return '{"data":{"attributes":{"amount":' . $amount . ',"payment_method_allowed":["card","paymaya"], "payment_method_options":{"card":{"request_three_d_secure":"any"}},"currency":"' . $currency . '","description":"' . $description . '"}}}';
    }

    /**
     * @param $paymentIntentId
     * @param $paymentMethodId
     * @param $appointmentId
     * @return mixed|null
     */
    private function attachPaymentIntent($paymentIntentId, $paymentMethodId, $appointmentId): mixed
    {
        $curl = curl_init();
        $paymentMethodId = '{"data":{"attributes":{"payment_method":"' . $paymentMethodId . '","return_url":"' . url('payments/paymongo/success', ['appointment_id' => $appointmentId, 'payment_intent_id' => $paymentIntentId]) . '"}}}';
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paymongo.com/v1/payment_intents/$paymentIntentId/attach",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $paymentMethodId,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . base64_encode(setting('paymongo_secret')),
                "Content-Type: application/json",
                "Accept: application/json",
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Flash::error($err);
            return null;
        } else {
            return json_decode($response, true);
        }
    }

    /**
     * @param Request $request
     * @param int $appointmentId
     * @param string $paymentIntentId
     * @return RedirectResponse
     */
    public function success(Request $request, int $appointmentId, string $paymentIntentId):RedirectResponse
    {
        $this->appointment = $this->appointmentRepository->findWithoutFail($appointmentId);
        if (!empty($this->appointment)) {
            $intent = $this->getPaymentIntent($paymentIntentId);
            if (isset($intent['data']['attributes']['status'])) {
                if ($intent['data']['attributes']['status'] == "succeeded") {
                    $this->paymentMethodId = 12; // PayMongo method
                    $this->createAppointment();
                    return redirect(url('payments/paymongo'));
                }
            }
        }
        Flash::error("Error processing PayMongo payment for your appointment");
        return redirect(route('payments.failed'));
    }

    /**
     * @param $paymentIntentId
     * @return mixed|null
     */
    private function getPaymentIntent($paymentIntentId): mixed
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paymongo.com/v1/payment_intents/$paymentIntentId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . base64_encode(setting('paymongo_secret')),
                "Content-Type: application/json",
                "Accept: application/json",
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Flash::error($err);
            return null;
        } else {
            return json_decode($response, true);
        }
    }
}
