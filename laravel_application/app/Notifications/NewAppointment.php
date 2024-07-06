<?php
/*
 * File name: NewAppointment.php
 * Last modified: 2024.05.03 at 22:25:44
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Notifications;

use App\Models\Appointment;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointment extends Notification
{
    use Queueable;

    /**
     * @var Appointment
     */
    private Appointment $appointment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        //
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        $types = ['database'];
        if (setting('enable_notifications', false)) {
            $types[] = 'fcm';
        }
        if (setting('enable_email_notifications', false)) {
            $types[] = 'mail';
        }
        return $types;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->markdown("notifications::appointment", ['appointment' => $this->appointment])
            ->subject(trans('lang.notification_new_appointment', ['appointment_id' => $this->appointment->id, 'user_name' => $this->appointment->user->name]) . " | " . setting('app_name', ''))
            ->greeting(trans('lang.notification_new_appointment', ['appointment_id' => $this->appointment->id, 'user_name' => $this->appointment->user->name]))
            ->action(trans('lang.appointment_details'), route('appointments.show', $this->appointment->id));
    }

    public function toFcm($notifiable): FcmMessage
    {
        $message = new FcmMessage();
        $notification = [
            'title' => $this->appointment->doctor->name,
            'body' => trans('lang.notification_new_appointment', ['appointment_id' => $this->appointment->id, 'user_name' => $this->appointment->user->name]),
            'icon' => $this->getDoctorMediaUrl(),
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => 'App\\Notifications\\NewAppointment',
            'status' => 'done',
        ];
        $data = $notification;
        $data['appointmentId'] = $this->appointment->id;
        $message->content($notification)->data($data)->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
    }

    private function getDoctorMediaUrl(): string
    {
        if ($this->appointment->doctor->hasMedia('image')) {
            return $this->appointment->doctor->getFirstMediaUrl('image', 'thumb');
        } else {
            return asset('images/image_default.png');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment['id'],
        ];
    }
}
