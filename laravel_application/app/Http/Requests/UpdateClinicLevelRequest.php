<?php
/*
 * File name: UpdateClinicLevelRequest.php
 * Last modified: 2024.05.03 at 21:19:16
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Requests;

use App\Models\ClinicLevel;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicLevelRequest extends FormRequest
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
        return ClinicLevel::$rules;
    }
}
