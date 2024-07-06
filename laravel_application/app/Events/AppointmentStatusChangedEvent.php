<?php
/*
 * File name: BookingStatusChangedEvent.php
 * Last modified: 2024.02.16 at 18:16:51
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Appointment $appointment;

    /**
     * AppointmentChangedEvent constructor.
     * @param $appointment
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }


}
