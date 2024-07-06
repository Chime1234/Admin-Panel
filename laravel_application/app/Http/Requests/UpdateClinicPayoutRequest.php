<?php
/*
 * File name: UpdateClinicPayoutRequest.php
 * Last modified: 2024.05.03 at 16:06:29
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Requests;

use App\Models\ClinicPayout;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicPayoutRequest extends FormRequest
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
