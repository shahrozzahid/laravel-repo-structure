<?php

namespace App\Dispatcher;

use App\Helpers\GeneralHelpers;

/**
 * Trait MailEventsDispatcher
 *
 * @package App\Dispatcher
 */
trait MailEventsDispatcher
{
    /**
     * Mail Data Container
     *
     * @var array
     */
    private $_data;

    /**
     * Send Forgot Password Email
     *
     * @return bool|mixed
     */
    public function sendForgotPasswordLink()
    {
        $this->_data = [
            'to'      => $this->email,
            'view'    => 'forgot_password',
            'subject' => 'Forgot Password',
            'params'  => [
                'name' => $this->name,
                'link' => route('user.reset.password.form', $this->remember_token),
            ]
        ];

        return $this->_dispatchEvent();
    }

    /**
     * Send Register Welcome Email
     *
     * @return bool|mixed
     */
    public function sendRegisterEmail()
    {
        $this->_data = [
            'to'      => $this->email,
            'view'    => 'register',         // resources/views/emails/register.blade.php
            'subject' => 'Welcome to '. config('app.name'),
            'params'  => [
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => $this->role,
            ]
        ];

        return $this->_dispatchEvent();
    }

    /**
     * Send Login Notification Email
     *
     * @return bool|mixed
     */
    public function sendLoginEmail()
    {
        $this->_data = [
            'to'      => $this->email,
            'view'    => 'login',            // resources/views/emails/login.blade.php
            'subject' => 'New Login Detected',
            'params'  => [
                'name'       => $this->name,
                'email'      => $this->email,
                'login_time' => now()->format('d M Y h:i A'),
            ]
        ];

        return $this->_dispatchEvent();
    }

    /**
     * Event Dispatcher
     *
     * @return bool|mixed
     */
    private function _dispatchEvent()
    {
        \Log::info('Mail data being sent: ', $this->_data);
        return GeneralHelpers::DISPATCH_MAIL($this->_data);
    }
}