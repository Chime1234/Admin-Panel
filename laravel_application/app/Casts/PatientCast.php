<?php
/*
 * File name: PatientCast.php
 * Last modified: 2024.05.03 at 21:35:24
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Casts;

use App\Models\Patient;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Class PatientCast
 * @package App\Casts
 */
class PatientCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): Patient
    {
        $decodedValue = json_decode($value, true);
        $patient = Patient::find($decodedValue['id']);
        if (!empty($patient)) {
            return $patient;
        }

        $patient = new Patient($decodedValue);
        $patient->fillable[] = 'id';
        $patient->id = $decodedValue['id'];
        return $patient;
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
//        if (!$value instanceof Patient) {
//            throw new InvalidArgumentException('The given value is not a Patient instance.');
//        }

        return [
            'patient' => json_encode(
                [
                    'id' => $value['id'],
                    'first_name' => $value['first_name'],
                    'last_name' => $value['last_name'],
                    'gender' => $value['gender'],
                    'age' => $value['age'],
                    'height' => $value['height'],
                    'weight' => $value['weight'],
                ]
            )
        ];
    }
}
