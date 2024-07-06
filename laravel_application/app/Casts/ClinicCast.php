<?php
/*
 * File name: ClinicCast.php
 * Last modified: 2024.05.03 at 19:14:22
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Casts;

use App\Models\Clinic;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Class ClinicCast
 * @package App\Casts
 */
class ClinicCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): Clinic
    {
        $decodedValue = json_decode($value, true);
        $clinic = Clinic::find($decodedValue['id']);
        if (!empty($clinic)) {
            return $clinic;
        }
        $clinic = new Clinic($decodedValue);
        $clinic->fillable[] = 'id';
        $clinic->id = $decodedValue['id'];
        return $clinic;
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
//        if (!$value instanceof Clinic) {
//            throw new InvalidArgumentException('The given value is not an Clinic instance.');
//        }
        return [
            'clinic' => json_encode([
                'id' => $value['id'],
                'name' => $value['name'],
                'phone_number' => $value['phone_number'],
                'mobile_number' => $value['mobile_number'],
            ])
        ];
    }
}
