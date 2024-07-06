<?php
/*
 * File name: ClinicTax.php
 * Last modified: 2024.02.15 at 14:39:58
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Eloquent as Model;

class ClinicTax extends Model
{
    use HasFactory;

    public $table = 'clinic_taxes';
    public $timestamps = false;
}
