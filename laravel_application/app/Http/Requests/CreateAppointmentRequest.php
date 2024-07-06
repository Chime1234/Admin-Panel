<?php
/*
 * File name: CreateAppointmentRequest.php
 * Last modified: 2024.05.03 at 19:14:24
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Support\Facades\Request;

class CreateAppointmentRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return Appointment::$rules;
    }
}
