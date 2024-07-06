<?php
/*
 * File name: CreateClinicPayoutRequest.php
 * Last modified: 2024.05.03 at 12:37:41
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Requests;

use App\Models\ClinicPayout;
use Illuminate\Foundation\Http\FormRequest;

class CreateClinicPayoutRequest extends FormRequest
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
        return ClinicPayout::$rules;
    }
}
