<?php
/*
 * File name: ClinicUser.php
 * Last modified: 2024.05.03 at 19:14:31
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Eloquent as Model;

class ClinicUser extends Model
{
    use HasFactory;

    public $table = 'clinic_users';
    public $timestamps = false;
}
